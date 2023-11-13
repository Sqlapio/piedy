<?php

namespace App\Livewire;

use App\Models\DetalleAsignacion;
use App\Models\Disponible;
use App\Models\FacturaMultiple;
use App\Models\Servicio;
use App\Models\TasaBcv;
use App\Models\User;
use App\Models\VentaServicio;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\Actions;

class FacturarCliente extends Component
{
    use Actions;

    use WithPagination;

    public $servicios = [];
    public $total_vista;
    public $total_vista_bsd;
    public $buscar;

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

    public function total()
    {
        $tasa_bcv = TasaBcv::where('id', 1)->first()->tasa;

        $valores = [];

        for ($i=0; $i < count($this->servicios) ; $i++) {
            $costo = Disponible::where('id', $this->servicios[$i])->first()->costo;
            array_push($valores, $costo);
        }

        $this->total_vista = array_sum($valores);
        $this->total_vista_bsd = $this->total_vista * $tasa_bcv;

    }

    public function calculo($value)
    {

        $tasa_bcv = TasaBcv::where('id', 1)->first()->tasa;

        /**
         * Caso 1
         * Pago en efectivo usd o transferencia en zelle y el restante en
         * calquier metodo de pago en bolivares.
         * ---------------------------------------------------------------
         */
        if ($this->op1 == 'Efectivo Usd' || $this->op1 == 'Zelle')
        {
            //Evalua que el segundo metodo de pago no este vacio
            //--------------------------------------------------
            if($this->op2 == '')
            {
                $this->dialog()->error(
                    $title = 'Error !!!',
                    $description = 'Debe seleccionar el segundo metodo de pago antes de ejecutar el calculo.'
                );

                $this->reset(['valor_uno']);
            }

            if ($this->op2 == 'Efectivo Bsd' || $this->op2 == 'Pago movil' || $this->op2 == 'Transferencia' || $this->op2 == 'Punto de venta')
            {
                //Evalua que el monto a calcular no sea mayor o igual al total de la venta
                //------------------------------------------------------------------------
                if($this->valor_uno >= $this->total_vista){
                    $this->dialog()->error(
                        $title = 'Error !!!',
                        $description = 'El monto debe ser menor al valor total de la venta.'
                    );
                    $this->reset(['valor_uno']);
                }else{
                    $calculo_valor_dos = $this->total_vista - $this->valor_uno;
                    $this->valor_uno = number_format($this->valor_uno, 2, ",", ".");
                    $this->valor_dos = number_format(($calculo_valor_dos * $tasa_bcv), 2, ",", ".");
                }

            }
        }

        /**
         * Caso 2
         * Este contempla los metodos de pago en bolivares conbinados
         * ----------------------------------------------------------
         */
        if ($this->op1 == 'Efectivo Bsd' || $this->op1 == 'Pago movil' || $this->op1 == 'Transferencia' || $this->op1 == 'Punto de venta')
        {
            //Evalua que el segundo metodo de pago no este vacio
            //--------------------------------------------------
            if($this->op2 == '')
            {
                $this->dialog()->error(
                    $title = 'Error !!!',
                    $description = 'Debe seleccionar el segundo metodo de pago antes de ejecutar el calculo.'
                );

                $this->reset(['valor_uno']);
            }

            if ($this->op2 == 'Efectivo Bsd' || $this->op2 == 'Pago movil' || $this->op2 == 'Transferencia' || $this->op2 == 'Punto de venta')
            {
                //Evalua que el monto a calcular no sea mayor o igual al total de la venta
                //------------------------------------------------------------------------
                if($this->valor_uno >= $this->total_vista_bsd){
                    $this->dialog()->error(
                        $title = 'Error !!!',
                        $description = 'El monto debe ser menor al valor total de la venta.'
                    );
                    $this->reset(['valor_uno']);

                }else{
                    $calculo_valor_uno = $this->valor_uno;
                    $this->valor_uno = number_format(($calculo_valor_uno), 2, ",", ".");

                    $calculo_valor_dos = $this->total_vista_bsd - $calculo_valor_uno;
                    $this->valor_dos = number_format(($calculo_valor_dos), 2, ",", ".");
                }

            }
        }

        /**
         * Caso 2
         * Este contempla el pago en efectivo emn usd y el restante en
         * un transferncia en zelle
         * -----------------------------------------------------------
         */
        if ($this->op1 == 'Efectivo Usd')
        {
            //Evalua que el segundo metodo de pago no este vacio
            //--------------------------------------------------
            if($this->op2 == '')
            {
                $this->dialog()->error(
                    $title = 'Error !!!',
                    $description = 'Debe seleccionar el segundo metodo de pago antes de ejecutar el calculo.'
                );

                $this->reset(['valor_uno']);
            }

            if ($this->op2 == 'Zelle')
            {
                //Evalua que el monto a calcular no sea mayor o igual al total de la venta
                //------------------------------------------------------------------------
                if($this->valor_uno >= $this->total_vista){
                    $this->dialog()->error(
                        $title = 'Error !!!',
                        $description = 'El monto debe ser menor al valor total de la venta.'
                    );
                    $this->reset(['valor_uno']);
                }else{
                    $calculo_valor_dos = $this->total_vista - $this->valor_uno;
                    $this->valor_uno = number_format($this->valor_uno, 2, ",", ".");
                    $this->valor_dos = number_format($calculo_valor_dos, 2, ",", ".");
                }

            }
        }

        //Caso4
        if ($this->op1 == $this->op2)
        {
            $this->dialog()->error(
                $title = 'Error !!!',
                $description = 'Seleccione un método de pago distinto.'
            );
            $this->reset(['op2', 'valor_uno']);
        }


    }

    public function facturar_servicio(Request $request)
    {

        $user = Auth::user();

        /**
         * Pago total en DOLARES
         */
        if($this->descripcion == 'Efectivo Usd' || $this->descripcion == 'Zelle')
        {

            $factura = new FacturaMultiple();
            $factura->cod_asignacion    = 'FM-'.random_int(11111111, 99999999);
            $factura->responsable       = $user->name;
            $factura->metodo_pago       = 'Facturación multiple';
            $factura->referencia        = $factura->cod_asignacion;
            $factura->fecha_venta       = date('d-m-Y');
            $factura->pago_usd          = $this->total_vista;
            $factura->total_usd         = $this->total_vista;
            $factura->save();

                for ($i=0; $i < count($this->servicios) ; $i++)
                {
                    Disponible::where('id', $this->servicios[$i])->update([
                        'status' => 'facturado'
                    ]);

                    $cod_asignacion = Disponible::where('id', $this->servicios[$i])->first()->cod_asignacion;

                    DetalleAsignacion::where('cod_asignacion', $cod_asignacion)->update([
                        'status' => 2
                    ]);

                    VentaServicio::where('cod_asignacion', $cod_asignacion)->update([
                        'metodo_pago' => $factura->metodo_pago,
                        'referencia' => $factura->cod_asignacion
                    ]);
                }

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

            $factura = new FacturaMultiple();
            $factura->cod_asignacion    = 'FM-'.random_int(11111111, 99999999);
            $factura->responsable       = $user->name;
            $factura->metodo_pago       = 'Facturación multiple';
            $factura->referencia        = $factura->cod_asignacion;
            $factura->fecha_venta       = date('d-m-Y');
            $factura->pago_bsd          = $this->total_vista_bsd;
            $factura->total_usd         = $this->total_vista;
            $factura->save();

                for ($i=0; $i < count($this->servicios) ; $i++)
                {
                    Disponible::where('id', $this->servicios[$i])->update([
                        'status' => 'facturado'
                    ]);

                    $cod_asignacion = Disponible::where('id', $this->servicios[$i])->first()->cod_asignacion;

                    DetalleAsignacion::where('cod_asignacion', $cod_asignacion)->update([
                        'status' => 2
                    ]);

                    VentaServicio::where('cod_asignacion', $cod_asignacion)->update([
                        'metodo_pago' => $factura->metodo_pago,
                        'referencia' => $factura->cod_asignacion
                    ]);
                }

            Notification::make()
                ->title('La factura fue cerrada con exito')
                ->success()
                ->send();

            $this->redirect('/citas');

        }

        /**
         * Pago total en BOLIVARES con referenia
         */
        if($this->descripcion == 'Transferencia' || $this->descripcion == 'Pago movil' || $this->descripcion == 'Punto de venta')
        {

            $factura = new FacturaMultiple();
            $factura->cod_asignacion    = 'FM-'.random_int(11111111, 99999999);
            $factura->responsable       = $user->name;
            $factura->metodo_pago       = 'Facturación multiple';
            $factura->referencia        = $this->referencia;
            $factura->fecha_venta       = date('d-m-Y');
            $factura->pago_bsd          = $this->total_vista_bsd;
            $factura->total_usd         = $this->total_vista;
            $factura->save();

                for ($i=0; $i < count($this->servicios) ; $i++)
                {
                    Disponible::where('id', $this->servicios[$i])->update([
                        'status' => 'facturado'
                    ]);

                    $cod_asignacion = Disponible::where('id', $this->servicios[$i])->first()->cod_asignacion;

                    DetalleAsignacion::where('cod_asignacion', $cod_asignacion)->update([
                        'status' => 2
                    ]);

                    VentaServicio::where('cod_asignacion', $cod_asignacion)->update([
                        'metodo_pago' => $factura->metodo_pago,
                        'referencia' => $factura->cod_asignacion
                    ]);
                }

            Notification::make()
                ->title('La factura fue cerrada con exito')
                ->success()
                ->send();

            $this->redirect('/citas');

        }

        /**
         * Pago total en MULTIPLE
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
                    if($this->valor_uno == '' and $this->valor_dos == '')
                    {
                        $this->dialog()->error(
                            $title = 'Error !!!',
                            $description = 'El monto debe coincidir con el valor de factura.'
                        );
                    }elseif($this->valor_uno == 0){
                        $this->dialog()->error(
                            $title = 'Error !!!',
                            $description = 'Los monto deben ser mayores a cero.'
                        );
                    }else{
                        $this->referencia = 'pago multiple';
                        $facturar = DB::table('venta_servicios')
                            ->where('cod_asignacion', $item->cod_asignacion)
                            ->update([
                                'metodo_pago' => $this->descripcion,
                                'referencia' => $this->referencia,
                                'total_USD' => $total_vista,
                                'pago_usd' => floatval($this->valor_uno),
                                'pago_bsd' => Str::replace(',', '.', (Str::replace('.', '', $this->valor_dos))),
                                // 'comision_empleado' => UtilsController::cal_comision_empleado($total_vista),
                                // 'comision_gerente' => UtilsController::cal_comision_gerente($total_vista),
                            ]);

                        DetalleAsignacion::where('cod_asignacion', $item->cod_asignacion)
                            ->where('status', '1')
                            ->update([
                                'status' => '2' //cerrado todos los detalles del servicio
                            ]);

                            Disponible::where('cod_asignacion', $item->cod_asignacion)
                            ->where('status', 'por facturar')
                            ->update([
                                'status' => 'facturado'
                            ]);

                        Notification::make()
                            ->title('La factura fue cerrada con exito')
                            ->success()
                            ->send();

                        $this->redirect('/citas');

                        $user = User::where('id', $item->empleado_id)->first();
                        $detalle = DetalleAsignacion::where('cod_asignacion', $item->cod_asignacion)->get();
                        $type = 'servicio';
                        $mailData = [
                            'codigo' => $item->cod_asignacion,
                            'user_email' => $user->email,
                            'user_fullname' => $item->empleado,
                            'cliente_fullname' => $item->cliente,
                            'fecha_venta' => $item->fecha_venta,
                            'detalle' => $detalle,
                        ];

                        NotificacionesController::notification($mailData, $type);
                    }

                }
            }

            /**
             * CASO 2
             */
            if ($this->op1 == 'Efectivo Bsd' || $this->op1 == 'Pago movil' || $this->op1 == 'Transferencia' || $this->op1 == 'Punto de venta')
            {
                if ($this->op2 == 'Efectivo Bsd' || $this->op2 == 'Pago movil' || $this->op2 == 'Transferencia' || $this->op2 == 'Punto de venta')
                {
                    if($this->valor_uno == '' and $this->valor_dos == '')
                    {
                        $this->dialog()->error(
                            $title = 'Error !!!',
                            $description = 'Los monto deben ser myor a 0.'
                        );
                    }else{
                        $this->referencia = 'pago multiple';
                        $facturar = DB::table('venta_servicios')
                            ->where('cod_asignacion', $item->cod_asignacion)
                            ->update([
                                'metodo_pago' => $this->descripcion,
                                'referencia' => $this->referencia,
                                'total_USD' => $total_vista,
                                'pago_bsd' => $total_vista_bsd,
                                // 'comision_empleado' => UtilsController::cal_comision_empleado($total_vista),
                                // 'comision_gerente' => UtilsController::cal_comision_gerente($total_vista),
                            ]);

                        DetalleAsignacion::where('cod_asignacion', $item->cod_asignacion)
                            ->where('status', '1')
                            ->update([
                                'status' => '2' //cerrado todos los detalles del servicio
                            ]);

                            Disponible::where('cod_asignacion', $item->cod_asignacion)
                            ->where('status', 'por facturar')
                            ->update([
                                'status' => 'facturado'
                            ]);

                        Notification::make()
                            ->title('La factura fue cerrada con exito')
                            ->success()
                            ->send();

                        $this->redirect('/citas');

                        $user = User::where('id', $item->empleado_id)->first();
                        $detalle = DetalleAsignacion::where('cod_asignacion', $item->cod_asignacion)->get();
                        $type = 'servicio';
                        $mailData = [
                            'codigo' => $item->cod_asignacion,
                            'user_email' => $user->email,
                            'user_fullname' => $item->empleado,
                            'cliente_fullname' => $item->cliente,
                            'fecha_venta' => $item->fecha_venta,
                            'detalle' => $detalle,
                        ];

                        NotificacionesController::notification($mailData, $type);
                    }

                }
            }

            /**
             * CASO 3
             */
            if ($this->op1 == 'Efectivo Usd')
            {
                if ($this->op2 == 'Zelle')
                {
                    if($this->valor_uno == '' and $this->valor_dos == '')
                    {
                        $this->dialog()->error(
                            $title = 'Error !!!',
                            $description = 'Los monto deben ser myor a 0.'
                        );
                    }else{
                        $this->referencia = 'pago multiple';
                        $facturar = DB::table('venta_servicios')
                            ->where('cod_asignacion', $item->cod_asignacion)
                            ->update([
                                'metodo_pago' => $this->descripcion,
                                'referencia' => $this->referencia,
                                'total_USD' => $total_vista,
                                'pago_usd' => $total_vista,
                                // 'comision_empleado' => UtilsController::cal_comision_empleado($total_vista),
                                // 'comision_gerente' => UtilsController::cal_comision_gerente($total_vista),
                            ]);

                        DetalleAsignacion::where('cod_asignacion', $item->cod_asignacion)
                            ->where('status', '1')
                            ->update([
                                'status' => '2' //cerrado todos los detalles del servicio
                            ]);

                            Disponible::where('cod_asignacion', $item->cod_asignacion)
                            ->where('status', 'por facturar')
                            ->update([
                                'status' => 'facturado'
                            ]);

                        Notification::make()
                            ->title('La factura fue cerrada con exito')
                            ->success()
                            ->send();

                        $this->redirect('/citas');

                        $user = User::where('id', $item->empleado_id)->first();
                        $detalle = DetalleAsignacion::where('cod_asignacion', $item->cod_asignacion)->get();
                        $type = 'servicio';
                        $mailData = [
                            'codigo' => $item->cod_asignacion,
                            'user_email' => $user->email,
                            'user_fullname' => $item->empleado,
                            'cliente_fullname' => $item->cliente,
                            'fecha_venta' => $item->fecha_venta,
                            'detalle' => $detalle,
                        ];

                        NotificacionesController::notification($mailData, $type);
                    }

                }
            }

        }

    }

    public function render()
    {
        if($this->descripcion == 'Pago movil' || $this->descripcion == 'Transferencia' || $this->descripcion == 'Zelle' || $this->descripcion == 'Punto de venta')
        {
            $this->ref_hidden = '';
            $this->op1_hidden = 'hidden';
            $this->op2_hidden = 'hidden';
        }

        if($this->descripcion == 'Multiple')
        {
            $this->op1_hidden = '';
            $this->op2_hidden = '';
            $this->ref_hidden = 'hidden';
        }

        if($this->descripcion == 'Efectivo Usd' || $this->descripcion == 'Efectivo Bsd')
        {
            $this->ref_hidden = 'hidden';
            $this->op1_hidden = 'hidden';
            $this->op2_hidden = 'hidden';
        }

        if($this->descripcion == '')
        {
            $this->ref_hidden = 'hidden';
            $this->op1_hidden = 'hidden';
            $this->op2_hidden = 'hidden';
        }

        return view('livewire.facturar-cliente',[
            'data' => Disponible::Where('status', 'por facturar')
            ->Where('cliente', 'like', "%{$this->buscar}%")
            ->orderBy('id', 'desc')
               ->paginate(4)
       ]);

    }
}
