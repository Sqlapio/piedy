<?php

namespace App\Livewire;

use App\Http\Controllers\UtilsController;
use App\Models\DetalleAsignacion;
use App\Models\TasaBcv;
use App\Models\VentaServicio;
use Barryvdh\Debugbar\Facades\Debugbar;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Rule;
use WireUi\Traits\Actions;
use Illuminate\Support\Str;
use Livewire\Component;

class Caja extends Component
{

    use Actions;


    public $op1;
    public $op2;
    public $valor_uno;
    public $valor_dos;

    #[Rule('required')]
    public $referencia;

    public $descripcion;
    public $op1_hidden = 'hidden';
    public $op2_hidden = 'hidden';
    public $ref_hidden = 'hidden'; 

    protected $messages = [

        'dolares'     => 'Campo requerido',

    ];

    protected $listeners = [
        'calculo',
    ];

    public function event()
    {
        if($this->descripcion == 'Pago movil' || 
            $this->descripcion == 'Transferncia' || 
            $this->descripcion == 'Zelle' || 
            $this->descripcion == 'Efectivo Usd' ||
            $this->descripcion == 'Efectivo Bsd')
        {
            $this->ref_hidden = '';
        }

        if($this->descripcion == 'Multiple')
        {
            $this->op1_hidden = '';
            $this->op2_hidden = '';
        }

        if($this->descripcion == ''){
            $this->reset();
        }
    }

    public function calculo($value)
    {
        $item = VentaServicio::all()->last();

        $total = DB::table('detalle_asignacions')
        ->select(DB::raw('SUM(costo) as total'))
        ->where('cod_asignacion', $item->cod_asignacion)
            ->where('status', '2')
            ->first();

        $tasa_bcv = TasaBcv::where('id', 1)->first()->tasa;

        $total_vista = $total->total;
        $total_vista_bsd = $total_vista * $tasa_bcv;

        $tasa_bcv = TasaBcv::where('id', 1)->first()->tasa;

        //Caso 1
        if ($this->op1 == 'Efectivo Usd' || $this->op1 == 'Zelle') {
            if ($this->op2 == 'Efectivo Bsd' || $this->op2 == 'Pago movil' || $this->op2 == 'Transferencia' || $this->op2 == 'Punto de venta') {
                $calculo_valor_dos = $total_vista - $this->valor_uno;
                $this->valor_uno = number_format($this->valor_uno, 2, ",", ".");
                $this->valor_dos = number_format(($calculo_valor_dos * $tasa_bcv), 2, ",", ".");
            }
        }

        //Caso 2
        if ($this->op1 == 'Efectivo Bsd' || $this->op1 == 'Pago movil' || $this->op1 == 'Transferencia' || $this->op1 == 'Punto de venta') {
            if ($this->op2 == 'Efectivo Bsd' || $this->op2 == 'Pago movil' || $this->op2 == 'Transferencia' || $this->op2 == 'Punto de venta') {
                $calculo_valor_uno = $this->valor_uno * $tasa_bcv;
                $this->valor_uno = number_format(($calculo_valor_uno), 2, ",", ".");

                $calculo_valor_dos = $total_vista_bsd - $calculo_valor_uno;
                $this->valor_dos = number_format(($calculo_valor_dos), 2, ",", ".");
            }
        }

        //Caso3
        if ($this->op1 == 'Efectivo Usd') {
            if ($this->op2 == 'Zelle') {
                $calculo_valor_dos = $total_vista - $this->valor_uno;
                $this->valor_uno = number_format($this->valor_uno, 2, ",", ".");
                $this->valor_dos = number_format($calculo_valor_dos, 2, ",", ".");
            }
        }

    }

    public function eliminar_servicio($value)
    {
        $this->dialog()->confirm([
            'title'       => 'seguro deseas eliminar el servicio?',
            'description' => 'Esta operación por seguridad no podra ser reversada.',
            'acceptLabel' => 'Si, eliminar',
            'method'      => 'delete',
            'params'      => $value,
        ]);  
    }

    public function delete($value)
    {
        try {
            $item = DetalleAsignacion::where('id', $value)->update(['status' => 3]);
        } catch (\Throwable $th) {
            dd($th);
        }
        
    }

    public function facturar_servicio()
    {
        
        $item = VentaServicio::all()->last();
        Debugbar::info($item);
        
        $total = DB::table('detalle_asignacions')
            ->select(DB::raw('SUM(costo) as total'))
            ->where('cod_asignacion', $item->cod_asignacion)
            ->where('status', '2')
            ->first();

        $total_vista = $total->total;

        $tasa_bcv = TasaBcv::where('id', 1)->first()->tasa;

        $total_vista_bsd = $total_vista * $tasa_bcv;

        /**
         * Pago total en DOLARES
         */
        if($this->descripcion == 'Efectivo Usd')
        {

            $this->validate();
            $facturar = DB::table('venta_servicios')
                ->where('cod_asignacion', $item->cod_asignacion)
                ->update([
                    'metodo_pago' => $this->descripcion,
                    'referencia' => $this->referencia,
                    'pago_usd' => $total_vista,
                    'comision_empleado' => UtilsController::cal_comision_empleado($total_vista),
                    'comision_gerente' => UtilsController::cal_comision_gerente($total_vista),
                ]);

                Notification::make()
                ->title('La factura fue cerrada con exito')
                ->success()
                ->send();

            $this->redirect('/citas');
        }

        /**
         * Pago total en BOLIVARES
         */
        if($this->descripcion == 'Efectivo Bsd')
        {
            $this->validate();
            $facturar = DB::table('venta_servicios')
                ->where('cod_asignacion', $item->cod_asignacion)
                ->update([
                    'metodo_pago' => $this->descripcion,
                    'referencia' => $this->referencia,
                    'pago_bsd' => $total_vista * $tasa_bcv,
                    'comision_empleado' => UtilsController::cal_comision_empleado($total_vista),
                    'comision_gerente' => UtilsController::cal_comision_gerente($total_vista),
                ]);
            
                Notification::make()
                ->title('La factura fue cerrada con exito')
                ->success()
                ->send();

            $this->redirect('/citas');
        }

        /**
         * Pago total en PAGO MOVIL
         */
        if($this->descripcion == 'Pago movil')
        {
            $this->validate();
            $facturar = DB::table('venta_servicios')
                ->where('cod_asignacion', $item->cod_asignacion)
                ->update([
                    'metodo_pago' => $this->descripcion,
                    'referencia' => $this->referencia,
                    'pago_bsd' => $total_vista * $tasa_bcv,
                    'comision_empleado' => UtilsController::cal_comision_empleado($total_vista),
                    'comision_gerente' => UtilsController::cal_comision_gerente($total_vista),
                ]);
            
                Notification::make()
                ->title('La factura fue cerrada con exito')
                ->success()
                ->send();

            $this->redirect('/citas');
        }

        /**
         * Pago total en PUNTO DE VENTA
         */
        if($this->descripcion == 'Pago de venta')
        {
            $this->validate();
            $facturar = DB::table('venta_servicios')
                ->where('cod_asignacion', $item->cod_asignacion)
                ->update([
                    'metodo_pago' => $this->descripcion,
                    'referencia' => $this->referencia,
                    'pago_bsd' => $total_vista * $tasa_bcv,
                    'comision_empleado' => UtilsController::cal_comision_empleado($total_vista),
                    'comision_gerente' => UtilsController::cal_comision_gerente($total_vista),
                ]);

                Notification::make()
                ->title('La factura fue cerrada con exito')
                ->success()
                ->send();

            $this->redirect('/citas');
        }

        /**
         * Pago total en TRANSFERENCIA
         */
        if($this->descripcion == 'Transferencia')
        {
            $this->validate();
            $facturar = DB::table('venta_servicios')
                ->where('cod_asignacion', $item->cod_asignacion)
                ->update([
                    'metodo_pago' => $this->descripcion,
                    'referencia' => $this->referencia,
                    'pago_bsd' => $total_vista * $tasa_bcv,
                    'comision_empleado' => UtilsController::cal_comision_empleado($total_vista),
                    'comision_gerente' => UtilsController::cal_comision_gerente($total_vista),
                ]);
            
                Notification::make()
                ->title('La factura fue cerrada con exito')
                ->success()
                ->send();

            $this->redirect('/citas');
        }

        /**
         * Pago total en ZELLE
         */
        if($this->descripcion == 'Zelle')
        {
            $this->validate();
            $facturar = DB::table('venta_servicios')
                ->where('cod_asignacion', $item->cod_asignacion)
                ->update([
                    'metodo_pago' => $this->descripcion,
                    'referencia' => $this->referencia,
                    'pago_usd' => $total_vista,
                    'comision_empleado' => UtilsController::cal_comision_empleado($total_vista),
                    'comision_gerente' => UtilsController::cal_comision_gerente($total_vista),
                ]);
            
                Notification::make()
                ->title('La factura fue cerrada con exito')
                ->success()
                ->send();

            $this->redirect('/citas');
        }

        /**
         * Pago total en ZELLE
         */
        if($this->descripcion == 'Multiple')
        {
            /**
             * CASO 1
             */
            if ($this->op1 == 'Efectivo Usd' || $this->op1 == 'Zelle') 
            {
                if ($this->op2 == 'Efectivo Bsd' || $this->op2 == 'Pago movil' || $this->op2 == 'Transferencia' || $this->op2 == 'Punto de venta') 
                {
                    $this->referencia = 'pago multiple';
                    $facturar = DB::table('venta_servicios')
                    ->where('cod_asignacion', $item->cod_asignacion)
                    ->update([
                        'metodo_pago' => $this->descripcion,
                        'referencia' => $this->referencia,
                        'pago_usd' => floatval($this->valor_uno),
                        'pago_bsd' => Str::replace(',', '.', (Str::replace('.', '', $this->valor_dos))),
                        'comision_empleado' => UtilsController::cal_comision_empleado($total_vista),
                        'comision_gerente' => UtilsController::cal_comision_gerente($total_vista),
                    ]);

                    Notification::make()
                        ->title('La factura fue cerrada con exito')
                        ->success()
                        ->send();

                    $this->redirect('/citas');
                }
            }

            /**
             * CASO 2
             */
            if ($this->op1 == 'Efectivo Bsd' || $this->op1 == 'Pago movil' || $this->op1 == 'Transferencia' || $this->op1 == 'Punto de venta') 
            {
                if ($this->op2 == 'Efectivo Bsd' || $this->op2 == 'Pago movil' || $this->op2 == 'Transferencia' || $this->op2 == 'Punto de venta') 
                {

                    $this->referencia = 'pago multiple';
                    $facturar = DB::table('venta_servicios')
                    ->where('cod_asignacion', $item->cod_asignacion)
                    ->update([
                        'metodo_pago' => $this->descripcion,
                        'referencia' => $this->referencia,
                        'pago_bsd' => $total_vista_bsd,
                        'comision_empleado' => UtilsController::cal_comision_empleado($total_vista),
                        'comision_gerente' => UtilsController::cal_comision_gerente($total_vista),
                    ]);

                    Notification::make()
                        ->title('La factura fue cerrada con exito')
                        ->success()
                        ->send();

                    $this->redirect('/citas');
                }
            }

            /**
             * CASO 3
             */
            if ($this->op1 == 'Efectivo Usd') 
            {
                if ($this->op2 == 'Zelle') 
                {
                    $this->referencia = 'pago multiple';
                    $facturar = DB::table('venta_servicios')
                    ->where('cod_asignacion', $item->cod_asignacion)
                    ->update([
                        'metodo_pago' => $this->descripcion,
                        'referencia' => $this->referencia,
                        'pago_usd' => $total_vista,
                        'comision_empleado' => UtilsController::cal_comision_empleado($total_vista),
                        'comision_gerente' => UtilsController::cal_comision_gerente($total_vista),
                    ]);

                    Notification::make()
                        ->title('La factura fue cerrada con exito')
                        ->success()
                        ->send();

                    $this->redirect('/citas');
                }
            }

        }

    }

    public function render()
    {
        $this->event();

        $data = VentaServicio::all()->last();

        $detalle = DetalleAsignacion::where('cod_asignacion', $data->cod_asignacion)
        ->where('status', '2')
        ->get();

        $total = DB::table('detalle_asignacions')
            ->select(DB::raw('SUM(costo) as total'))
            ->where('cod_asignacion', $data->cod_asignacion)
            ->where('status', '2')
            ->first();

        $total_vista = $total->total;

        $tasa_bcv = TasaBcv::where('id', 1)->first()->tasa;
        $total_vista_bsd = $total_vista * $tasa_bcv;

        return view('livewire.caja', compact('data', 'detalle', 'total_vista', 'total_vista_bsd'));
    }
}
