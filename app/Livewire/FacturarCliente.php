<?php

namespace App\Livewire;

use App\Models\DetalleAsignacion;
use App\Models\Disponible;
use App\Models\FacturaMultiple;
use App\Models\Promocion;
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
use Illuminate\Support\Arr;

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

    public $atr_btn_promo = 'hidden';
    public $atr_grip_promo = 'grid-cols-1';

    public $promocion;

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

        $count = count($this->servicios);
        if($count = 2)
        {
            if(isset($this->servicios[0]))
            {
                $tipo_promocion = Disponible::where('id', $this->servicios[0])->first()->tipo_promocion;
                    if($tipo_promocion == '2x1')
                    {
                        $this->atr_btn_promo = '';
                        $this->atr_grip_promo = 'grid-cols-2';
                    }
            }

        }

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

    public function aplicar_promocion()
    {
        /** 1. pregunto cuantos servicos estan para facturar */

        $count = count($this->servicios);
        if($count = 2)
        {
            $es_dos_x_uno = Disponible::where('id', $this->servicios[0])->first()->tipo_promocion;

            if($es_dos_x_uno == '2x1')
            {
                $this->total_vista = $this->total_vista / 2;
                $this->total_vista_bsd = $this->total_vista_bsd / 2;
                $this->dialog()->success(
                    $title = 'Promocion aplicada !!!',
                    $description = 'El descuento fue aplicado de forma exitosa'
                );
                $this->atr_btn_promo = 'hidden';
                $this->atr_grip_promo = 'grid-cols-1';
            }

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

                    $factura = new FacturaMultiple();
                    $factura->cod_asignacion    = 'FM-' . random_int(11111111, 99999999);
                    $factura->responsable       = $user->name;
                    $factura->metodo_pago       = $this->descripcion;
                    $factura->referencia        = $factura->cod_asignacion;
                    $factura->fecha_venta       = date('d-m-Y');
                    $factura->pago_usd          = $this->total_vista;
                    $factura->total_usd         = $this->total_vista;
                    $factura->save();

                    for ($i = 0; $i < count($this->servicios); $i++) {
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

                    $this->redirect('/cabinas');
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

                    $factura = new FacturaMultiple();
                    $factura->cod_asignacion    = 'FM-' . random_int(11111111, 99999999);
                    $factura->responsable       = $user->name;
                    $factura->metodo_pago       = $this->descripcion;
                    $factura->referencia        = $this->referencia;
                    $factura->fecha_venta       = date('d-m-Y');
                    $factura->pago_usd          = $this->total_vista;
                    $factura->total_usd         = $this->total_vista;
                    $factura->save();

                    for ($i = 0; $i < count($this->servicios); $i++) {
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

                    $this->redirect('/cabinas');
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
                    $factura = new FacturaMultiple();
                    $factura->cod_asignacion    = 'FM-' . random_int(11111111, 99999999);
                    $factura->responsable       = $user->name;
                    $factura->metodo_pago       = $this->descripcion;
                    $factura->referencia        = $factura->cod_asignacion;
                    $factura->fecha_venta       = date('d-m-Y');
                    $factura->pago_bsd          = $this->total_vista_bsd;
                    $factura->total_usd         = $this->total_vista;
                    $factura->save();

                    for ($i = 0; $i < count($this->servicios); $i++) {
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

                    $this->redirect('/cabinas');
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
                    $factura = new FacturaMultiple();
                    $factura->cod_asignacion    = 'FM-' . random_int(11111111, 99999999);
                    $factura->responsable       = $user->name;
                    $factura->metodo_pago       = $this->descripcion;
                    $factura->referencia        = $this->referencia;
                    $factura->fecha_venta       = date('d-m-Y');
                    $factura->pago_bsd          = $this->total_vista_bsd;
                    $factura->total_usd         = $this->total_vista;
                    $factura->save();

                    for ($i = 0; $i < count($this->servicios); $i++) {
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

                    $this->redirect('/cabinas');
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
                                $this->dialog()->error(
                                    $title = 'Error !!!',
                                    $description = 'El monto debe coincidir con el valor de factura.'
                                );
                            } elseif ($this->valor_uno == 0) {
                                $this->dialog()->error(
                                    $title = 'Error !!!',
                                    $description = 'Los monto en dolares deben ser mayores a cero.'
                                );
                            } elseif ($this->op1 == $this->op2) {
                                $this->dialog()->error(
                                    $title = 'Error !!!',
                                    $description = 'Los metodos de pago no pueden ser iguales'
                                );
                            } else {
                                $factura = new FacturaMultiple();
                                $factura->cod_asignacion    = 'FM-' . random_int(11111111, 99999999);
                                $factura->responsable       = $user->name;
                                $factura->metodo_pago       = 'Multiple';
                                $factura->referencia        = $factura->cod_asignacion;
                                $factura->fecha_venta       = date('d-m-Y');
                                $factura->pago_bsd          = str_replace(',', '.', str_replace('.', '', $this->valor_dos));
                                $factura->pago_usd          = str_replace(',', '.', $this->valor_uno);
                                $factura->total_usd         = $this->total_vista;
                                $factura->save();

                                for ($i = 0; $i < count($this->servicios); $i++) {
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

                                $this->redirect('/cabinas');
                            }
                        }
                    }

                    /**
                     * CASO 2
                     */
                    if ($this->op1 == 'Efectivo Bsd' || $this->op1 == 'Pago movil' || $this->op1 == 'Transferencia' || $this->op1 == 'Punto de venta') {
                        if ($this->op2 == 'Efectivo Bsd' || $this->op2 == 'Pago movil' || $this->op2 == 'Transferencia' || $this->op2 == 'Punto de venta') {
                            if ($this->valor_uno == '' and $this->valor_dos == '') {
                                $this->dialog()->error(
                                    $title = 'Error !!!',
                                    $description = 'Los monto deben ser myor a 0.'
                                );
                            } else {
                                $factura = new FacturaMultiple();
                                $factura->cod_asignacion    = 'FM-' . random_int(11111111, 99999999);
                                $factura->responsable       = $user->name;
                                $factura->metodo_pago       = 'Facturación multiple';
                                $factura->referencia        = $factura->cod_asignacion;
                                $factura->fecha_venta       = date('d-m-Y');
                                $factura->pago_bsd          = $this->total_vista_bsd;
                                $factura->total_usd         = $this->total_vista;
                                $factura->save();

                                for ($i = 0; $i < count($this->servicios); $i++) {
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
                        }
                    }

                    /**
                     * CASO 3
                     */
                    if ($this->op1 == 'Efectivo Usd') {
                        if ($this->op2 == 'Zelle') {
                            if ($this->valor_uno == '' and $this->valor_dos == '') {
                                $this->dialog()->error(
                                    $title = 'Error !!!',
                                    $description = 'Los monto deben ser myor a 0.'
                                );
                            } else {
                                $factura = new FacturaMultiple();
                                $factura->cod_asignacion    = 'FM-' . random_int(11111111, 99999999);
                                $factura->responsable       = $user->name;
                                $factura->metodo_pago       = 'Facturación multiple';
                                $factura->referencia        = $factura->cod_asignacion;
                                $factura->fecha_venta       = date('d-m-Y');
                                $factura->pago_bsd          = $this->total_vista_bsd;
                                $factura->total_usd         = $this->total_vista;
                                $factura->save();

                                for ($i = 0; $i < count($this->servicios); $i++) {
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
                ->paginate(4),
            'promociones' => Promocion::paginate(4)
       ]);

    }
}

