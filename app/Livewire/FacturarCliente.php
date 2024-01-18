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

    public $valor_uno_db;
    public $valor_dos_db;

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
        if($this->descripcion == '')
        {
            $this->dialog()->error(
                $title = 'Error !!!',
                $description = 'Debe seleccionar un método de pago de lo contrario no podra facturar el servicio'
            );

        }else{

            $user = Auth::user();

            /**
             * Pago total en DOLARES
             */
            if ($this->descripcion == 'Efectivo Usd') {

                if (count($this->servicios) <= 0) {

                    $this->dialog()->error(
                        $title = 'Error !!!',
                        $description = 'Debe seleccionar al menos un servicio para poder realizar la facturación'
                    );

                } else {

                    try {

                        $factura = new FacturaMultiple();
                        $factura->cod_asignacion    = 'FM-' . random_int(11111111, 99999999);
                        $factura->responsable       = $user->name;
                        $factura->metodo_pago       = $this->descripcion;
                        $factura->referencia        = $factura->cod_asignacion;
                        $factura->fecha_venta       = date('d-m-Y');
                        $factura->pago_usd          = $this->total_vista;
                        $factura->total_usd         = $this->total_vista;
                        $factura->responsable       = $user->name;
                        $factura->save();

                        /** Calculo del 40% del total de la vista
                         * Este valor sera el asignado a los empleados
                         * segun el porcentace de representacion.
                         */
                        $_40porciento = ($this->total_vista * 40) / 100;

                        for ($i = 0; $i < count($this->servicios); $i++) {
                            Disponible::where('id', $this->servicios[$i])->update([
                                'status' => 'facturado'
                            ]);

                            $cod_asignacion = Disponible::where('id', $this->servicios[$i])->first()->cod_asignacion;

                            DetalleAsignacion::where('cod_asignacion', $cod_asignacion)->update([
                                'status' => 2
                            ]);

                            /**
                             * Calculo de la comision por empleado
                             * y el resultado se actualiza en la tabla de ventas
                             */
                            $costo_servicio = Disponible::where('id', $this->servicios[$i])->first()->costo;
                            $porcen_venta = ($costo_servicio * 100) / $this->total_vista;
                            $comision_empleado = ($porcen_venta * $_40porciento) / 100;

                            /**
                             * Calculo del costo del servicio
                             */
                            $porcentaje_servicio = ($porcen_venta * $factura->pago_usd) / 100;

                            VentaServicio::where('cod_asignacion', $cod_asignacion)->update([
                                'metodo_pago'       => 'Facturación multiple',
                                'referencia'        => $factura->cod_asignacion,
                                'pago_usd'          => $porcentaje_servicio,
                                'comision_dolares'  => $comision_empleado,
                                'responsable'       => $user->name
                            ]);
                        }

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

            /**
             * Pago total en DOLARES con referenia
             */
            if ($this->descripcion == 'Zelle') {
                if ($this->referencia == '') {

                    $this->dialog()->error(
                        $title = 'Error !!!',
                        $description = 'Debe cargar el número de referencia de lo contrario no podra realizar la facturación'
                    );

                } elseif (count($this->servicios) <= 0) {

                    $this->dialog()->error(
                        $title = 'Error !!!',
                        $description = 'Debe seleccionar al menos un servicio para poder realizar la facturación'
                    );

                } else {

                    try {

                        $factura = new FacturaMultiple();
                        $factura->cod_asignacion = 'FM-' . random_int(11111111, 99999999);
                        $factura->responsable    = $user->name;
                        $factura->metodo_pago    = $this->descripcion;
                        $factura->referencia     = $this->referencia;
                        $factura->fecha_venta    = date('d-m-Y');
                        $factura->pago_usd       = $this->total_vista;
                        $factura->total_usd      = $this->total_vista;
                        $factura->responsable    = $user->name;
                        $factura->save();

                        /** Calculo del 40% del total de la vista
                         * Este valor sera el asignado a los empleados
                         * segun el porcentace de representacion.
                         */
                        $_40porciento = ($this->total_vista * 40) / 100;

                        for ($i = 0; $i < count($this->servicios); $i++) {
                            Disponible::where('id', $this->servicios[$i])->update([
                                'status' => 'facturado'
                            ]);

                            $cod_asignacion = Disponible::where('id', $this->servicios[$i])->first()->cod_asignacion;

                            DetalleAsignacion::where('cod_asignacion', $cod_asignacion)->update([
                                'status' => 2
                            ]);

                            /**
                             * Calculo de la comision por empleado
                             * y el resultado se actualiza en la tabla de ventas
                             */
                            $costo_servicio = Disponible::where('id', $this->servicios[$i])->first()->costo;
                            $porcen_venta = ($costo_servicio * 100) / $this->total_vista;
                            $comision_empleado = ($porcen_venta * $_40porciento) / 100;

                            /**
                             * Calculo del costo del servicio
                             */
                            $porcentaje_servicio = ($porcen_venta * $factura->pago_usd) / 100;

                            VentaServicio::where('cod_asignacion', $cod_asignacion)->update([
                                'metodo_pago'       => 'Facturación multiple',
                                'referencia'        => $factura->cod_asignacion,
                                'pago_usd'          => $porcentaje_servicio,
                                'comision_dolares'  => $comision_empleado,
                                'responsable'       => $user->name
                            ]);
                        }

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

            /**
             * Pago total en BOLIVARES
             */
            if ($this->descripcion == 'Efectivo Bsd') {
                if (count($this->servicios) <= 0) {

                    $this->dialog()->error(
                        $title = 'Error !!!',
                        $description = 'Debe seleccionar al menos un servicio para poder realizar la facturación'
                    );

                } else {

                    try {

                        $factura = new FacturaMultiple();
                        $factura->cod_asignacion    = 'FM-' . random_int(11111111, 99999999);
                        $factura->responsable       = $user->name;
                        $factura->metodo_pago       = $this->descripcion;
                        $factura->referencia        = $factura->cod_asignacion;
                        $factura->fecha_venta       = date('d-m-Y');
                        $factura->pago_bsd          = $this->total_vista_bsd;
                        $factura->total_usd         = $this->total_vista;
                        $factura->responsable       = $user->name;
                        $factura->save();

                        /** Calculo del 40% del total de la vista
                         * Este valor sera el asignado a los empleados
                         * segun el porcentace de representacion.
                         */
                        $_40porciento = ($this->total_vista_bsd * 40) / 100;

                        for ($i = 0; $i < count($this->servicios); $i++) {
                            Disponible::where('id', $this->servicios[$i])->update([
                                'status' => 'facturado'
                            ]);

                            $cod_asignacion = Disponible::where('id', $this->servicios[$i])->first()->cod_asignacion;

                            DetalleAsignacion::where('cod_asignacion', $cod_asignacion)->update([
                                'status' => 2
                            ]);

                            /**
                             * Calculo de la comision por empleado
                             * y el resultado se actualiza en la tabla de ventas
                             */
                            $costo_servicio = Disponible::where('id', $this->servicios[$i])->first()->costo;
                            $porcen_venta = ($costo_servicio * 100) / $this->total_vista;
                            $comision_empleado = ($porcen_venta * $_40porciento) / 100;

                            /**
                             * Calculo del costo del servicio
                             */
                            $porcentaje_servicio = ($porcen_venta * $factura->pago_bsd) / 100;

                            VentaServicio::where('cod_asignacion', $cod_asignacion)->update([
                                'metodo_pago'       => 'Facturación multiple',
                                'referencia'        => $factura->cod_asignacion,
                                'pago_bsd'          => $porcentaje_servicio,
                                'comision_bolivares'  => $comision_empleado,
                                'responsable'       => $user->name
                            ]);
                        }

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

            /**
             * Pago total en BOLIVARES con referenia
             */
            if ($this->descripcion == 'Transferencia' || $this->descripcion == 'Pago movil' || $this->descripcion == 'Punto de venta') {
                if ($this->referencia == '') {

                    $this->dialog()->error(
                        $title = 'Error !!!',
                        $description = 'Debe cargar el número de referencia de lo contrario no podra realizar la facturación'
                    );

                } elseif (count($this->servicios) <= 0) {

                    $this->dialog()->error(
                        $title = 'Error !!!',
                        $description = 'Debe seleccionar al menos un servicio para poder realizar la facturación'
                    );

                } else {

                    try {

                        $factura = new FacturaMultiple();
                        $factura->cod_asignacion    = 'FM-' . random_int(11111111, 99999999);
                        $factura->responsable       = $user->name;
                        $factura->metodo_pago       = $this->descripcion;
                        $factura->referencia        = $this->referencia;
                        $factura->fecha_venta       = date('d-m-Y');
                        $factura->pago_bsd          = $this->total_vista_bsd;
                        $factura->total_usd         = $this->total_vista;
                        $factura->responsable       = $user->name;
                        $factura->save();

                        /** Calculo del 40% del total de la vista
                         * Este valor sera el asignado a los empleados
                         * segun el porcentace de representacion.
                         */
                        $_40porciento = ($this->total_vista_bsd * 40) / 100;

                        for ($i = 0; $i < count($this->servicios); $i++) {
                            Disponible::where('id', $this->servicios[$i])->update([
                                'status' => 'facturado'
                            ]);

                            $cod_asignacion = Disponible::where('id', $this->servicios[$i])->first()->cod_asignacion;

                            DetalleAsignacion::where('cod_asignacion', $cod_asignacion)->update([
                                'status' => 2
                            ]);

                            /**
                             * Calculo de la comision por empleado
                             * y el resultado se actualiza en la tabla de ventas
                             */
                            $costo_servicio = Disponible::where('id', $this->servicios[$i])->first()->costo;
                            $porcen_venta = ($costo_servicio * 100) / $this->total_vista;
                            $comision_empleado = ($porcen_venta * $_40porciento) / 100;

                            /**
                             * Calculo del costo del servicio
                             */
                            $porcentaje_servicio = ($porcen_venta * $factura->pago_bsd) / 100;

                            VentaServicio::where('cod_asignacion', $cod_asignacion)->update([
                                'metodo_pago'           => 'Facturación multiple',
                                'referencia'            => $factura->cod_asignacion,
                                'pago_bsd'              => $porcentaje_servicio,
                                'comision_bolivares'    => $comision_empleado,
                                'responsable'           => $user->name
                            ]);
                        }

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

            /**
             * Pago total en MULTIPLE
             */
            if ($this->descripcion == 'Multiple') {
                if (count($this->servicios) <= 0) {

                    $this->dialog()->error(
                        $title = 'Error !!!',
                        $description = 'Debe seleccionar al menos un servicio de lo contrario no podra facturar'
                    );

                } else {
                    /**
                     * CASO 1
                     */
                    if ($this->op1 == 'Efectivo Usd' || $this->op1 == 'Zelle') {
                        if ($this->op2 == 'Efectivo Bsd' || $this->op2 == 'Pago movil' || $this->op2 == 'Transferencia' || $this->op2 == 'Punto de venta') {
                            if ($this->valor_uno == '' and $this->valor_dos == '') {
                                /** Notificacion al usuario */
                                $this->dialog()->error(
                                    $title = 'Error !!!',
                                    $description = 'El monto debe coincidir con el valor de factura.'
                                );

                            } elseif ($this->valor_uno == 0) {
                                /** Notificacion al usuario */
                                $this->dialog()->error(
                                    $title = 'Error !!!',
                                    $description = 'Los monto en dolares deben ser mayores a cero.'
                                );

                            } elseif ($this->op1 == $this->op2) {
                                /** Notificacion al usuario */
                                $this->dialog()->error(
                                    $title = 'Error !!!',
                                    $description = 'Los metodos de pago no pueden ser iguales'
                                );

                            } else {

                                try {

                                    $factura = new FacturaMultiple();
                                    $factura->cod_asignacion    = 'FM-' . random_int(11111111, 99999999);
                                    $factura->responsable       = $user->name;
                                    $factura->metodo_pago       = $this->descripcion;
                                    $factura->referencia        = $factura->cod_asignacion;
                                    $factura->fecha_venta       = date('d-m-Y');
                                    $factura->pago_bsd          = str_replace(',', '.', str_replace('.', '', $this->valor_dos));
                                    $factura->pago_usd          = str_replace(',', '.', $this->valor_uno);
                                    $factura->total_usd         = $this->total_vista;
                                    $factura->responsable       = $user->name;
                                    $factura->save();

                                    /** Calculo del 40% del total de la vista
                                     * Este valor sera el asignado a los empleados
                                     * segun el porcentace de representacion.
                                     */
                                    $_40porciento = ($this->total_vista * 40) / 100;

                                    for ($i = 0; $i < count($this->servicios); $i++) {
                                        Disponible::where('id', $this->servicios[$i])->update([
                                            'status' => 'facturado'
                                        ]);

                                        $cod_asignacion = Disponible::where('id', $this->servicios[$i])->first()->cod_asignacion;

                                        DetalleAsignacion::where('cod_asignacion', $cod_asignacion)->update([
                                            'status' => 2
                                        ]);

                                        /**
                                         * Calculo de la comision por empleado
                                         * y el resultado se actualiza en la tabla de ventas
                                         */
                                        $costo_servicio = Disponible::where('id', $this->servicios[$i])->first()->costo;
                                        $porcen_venta = ($costo_servicio * 100) / $this->total_vista;

                                        /** Comison en dolares */
                                        $comision_empleado_usd = ($porcen_venta * $factura->pago_usd) / 100;
                                        $total_comision_empleado_usd = ($comision_empleado_usd * 40) / 100;

                                        /** Comison en bolivares */
                                        $comision_empleado_bsd = ($porcen_venta * $factura->pago_bsd) / 100;
                                        $total_comision_empleado_bsd = ($comision_empleado_bsd * 40) / 100;


                                        /**
                                         * Calculo del costo del servicio en bolivares
                                         */
                                        $porcentaje_servicio_bsd = ($porcen_venta * $factura->pago_bsd) / 100;

                                        /**
                                         * Calculo del costo del servicio en dolares
                                         */
                                        $porcentaje_servicio_usd = ($porcen_venta * $factura->pago_usd) / 100;

                                        VentaServicio::where('cod_asignacion', $cod_asignacion)->update([
                                            'metodo_pago'        => 'Facturación multiple',
                                            'referencia'         => $factura->cod_asignacion,
                                            'pago_usd'           => $porcentaje_servicio_usd,
                                            'pago_bsd'           => $porcentaje_servicio_bsd,
                                            'comision_dolares'   => $total_comision_empleado_usd,
                                            'comision_bolivares' => $total_comision_empleado_bsd,
                                            'responsable'        => $user->name
                                        ]);
                                    }

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
