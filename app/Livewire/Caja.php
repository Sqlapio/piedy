<?php

namespace App\Livewire;

use App\Http\Controllers\UtilsController;
use App\Http\Controllers\NotificacionesController;
use App\Models\Comision;
use App\Models\DetalleAsignacion;
use App\Models\Disponible;
use App\Models\GiftCard;
use App\Models\Membresia;
use App\Models\MovimientoGiftCard;
use App\Models\Servicio;
use App\Models\TasaBcv;
use App\Models\User;
use App\Models\VentaProducto;
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
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Exception;

class Caja extends Component
{

    use Actions;

    public $op1;
    public $op2;
    public $valor_uno;
    public $valor_dos;
    public $monto_giftcard;

    public $propina_usd;
    public $propina_bsd;

    public $referencia;

    public $ref_usd;
    public $ref_bsd;
    public $ref_propina;

    public $prueba;

    public $descripcion = 'Multiple';
    public $op1_hidden = 'hidden';
    public $op2_hidden = 'hidden';
    public $ref_hidden = 'hidden';
    public $atr_giftCard = 'hidden';
    public $codigo;
    public $metodo_pago_pre;

    public $option = false;

    /**Atributos y propiedades para manejo de giftCard */
    public $monto_mem;
    public $codigo_mem;
    public $atr_mem = 'hidden';

    public $img_dr_encasa = 'dr-encasa-gris.png';
    public $pixeles = '90px';
    public $seguro;

    protected $messages = [
        'dolares'     => 'Campo requerido',
    ];

    protected $listeners = [
        'calculo',
    ];

    public function event()
    {
        if ($this->descripcion == 'Pago movil' || $this->descripcion == 'Transferencia' || $this->descripcion == 'Zelle' || $this->descripcion == 'Punto de venta') {
            $this->ref_hidden = '';
            $this->op1_hidden = 'hidden';
            $this->op2_hidden = 'hidden';
        }

        if ($this->descripcion == 'Multiple') {
            $this->op1_hidden = '';
            $this->op2_hidden = '';
            $this->ref_hidden = '';
        }

        if ($this->descripcion == 'Efectivo Usd' || $this->descripcion == 'Efectivo Bsd') {
            $this->ref_hidden = 'hidden';
            $this->op1_hidden = 'hidden';
            $this->op2_hidden = 'hidden';
        }

        if ($this->metodo_pago_pre == 'Giftcard' || $this->metodo_pago_pre == 'Seguro - TuDr.EnCasa') {
            $this->atr_giftCard = '';
        }

        if ($this->metodo_pago_pre == 'Membresia') {
            $this->atr_mem = '';
        }

        if ($this->descripcion == '') {
            $this->reset();
        }
    }

    public function calculo(Request $request)
    {
        $codigo = $request->session()->all();

        $item = VentaServicio::where('cod_asignacion', $codigo['cod_asignacion'])->first();

        $total_servicios = DB::table('detalle_asignacions')
            ->select(DB::raw('SUM(costo) as total'))
            ->where('cod_asignacion', $codigo['cod_asignacion'])
            ->where('status', '1')
            ->first()
            ->total;

        /**Calculo el total de la vista */
        $total_productos = DB::table('venta_productos')
            ->select(DB::raw('SUM(total_venta) as total'))
            ->where('cod_asignacion', $codigo['cod_asignacion'])
            ->where('status', '1')
            ->first()->total;

        $tasa_bcv = TasaBcv::where('id', 1)->first()->tasa;

        if ($this->monto_giftcard != '') {
            $total_vista = $total_servicios - $this->monto_giftcard + $total_productos;
            $total_vista_bsd = $total_vista * $tasa_bcv;
        } elseif($this->metodo_pago_pre == 2) {
            $comision = Comision::where('aplicacion', 'seguro')->first()->porcentaje;
            $total_vista = $total_servicios - (($total_servicios * 10) / 100) + $total_productos;
            $total_vista_bsd = $total_vista * $tasa_bcv;
        }else{
            $total_vista = $total_servicios + $total_productos;
            $total_vista_bsd = $total_vista * $tasa_bcv;
        }

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

    public function valida_giftcard(Request $request)
    {
        $codigo = $request->session()->all();

        $item = VentaServicio::where('cod_asignacion', $codigo['cod_asignacion'])->first();

        $valida_cod = GiftCard::where('pgc', $this->codigo)->first();

        if (isset($valida_cod)) {

            if ($valida_cod->status == '1' && $valida_cod->cliente_id == $item->cliente_id) {
                session()->flash('activa', 'TARJETA GIFTCARD ACTIVA!');
                $this->monto_giftcard = $valida_cod->monto;
            }

            if ($valida_cod->status == '1' && $valida_cod->cliente_id != $item->cliente_id) {
                session()->flash('error', 'TARJETA GIFTCARD ACTIVA!, PERO NO PERTENECE AL CLIENTE');
                $this->reset(['codigo', 'monto_giftcard']);

            }

            if ($valida_cod->status == '2') {
                session()->flash('error', 'TARJETA GIFTCARD INACTIVA. FECHA DE USO: '.$valida_cod->updated_at.'');
            }

        }else {
            session()->flash('error', 'CODIGO NO EXISTE');
            $this->reset(['codigo', 'monto_giftcard']);
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
            $codigo = $request->session()->all();

            $item = VentaServicio::where('cod_asignacion', $codigo['cod_asignacion'])->first();
            Debugbar::info($item);

            $total_servicios = DB::table('detalle_asignacions')
            ->select(DB::raw('SUM(costo) as total'))
            ->where('cod_asignacion', $codigo['cod_asignacion'])
            ->where('status', '1')
            ->first()
            ->total;

            /**Calculo el total de la vista */
            $total_productos = DB::table('venta_productos')
                ->select(DB::raw('SUM(total_venta) as total'))
                ->where('cod_asignacion', $codigo['cod_asignacion'])
                ->where('status', '1')
                ->where('facturado', 1)
                ->first()->total;

            $total_vista = $total_servicios + $total_productos;

            $tasa_bcv = TasaBcv::where('id', 1)->first()->tasa;

            $total_vista_bsd = $total_vista * $tasa_bcv;

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

                        /**Obtengo el codigo del servicio a facturar */
                        $srv_vip = DetalleAsignacion::where('cod_asignacion', $item->cod_asignacion)
                            ->where('status', '1')
                            ->first()
                            ->cod_servicio;

                        /**Pregunto? si la asignacion del servicio en VIP */
                        $tipoSrv = Servicio::where('cod_servicio', $srv_vip)->first();

                        /**CASO DE USO 1
                         * METODO DE PAGO: SOLO CON GIFTCARD
                         */
                        if ($this->monto_giftcard != '') {

                            /**Tomo la informacion de giftCard segun el codigo de 4 digitos asignado */
                            $valida_cod = GiftCard::where('pgc', $this->codigo)->first();

                            /**Valido que el monto de la giftcard no puede ser mayor al monto total a pagar*/
                            if ($this->monto_giftcard > $total_vista) {
                                throw new Exception("El monto de Giftcard es mayor al monto del servicio total. Favor intente con otra", 401);
                            } else {
                                /**Calculo el nuevo valor del servicio restando el valor de la giftcard */
                                $total_vista_actualizado = $total_vista - $this->monto_giftcard;

                                /**Si el total a pagar es igual a cero(0), implica que el cliente va a cancelar el total de la cuenta con la giftcard */
                                if($total_vista_actualizado == 0){
                                    /**Controlador para calcular las comisiones */
                                    $res = UtilsController::cal_comision_giftCard($this->monto_giftcard, $tipoSrv);

                                    /**
                                     * Actualizamos la tabla de ventas, Detalles de asignacion y Disponible
                                     */
                                    $facturar = DB::table('venta_servicios')->where('cod_asignacion', $item->cod_asignacion)
                                        ->update([
                                            'metodo_pago'           => ($this->op1 != '') ? $this->op1 : 'N/A',
                                            'metodo_pago_dos'       => ($this->op2 != '') ? $this->op2 : 'N/A',
                                            'metodo_pago_prepagado' => ($this->metodo_pago_pre != '') ? $this->metodo_pago_pre : 'N/A',
                                            'referencia'            => $valida_cod->referencia,
                                            'total_USD'             => $total_vista,
                                            'pago_usd'              => 0.00,
                                            'pago_bsd'              => ($this->valor_dos == '') ? 0.00 : Str::replace(',', '.', (Str::replace('.', '', $this->valor_dos))),
                                            'propina_usd'           => ($this->propina_usd != '') ? $this->propina_usd : 0.00,
                                            'propina_bsd'           => ($this->propina_bsd != '') ? $this->propina_bsd : 0.00,
                                            'referencia_propina'    => $this->ref_propina,
                                            'comision_dolares'      => $res['comision_usd_emp_valorUno'],
                                            'comision_bolivares'    => $res['comision_bs_emp_valorDos'],
                                            'comision_gerente'      => $res['comision_usd_gte'],
                                            'responsable_id'        => Auth::user()->id,
                                            'responsable'           => Auth::user()->name,
                                        ]);

                                    /**Valido el estatus de la giftcard se encuentre activa(1) y que pertenesca al cliente */
                                    if ($valida_cod->status == '1' && $valida_cod->cliente_id == $item->cliente_id) {
                                        $movimiento = new MovimientoGiftCard();
                                        $movimiento->gift_card_id = $valida_cod->id;
                                        $movimiento->codigo_seguridad = $valida_cod->codigo_seguridad;
                                        $movimiento->cliente_id = $item->cliente_id;
                                        $movimiento->monto_pagado = $this->monto_giftcard;
                                        $movimiento->fecha_debito = date('d-m-Y');
                                        $movimiento->responsable = Auth::user()->name;
                                        $movimiento->save();

                                        /**Actualizo el status de la giftCard a 2, que significa que ya fue utilizada */
                                        GiftCard::where('pgc', $this->codigo)
                                        ->update([
                                            /**Giftcard usada */
                                            'status' => '2'
                                        ]);

                                    } else {
                                        throw new Exception("La tarjeta GiftCard ya fue consumida en su totalidad, ó no pertenece al cliente. Favor intente con otra", 401);
                                    }

                                }else{
                                    /**Al entrean aqui, quiere decir que el cliente va a cancelar de forma parcial, una parte con la giftcard y el resto seleccionando uno
                                     * o ambos metodos de pago que serian en Dolares y/o Bolivares
                                     */

                                     /**Restriccion para que el cliente seleccione otro metodo de pago para completar la cuenta
                                      * ya que la giftcard debe usarce completa y NO DE FORMA PARCIAL
                                      */
                                    if($this->valor_uno == '' || $this->valor_dos == '')
                                    {
                                        throw new Exception("Debe seleccionar un metodo de pago en Dolares o en Bolivares", 401);
                                    }

                                    /**Controlador para calcular las comisiones */
                                    $res = UtilsController::cal_comision_empleado($this->valor_uno, $this->valor_dos, $tipoSrv, $total_vista, $this->monto_giftcard);

                                    /**
                                     * Actualizamos la tabla de ventas, Detalles de asignacion y Disponible
                                     */
                                    $facturar = DB::table('venta_servicios')->where('cod_asignacion', $item->cod_asignacion)
                                        ->update([
                                            'metodo_pago'           => ($this->op1 != '') ? $this->op1 : 'N/A',
                                            'metodo_pago_dos'       => ($this->op2 != '') ? $this->op2 : 'N/A',
                                            'metodo_pago_prepagado' => ($this->metodo_pago_pre != '') ? $this->metodo_pago_pre : 'N/A',
                                            'referencia'            => $this->referencia,
                                            'total_USD'             => $total_vista,
                                            'pago_usd'              => ($this->valor_uno == '') ? 0.00 : floatval($this->valor_uno),
                                            'pago_bsd'              => ($this->valor_dos == '') ? 0.00 : Str::replace(',', '.', (Str::replace('.', '', $this->valor_dos))),
                                            'propina_usd'           => ($this->propina_usd != '') ? $this->propina_usd : 0.00,
                                            'propina_bsd'           => ($this->propina_bsd != '') ? $this->propina_bsd : 0.00,
                                            'referencia_propina'    => $this->ref_propina,
                                            'comision_dolares'      => $res['comision_usd_emp_valorUno'],
                                            'comision_bolivares'    => $res['comision_bs_emp_valorDos'],
                                            'comision_gerente'      => $res['comision_usd_gte'],
                                            'responsable_id'        => Auth::user()->id,
                                            'responsable'           => Auth::user()->name,
                                        ]);

                                    /**Valido el estatus de la giftcard se encuentre activa(1) y que pertenesca al cliente */
                                    if ($valida_cod->status == '1' && $valida_cod->cliente_id == $item->cliente_id) {
                                        $movimiento = new MovimientoGiftCard();
                                        $movimiento->gift_card_id = $valida_cod->id;
                                        $movimiento->codigo_seguridad = $valida_cod->codigo_seguridad;
                                        $movimiento->cliente_id = $item->cliente_id;
                                        $movimiento->monto_pagado = $valida_cod->monto;
                                        $movimiento->fecha_debito = date('d-m-Y');
                                        $movimiento->responsable = Auth::user()->name;
                                        $movimiento->save();

                                        /**Actualizo el status de la giftCard a 2, que significa que ya fue utilizada */
                                        GiftCard::where('pgc', $this->codigo)
                                        ->update([
                                            /**giftcard usada */
                                            'status' => '2'
                                        ]);

                                    } else {
                                        throw new Exception("La tarjeta GiftCard ya fue consumida en su totalidad, ó no pertenece al cliente. Favor intente con otra", 401);
                                    }

                                }
                            }

                        }

                        if ($this->metodo_pago_pre == '') {

                            /**
                             * Logica para calcular el porcentaje de los servicios sin tocar el valor de los productos
                             * que ya fue calculado en pasos anteriores, al momento de asignar el prodcuto.
                             * number_format($total_vista_bsd, 2, ",", ".")
                             */
                            if($total_productos > 0){
                                /**Calculo el porcentaje que representa el total de los servicios */
                                $porcen_total_srv = ($total_servicios * 100) / $total_vista;

                                /**Calculoel porcentaje que representa el valor1($) y el valor2(Bs), que es la forma de pago del cliente */
                                $new_valor_uno = number_format((($porcen_total_srv * floatval($this->valor_uno)) / 100), 2, ",", ".");

                                /**Calculoel porcentaje que representa el valor1($) y el valor2(Bs), que es la forma de pago del cliente */
                                $new_valor_dos = number_format((($porcen_total_srv * Str::replace(',', '.', (Str::replace('.', '', $this->valor_dos)))) / 100), 2, ",", ".");

                                /**Busco la comision total del empleado calculada en la asignacion del producto */
                                $comision_emp_venprod = DB::table('venta_productos')
                                ->select(DB::raw('SUM(comision_empleado) as total'))
                                ->where('cod_asignacion', $codigo['cod_asignacion'])
                                ->where('facturado', '1')
                                ->first()->total;

                                /**Busco la comision total del gerente calculada en la asignacion del producto */
                                $comision_gerente_venprod = DB::table('venta_productos')
                                ->select(DB::raw('SUM(comision_gerente) as total'))
                                ->where('cod_asignacion', $codigo['cod_asignacion'])
                                ->where('facturado', '1')
                                ->first()->total;

                            }else{
                                $new_valor_uno = $this->valor_uno;
                                $new_valor_dos = $this->valor_dos;

                            }

                            /**
                             * AQUI DEBO LOCOCAR LA LOGICA PARA PODER SERPARAR LAS CANTIDADES Y PODER OBTENER EL VALOR 1 Y EL VALOR 2
                             * QUE VIAJAN AL CONTROLADOR
                             *
                             * ($this->valor_uno, $this->valor_dos)
                             */
                            // dump($new_valor_uno, floatval($new_valor_uno), $new_valor_dos, number_format($new_valor_dos, 2, ",", "."));
                            /**Controlador para calcular las comisiones */
                            $res = UtilsController::cal_comision_empleado($new_valor_uno, $new_valor_dos, $tipoSrv->asignacion, $tipoSrv->tipo_servicio_id, $total_servicios, $this->monto_giftcard);

                            /**
                             * Actualizamos la tabla de ventas, Detalles de asignacion y Disponible
                             * ($total_productos > 0) ?
                             */
                            $facturar = DB::table('venta_servicios')->where('cod_asignacion', $item->cod_asignacion)
                                ->update([
                                    'metodo_pago'           => ($this->op1 != '') ? $this->op1 : 'N/A',
                                    'metodo_pago_dos'       => ($this->op2 != '') ? $this->op2 : 'N/A',
                                    'metodo_pago_prepagado' => ($this->metodo_pago_pre != '') ? $this->metodo_pago_pre : 'N/A',
                                    'referencia'            => ($this->referencia == '') ? $this->referencia : 'N/A',
                                    'total_USD'             => $total_vista,
                                    'pago_usd'              => ($this->valor_uno == '') ? 0.00 : floatval($this->valor_uno),
                                    'pago_bsd'              => ($this->valor_dos == '') ? 0.00 : Str::replace(',', '.', (Str::replace('.', '', $this->valor_dos))),
                                    'propina_usd'           => ($this->propina_usd != '') ? $this->propina_usd : 0.00,
                                    'propina_bsd'           => ($this->propina_bsd != '') ? $this->propina_bsd : 0.00,
                                    'referencia_propina'    => $this->ref_propina,
                                    'comision_dolares'      => $res['comision_usd_emp_valorUno'],
                                    'comision_bolivares'    => $res['comision_bs_emp_valorDos'],
                                    'comision_gerente'      => ($total_productos > 0) ? $res['comision_usd_gte'] + $comision_gerente_venprod : $res['comision_usd_gte'],
                                    'comision_emp_venprod'  => ($total_productos > 0) ? $comision_emp_venprod : 0.00,
                                    'responsable_id'        => Auth::user()->id,
                                    'responsable'           => Auth::user()->name,
                                ]);

                            if($facturar){
                                /**Estatus facturado para los productos vendidos por el tecnico */
                                $prod_facturado = VentaProducto::where('cod_asignacion', $item->cod_asignacion)
                                ->where('facturado', 1)->get();
                                foreach($prod_facturado as $value)
                                {
                                    $value->facturado = 2;
                                    $value->save();
                                }

                            }
                        }

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

        $total_servicios = DB::table('detalle_asignacions')
            ->select(DB::raw('SUM(costo) as total'))
            ->where('cod_asignacion', $codigo['cod_asignacion'])
            ->where('status', '1')
            ->first()
            ->total;

        /**Calculo el total de los productos vendidos por el tecnico */
        $total_productos = DB::table('venta_productos')
            ->select(DB::raw('SUM(total_venta) as total'))
            ->where('cod_asignacion', $codigo['cod_asignacion'])
            ->where('status', '1')
            ->where('facturado', 1)
            ->first()->total;

        $tasa_bcv = TasaBcv::where('id', 1)->first()->tasa;

        if ($this->monto_giftcard != '') {
            $total_vista = $total_servicios - $this->monto_giftcard + $total_productos;
            $total_vista_bsd = $total_vista * $tasa_bcv;
        } elseif($this->metodo_pago_pre == 2) {
            $comision = Comision::where('aplicacion', 'seguro')->first()->porcentaje;
            $total_vista = $total_servicios - (($total_servicios * 10) / 100) + $total_productos;
            $total_vista_bsd = $total_vista * $tasa_bcv;
        }else{
            $total_vista = $total_servicios + $total_productos;
            $total_vista_bsd = $total_vista * $tasa_bcv;
        }

        /**Seleccion los productos que voy a vender y los muestro en la lista de 'Productos cargados' */
        $lista_prod = VentaProducto::where('cod_asignacion', $codigo['cod_asignacion'])->where('status', 1)->where('facturado', 1)->with('producto')->get();


        return view('livewire.caja', compact('data', 'detalle', 'total_vista', 'total_vista_bsd', 'lista_prod', 'total_productos'));
    }
}
