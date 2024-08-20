<?php

namespace App\Livewire;

use App\Http\Controllers\UtilsController;
use App\Models\TasaBcv;
use App\Models\VentaProducto;
use App\Models\VentaServicio;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class CajaProducto extends Component
{
    public $cliente_id;
    public $op1_hidden = 'hidden';
    public $op2_hidden = 'hidden';
    public $ref_hidden = 'hidden';
    public $op1;
    public $op2;
    public $valor_uno;
    public $valor_dos;
    public $ref_usd;
    public $ref_bsd;
    public $descripcion = 'Multiple';
    public $referencia;

    public function event()
    {

        if ($this->descripcion == 'Multiple') {
            $this->op1_hidden = '';
            $this->op2_hidden = '';
            $this->ref_hidden = '';
        }

    }

    public function calculo(Request $request)
    {
        $codigo = $request->session()->get('cod_asignacion');

        $tasa_bcv = TasaBcv::where('id', 1)->first()->tasa;

        $total_productos = DB::table('venta_productos')
            ->select(DB::raw('SUM(total_venta) as total'))
            ->where('cod_asignacion', $codigo)
            ->where('status', '1')
            ->where('facturado', 1)
            ->first()->total;

        $total_vista = $total_productos;
        $total_vista_bsd = $total_productos * $tasa_bcv;

        /**
         * Caso 1
         * Pago en efectivo usd o transferencia en zelle y el restante en
         * calquier metodo de pago en bolivares.
         * ---------------------------------------------------------------
         */

        if ($this->op1 == 'Efectivo Usd' || $this->op1 == 'Zelle' || $this->op1 == '' && $this->op2 == '' || $this->op2 == 'Efectivo Bsd' || $this->op2 == 'Pago movil' || $this->op2 == 'Transferencia' || $this->op2 == 'Punto de venta') {
            if ($this->op1 == '' && $this->op2 == '') {
                $this->dialog()->error(
                    $title = 'Error !!!',
                    $description = 'Debe seleccionar la forma de pago antes de ejecutar el calculo.'
                );

                $this->reset(['valor_uno']);
            } else {
                $calculo_valor_dos = $total_vista - $this->valor_uno;
                $this->valor_uno = number_format($this->valor_uno, 2, ",", ".");
                $this->valor_dos = number_format(($calculo_valor_dos * $tasa_bcv), 2, ",", ".");
            }
        }
    }

    public function facturar_servicio(Request $request)
    {
        if ($this->descripcion == '') {
            $this->dialog()->error(
                $title = 'Error !!!',
                $description = 'Debe seleccionar un método de pago de lo contrario no podra facturar el servicio'
            );
        } else {

            /**
             * El codigo es tomado de la variables de sesion
             * del usuario
             *
             * @param $codigo
             */
            $codigo = $request->session()->get('cod_asignacion');

            $data = VentaProducto::where('cod_asignacion', $codigo)->first();

            $tasa_bcv = TasaBcv::where('id', 1)->first()->tasa;

            $total_productos = DB::table('venta_productos')
                ->select(DB::raw('SUM(total_venta) as total'))
                ->where('cod_asignacion', $codigo)
                ->where('status', '1')
                ->where('facturado', 1)
                ->first()->total;
            
            $total_comisiones = DB::table('venta_productos')
                ->select(DB::raw('SUM(comision_gerente) as total'))
                ->where('cod_asignacion', $codigo)
                ->where('status', '1')
                ->where('facturado', 1)
                ->first()->total;

            $total_vista = $total_productos;

            /**
             * Pago total en ZELLE
             */
            if ($this->descripcion == 'Multiple') {
                try {

                        if ($this->ref_usd == '' && $this->ref_bsd == '') {
                            $this->referencia = '';
                        }

                        if ($this->ref_usd != '' && $this->ref_bsd == '') {
                            $this->referencia = $this->ref_usd;
                        }

                        if ($this->ref_usd == '' && $this->ref_bsd != '') {
                            $this->referencia = $this->ref_bsd;
                        }

                        if ($this->ref_usd != '' && $this->ref_bsd != '') {
                            $this->referencia = $this->ref_usd . '-' . $this->ref_bsd;
                        }

                        /**
                         * Actualizamos la tabla de ventas, Detalles de asignacion y Disponible
                         */
                        $facturar = new VentaServicio();
                        $facturar->cod_asignacion        = $codigo;
                        $facturar->metodo_pago           = ($this->op1 != '') ? $this->op1 : 'N/A';
                        $facturar->metodo_pago_dos       = ($this->op2 != '') ? $this->op2 : 'N/A';
                        $facturar->metodo_pago_prepagado = 'N/A';
                        $facturar->referencia            = ($this->referencia == '') ? $this->referencia : 'N/A';
                        $facturar->total_USD             = $total_vista;
                        $facturar->pago_usd              = ($this->valor_uno == '') ? 0.00 : floatval($this->valor_uno);
                        $facturar->pago_bsd              = ($this->valor_dos == '') ? 0.00 : Str::replace(',', '.', (Str::replace('.', '', $this->valor_dos)));
                        $facturar->propina_usd           = 0.00;
                        $facturar->propina_bsd           = 0.00;
                        $facturar->comision_dolares      = 0.00;
                        $facturar->comision_bolivares    = 0.00;
                        $facturar->comision_gerente      = $total_comisiones;
                        $facturar->comision_emp_venprod  = 0.00;
                        $facturar->responsable_id        = Auth::user()->id;
                        $facturar->responsable           = Auth::user()->name;
                        $facturar->fecha_venta           = now()->format('d-m-Y');
                        $facturar->empleado              = Auth::user()->name;
                        $facturar->empleado_id           = Auth::user()->id;
                        $facturar->cliente               = $data->cliente;
                        $facturar->cliente_id           = $data->cliente_id;
                        $facturar->save();

                        /**Estatus facturado para los productos vendidos por el gerente de tienda o el encargado de tienda */
                        $prod_facturado_gerente = VentaProducto::where('cod_asignacion', $codigo)
                        ->where('facturado', 1)
                        ->where('fecha_venta', now()->format('d-m-Y'))
                        ->where('responsable', Auth::user()->name)
                        ->where('rol', 'gerente')
                        ->get();
                        foreach($prod_facturado_gerente as $value)
                        {
                            $value->facturado = 2;
                            $value->save();
                        }

                        Notification::make()
                            ->title('La factura fue cerrada con exito')
                            ->success()
                            ->send();

                        $this->redirect('/cabinas');

                } catch (\Throwable $th) {
                    Notification::make()
                        ->title('NOTIFICACIÓN')
                        ->icon('heroicon-o-shield-check')
                        ->color('danger')
                        ->body($th->getMessage())
                        ->send();
                }
            }
        }
    }

    public function render(Request $request)
    {
        $codigo = $request->session()->get('cod_asignacion');

        $tasa_bcv = TasaBcv::where('id', 1)->first()->tasa;

        $this->event();

        $data = VentaProducto::where('cod_asignacion', $codigo)->first();

        /**Seleccion los productos que voy a vender y los muestro en la lista de 'Productos cargados' */
        $lista_prod_ven_gerente = VentaProducto::where('status', '1')
            ->where('cod_asignacion', $codigo)
            ->where('facturado', 1)
            ->where('fecha_venta', now()->format('d-m-Y'))
            ->where('responsable', Auth::user()->name)
            ->where('rol', 'gerente')
            ->with('producto')
            ->get();

        /**Calculo el total de los productos vendidos por el tecnico */
        $total_productos = DB::table('venta_productos')
            ->select(DB::raw('SUM(total_venta) as total'))
            ->where('cod_asignacion', $codigo)
            ->where('status', '1')
            ->where('facturado', 1)
            ->where('rol', 'gerente')
            ->first()->total;

        $total_productos;
        $total_vista_bsd = $total_productos * $tasa_bcv;

        return view('livewire.caja-producto', compact('data', 'lista_prod_ven_gerente', 'total_productos', 'total_vista_bsd'));
    }
}
