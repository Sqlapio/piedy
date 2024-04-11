<?php

namespace App\Livewire;

use App\Http\Controllers\UtilsController;
use App\Http\Controllers\NotificacionesController;
use App\Models\DetalleAsignacion;
use App\Models\Disponible;
use App\Models\Servicio;
use App\Models\TasaBcv;
use App\Models\User;
use App\Models\VentaServicio;
use Barryvdh\Debugbar\Facades\Debugbar;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Rule;
use WireUi\Traits\Actions;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Caja extends Component
{

    use Actions;

    public $op1;
    public $op2;
    public $valor_uno;
    public $valor_dos;

    public $propina_usd;
    public $propina_bsd;

    #[Rule('required')]
    public $referencia;

    public $prueba;

    public $descripcion;
    public $op1_hidden = 'hidden';
    public $op2_hidden = 'hidden';
    public $ref_hidden = 'hidden';

    public $option = false;

    protected $messages = [
        'dolares'     => 'Campo requerido',
    ];

    protected $listeners = [
        'calculo',
    ];

    public function event()
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
            $this->ref_hidden = '';
        }

        if($this->descripcion == 'Efectivo Usd' || $this->descripcion == 'Efectivo Bsd')
        {
            $this->ref_hidden = 'hidden';
            $this->op1_hidden = 'hidden';
            $this->op2_hidden = 'hidden';
        }

        if($this->descripcion == ''){
            $this->reset();
        }
    }

    public function calculo(Request $request)
    {
        $codigo = $request->session()->all();

        $item = VentaServicio::where('cod_asignacion', $codigo['cod_asignacion'])->first();

        $total = DB::table('detalle_asignacions')
        ->select(DB::raw('SUM(costo) as total'))
        ->where('cod_asignacion', $item->cod_asignacion)
            ->where('status', '1')
            ->first();

        $tasa_bcv = TasaBcv::where('id', 1)->first()->tasa;

        $total_vista = $total->total;
        $total_vista_bsd = $total_vista * $tasa_bcv;

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
                if($this->valor_uno >= $total_vista){
                    $this->dialog()->error(
                        $title = 'Error !!!',
                        $description = 'El monto debe ser menor al valor total de la venta.'
                    );
                    $this->reset(['valor_uno']);
                }else{
                    $calculo_valor_dos = $total_vista - $this->valor_uno;
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
                if($this->valor_uno >= $total_vista_bsd){
                    $this->dialog()->error(
                        $title = 'Error !!!',
                        $description = 'El monto debe ser menor al valor total de la venta.'
                    );
                    $this->reset(['valor_uno']);

                }else{
                    $calculo_valor_uno = $this->valor_uno;
                    $this->valor_uno = number_format(($calculo_valor_uno), 2, ",", ".");

                    $calculo_valor_dos = $total_vista_bsd - $calculo_valor_uno;
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
                if($this->valor_uno >= $total_vista){
                    $this->dialog()->error(
                        $title = 'Error !!!',
                        $description = 'El monto debe ser menor al valor total de la venta.'
                    );
                    $this->reset(['valor_uno']);
                }else{
                    $calculo_valor_dos = $total_vista - $this->valor_uno;
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

    public function facturar_servicio(Request $request)
    {

        if($this->descripcion == ''){
            $this->dialog()->error(
                $title = 'Error !!!',
                $description = 'Debe seleccionar un método de pago de lo contrario no podra facturar el servicio'
            );
        }else{

            /**
             * El codigo es tomado de la variables de sesion
             * del usuario
             * @param $codigo
             */
            $codigo = $request->session()->all();

            $item = VentaServicio::where('cod_asignacion', $codigo['cod_asignacion'])->first();

            $total = DB::table('detalle_asignacions')
                ->select(DB::raw('SUM(costo) as total'))
                ->where('cod_asignacion', $item->cod_asignacion)
                ->where('status', '1')
                ->first();

            $total_vista = $total->total;

            $tasa_bcv = TasaBcv::where('id', 1)->first()->tasa;

            $total_vista_bsd = $total_vista * $tasa_bcv;

            /**
             * Pago total en DOLARES
             */
            if($this->descripcion == 'Efectivo Usd')
            {
                try {

                    /**Obtengo el codigo del servicio a facturar */
                    $cod_srv_vip = DetalleAsignacion::where('cod_asignacion', $item->cod_asignacion)->first()->cod_servicio;

                    /**Pregunto? si la asignacion del servicio en VIP */
                    $srv_vip_tipo = Servicio::where('cod_servicio', $cod_srv_vip)->first()->asignacion;

                    /**Realizo el calculo de las respectivas comisiones */
                    if($srv_vip_tipo == 'vip'){

                        $comision_dolares_emp = UtilsController::comision_empleado_srvvip($total_vista);

                        $comision_dolares_gte_vip = UtilsController::comision_gerente_srvvip($total_vista);

                    }else{
                        /**Si el servicio facturado es diferente de los VIP */
                        $comision_dolares_emp = UtilsController::cal_comision_empleado($total_vista);

                    }

                    $facturar = DB::table('venta_servicios')->where('cod_asignacion', $item->cod_asignacion)
                        ->update([
                            'metodo_pago'   => $this->descripcion,
                            'referencia'    => $this->referencia,
                            'total_USD'     => $total_vista,
                            'pago_usd'      => $total_vista,
                            'propina_usd'   => $this->propina_usd != '' ? $this->propina_usd : 0.00,
                            'propina_bsd'   => $this->propina_bsd != '' ? $this->propina_bsd : 0.00,
                            'comision_dolares' => $comision_dolares_emp,
                            'comision_gerente' => (isset($comision_dolares_gte_vip)) ? $comision_dolares_gte_vip : 0,
                            'responsable'   => Auth::user()->name,
                        ]);

                    DetalleAsignacion::where('cod_asignacion', $item->cod_asignacion)->where('status', '1')
                        ->update([
                            'status' => '2',

                        ]);

                    Disponible::where('cod_asignacion', $item->cod_asignacion)->where('status', 'por facturar')
                        ->update([
                            'status' => 'facturado'
                        ]);

                    Notification::make()
                        ->title('La factura fue cerrada con exito')
                        ->success()
                        ->send();

                    $this->redirect('/cabinas');

                } catch (\Throwable $th) {
                    dd($th);
                }

            }

            /**
             * Pago total en BOLIVARES
             */
            if($this->descripcion == 'Efectivo Bsd')
            {

                try {

                    $facturar = DB::table('venta_servicios')->where('cod_asignacion', $item->cod_asignacion)
                        ->update([
                            'metodo_pago' => $this->descripcion,
                            'referencia' => $this->referencia,
                            'total_USD' => $total_vista,
                            'pago_bsd' => $total_vista * $tasa_bcv,
                            'propina_usd'   => $this->propina_usd != '' ? $this->propina_usd : 0.00,
                            'propina_bsd'   => $this->propina_bsd != '' ? $this->propina_bsd : 0.00,
                            'comision_bolivares' => UtilsController::cal_comision_empleado($total_vista * $tasa_bcv),
                            'responsable'   => Auth::user()->name,
                        ]);

                    DetalleAsignacion::where('cod_asignacion', $item->cod_asignacion)->where('status', '1')
                        ->update([
                            'status' => '2',
                        ]);

                    Disponible::where('cod_asignacion', $item->cod_asignacion)->where('status', 'por facturar')
                        ->update([
                            'status' => 'facturado'
                        ]);

                    Notification::make()
                        ->title('La factura fue cerrada con exito')
                        ->success()
                        ->send();

                    $this->redirect('/cabinas');

                } catch (\Throwable $th) {
                    //throw $th;
                }

            }

            /**
             * Pago total en PAGO MOVIL
             */
            if($this->descripcion == 'Pago movil' || $this->descripcion == 'Punto de venta' || $this->descripcion == 'Transferencia')
            {
                if($this->referencia != '')
                {
                    try {

                        $facturar = DB::table('venta_servicios')->where('cod_asignacion', $item->cod_asignacion)
                            ->update([
                                'metodo_pago' => $this->descripcion,
                                'referencia' => $this->referencia,
                                'total_USD' => $total_vista,
                                'pago_bsd' => $total_vista * $tasa_bcv,
                                'propina_usd'   => $this->propina_usd != '' ? $this->propina_usd : 0.00,
                                'propina_bsd'   => $this->propina_bsd != '' ? $this->propina_bsd : 0.00,
                                'comision_bolivares' => UtilsController::cal_comision_empleado($total_vista * $tasa_bcv),
                                'responsable'   => Auth::user()->name,
                            ]);

                        DetalleAsignacion::where('cod_asignacion', $item->cod_asignacion)->where('status', '1')
                            ->update([
                                'status' => '2',
                            ]);

                        Disponible::where('cod_asignacion', $item->cod_asignacion)->where('status', 'por facturar')
                            ->update([
                                'status' => 'facturado'
                            ]);

                        Notification::make()
                            ->title('La factura fue cerrada con exito')
                            ->success()
                            ->send();

                        $this->redirect('/cabinas');

                    } catch (\Throwable $th) {
                        //throw $th;
                    }

                }else{
                    $this->dialog()->error(
                        $title = 'Error !!!',
                        $description = 'Debe cargar el número de referencia, de lo contrario no podra realizar la facturación'
                    );
                }
            }

            /**
             * Pago total en ZELLE
             */
            if($this->descripcion == 'Zelle')
            {
                if($this->referencia == ''){
                    $this->dialog()->error(
                        $title = 'Error !!!',
                        $description = 'Debe cargar el número de referencia, de lo contrario no podra realizar la facturación'
                    );

                }else{

                    try {

                        $facturar = DB::table('venta_servicios')->where('cod_asignacion', $item->cod_asignacion)
                            ->update([
                                'metodo_pago' => $this->descripcion,
                                'referencia' => $this->referencia,
                                'total_USD' => $total_vista,
                                'pago_usd' => $total_vista,
                                'propina_usd'   => $this->propina_usd != '' ? $this->propina_usd : 0.00,
                                'propina_bsd'   => $this->propina_bsd != '' ? $this->propina_bsd : 0.00,
                                'comision_dolares' => UtilsController::cal_comision_empleado($total_vista),
                                'responsable'   => Auth::user()->name,
                            ]);

                        DetalleAsignacion::where('cod_asignacion', $item->cod_asignacion)->where('status', '1')
                            ->update([
                                'status' => '2',
                            ]);

                        Disponible::where('cod_asignacion', $item->cod_asignacion)->where('status', 'por facturar')
                            ->update([
                                'status' => 'facturado'
                            ]);

                        Notification::make()
                            ->title('La factura fue cerrada con exito')
                            ->success()
                            ->send();

                        $this->redirect('/cabinas');
                        //code...
                    } catch (\Throwable $th) {
                        //throw $th;
                    }

                }
            }

            /**
             * Pago total en ZELLE
             */
            if($this->descripcion == 'Multiple')
            {

                /**
                 * CASO 1 DOLARES -> BOLIVARES
                 */
                if ($this->op1 == 'Efectivo Usd' || $this->op1 == 'Zelle')
                {
                    if ($this->op2 == 'Efectivo Bsd' || $this->op2 == 'Pago movil' || $this->op2 == 'Transferencia' || $this->op2 == 'Punto de venta')
                    {
                        if($this->valor_uno == '' and $this->valor_dos == '')
                        {
                            $this->dialog()->error(
                                $title = 'Error !!!',
                                $description = 'Debe llenar el monto en dolares para realizar el calculo.'
                            );

                        }elseif($this->valor_uno == 0)
                        {

                            $this->dialog()->error(
                                $title = 'Error !!!',
                                $description = 'Los monto deben ser mayores a cero.'
                            );

                        }else{

                            // dd($this->referencia);
                            // $this->referencia = 'pago multiple';

                                try {

                                    $facturar = DB::table('venta_servicios')->where('cod_asignacion', $item->cod_asignacion)
                                        ->update([
                                            'metodo_pago' => $this->descripcion,
                                            'referencia' => $this->referencia,
                                            'total_USD' => $total_vista,
                                            'pago_usd' => floatval($this->valor_uno),
                                            'pago_bsd' => Str::replace(',', '.', (Str::replace('.', '', $this->valor_dos))),
                                            'propina_usd'   => $this->propina_usd != '' ? $this->propina_usd : 0.00,
                                            'propina_bsd'   => $this->propina_bsd != '' ? $this->propina_bsd : 0.00,
                                            'comision_dolares'   => UtilsController::cal_comision_empleado(floatval($this->valor_uno)),
                                            'comision_bolivares' => UtilsController::cal_comision_empleado(Str::replace(',', '.', (Str::replace('.', '', $this->valor_dos)))),
                                            'responsable'   => Auth::user()->name,
                                        ]);

                                    DetalleAsignacion::where('cod_asignacion', $item->cod_asignacion)->where('status', '1')
                                        ->update([
                                            'status' => '2',
                                        ]);

                                    Disponible::where('cod_asignacion', $item->cod_asignacion)->where('status', 'por facturar')
                                        ->update([
                                            'status' => 'facturado'
                                        ]);

                                    Notification::make()
                                        ->title('La factura fue cerrada con exito')
                                        ->success()
                                        ->send();

                                    $this->redirect('/cabinas');

                                } catch (\Throwable $th) {
                                    //throw $th;
                                }

                        }

                    }
                }

                /**
                 * CASO 2 DOLARES -> DOLARES
                 */
                // if ($this->op1 == 'Efectivo Usd')
                // {
                //     if ($this->op2 == 'Zelle')
                //     {
                //         if($this->valor_uno == '' and $this->valor_dos == '')
                //         {
                //             $this->dialog()->error(
                //                 $title = 'Error !!!',
                //                 $description = 'Los monto deben ser myor a 0.'
                //             );
                //         }else{

                //             // $this->referencia = 'pago multiple';

                //             try {

                //                 $facturar = DB::table('venta_servicios')->where('cod_asignacion', $item->cod_asignacion)
                //                     ->update([
                //                         'metodo_pago' => $this->descripcion,
                //                         'referencia' => $this->referencia,
                //                         'total_USD' => $total_vista,
                //                         'pago_usd' => $total_vista,
                //                         'propina_usd'   => $this->propina_usd != '' ? $this->propina_usd : 0.00,
                //                         'propina_bsd'   => $this->propina_bsd != '' ? $this->propina_bsd : 0.00,
                //                         'comision_dolares' => UtilsController::cal_comision_empleado($total_vista),
                //                         'responsable'   => Auth::user()->name,
                //                     ]);

                //                 DetalleAsignacion::where('cod_asignacion', $item->cod_asignacion)->where('status', '1')
                //                     ->update([
                //                         'status' => '2',
                //                     ]);

                //                 Disponible::where('cod_asignacion', $item->cod_asignacion)->where('status', 'por facturar')
                //                     ->update([
                //                         'status' => 'facturado'
                //                     ]);

                //                 Notification::make()
                //                     ->title('La factura fue cerrada con exito')
                //                     ->success()
                //                     ->send();

                //                 $this->redirect('/cabinas');

                //             } catch (\Throwable $th) {
                //                 //throw $th;
                //             }

                //         }

                //     }
                // }

            }

        }

    }

    public function cliente_especial()
    {
        $this->dialog()->confirm([
            'title'       => 'Es un cliente especial?',
            'description' => 'Esta modalida de cobro no genera un registro de caja, pero si realiza el calculo de comisiones respectivamente.',
            'icon'        => 'question',
            'accept'      => [
                'label'  => 'Si, es cliente especial',
                'method' => 'ejecuta_ce',
                'params' => 'Saved',
            ],
            'reject' => [
                'label'  => 'No, cancelar',
                'method' => 'cancelar',
            ],
        ]);

    }

    /** Ejecutar el cliente especial */
    public function ejecuta_ce(Request $request)
    {
        /**
         * El codigo es tomado de la variables de sesion
         * del usuario
         *
         * @param $codigo
         */
        $codigo = $request->session()->all();

        $item = VentaServicio::where('cod_asignacion', $codigo['cod_asignacion'])->first();
        Debugbar::info($item);

        $total = DB::table('detalle_asignacions')
        ->select(DB::raw('SUM(costo) as total'))
        ->where('cod_asignacion', $item->cod_asignacion)
            ->where('status', '1')
            ->first();

        $total_vista = $total->total;

        $tasa_bcv = TasaBcv::where('id', 1)->first()->tasa;

        $total_vista_bsd = $total_vista * $tasa_bcv;

        try {

            $facturar = DB::table('venta_servicios')->where('cod_asignacion', $item->cod_asignacion)
                ->update([
                    'metodo_pago' => 'cliente especial',
                    'total_USD' => $total_vista,
                    'propina_usd'   => $this->propina_usd != '' ? $this->propina_usd : 0.00,
                    'propina_bsd'   => $this->propina_bsd != '' ? $this->propina_bsd : 0.00,
                    'responsable'   => Auth::user()->name,
                ]);

            DetalleAsignacion::where('cod_asignacion', $item->cod_asignacion)->where('status', '1')
                ->update([
                    'status' => '2',
                ]);

            Disponible::where('cod_asignacion', $item->cod_asignacion)->where('status', 'por facturar')
            ->update([
                'status' => 'facturado'
            ]);

            Notification::make()
                ->title('La factura fue cerrada con exito')
                ->success()
                ->send();

            $this->redirect('/cabinas');
        } catch (\Throwable $th) {
            //throw $th;
        }

    }

    public function cancelar()
    {
        $this->reset();
    }

    public function render(Request $request)
    {
        /**
         * El codigo es tomado de la variables de sesion
         * del usuario
         *
         * @param $codigo
         */
        $codigo = $request->session()->all();

        $this->event();

        $data = VentaServicio::where('cod_asignacion', $codigo['cod_asignacion'])->first();

        $detalle = DetalleAsignacion::where('cod_asignacion', $data->cod_asignacion)
        ->where('status', '1')
        ->get();

        $total = DB::table('detalle_asignacions')
            ->select(DB::raw('SUM(costo) as total'))
            ->where('cod_asignacion', $data->cod_asignacion)
            ->where('status', '1')
            ->first();

        $total_vista = $total->total;

        $tasa_bcv = TasaBcv::where('id', 1)->first()->tasa;
        $total_vista_bsd = $total_vista * $tasa_bcv;

        return view('livewire.caja', compact('data', 'detalle', 'total_vista', 'total_vista_bsd'));
    }
}
