<?php

namespace App\Livewire;

use App\Http\Controllers\NotificacionesController;
use App\Http\Controllers\UtilsController;
use App\Models\Cliente;
use App\Models\DetalleAsignacion;
use App\Models\Disponible;
use App\Models\GiftCard;
use App\Models\Membresia;
use App\Models\MovimientoMembresia;
use App\Models\Servicio;
use App\Models\TasaBcv;
use App\Models\VentaServicio;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PagoExterno extends Component
{

    public $barcode;

    #[Validate('required', message: 'Campo requerido')]
    public $cod_asignacion;

    public $pcs;

    public $atr_pgc = 'hidden';
    public $atr_label = '';
    public $atr_input = '';

    public $atr_acciones = 'hidden';
    public $atr_btn_validar = '';
    public $atr_btn_salir = 'hidden';
    public $atr_facturar = 'hidden';

    public function input_pgc(){
        $this->atr_pgc = '';
        $this->atr_label = 'hidden';
        $this->atr_input = 'hidden';

    }

    public function validar_gift(){
        /**Validar GiftCard */
        $valida = GiftCard::Where('codigo_seguridad', $this->barcode)
        ->orWhere('pgc', $this->pcs)
        ->first();
        /**Validar GiftCard */
        $valida_mem = Membresia::Where('cod_membresia', $this->barcode)
        ->orWhere('pm', $this->pcs)
        ->first();

        if(isset($valida) and $valida->status == 1){
            session()->flash('activa','TARJETA GIFTCARD ACTIVA!');
            $this->atr_acciones = '';
            $this->atr_btn_validar = 'hidden';
        }elseif(isset($valida) and $valida->status == 2){
            session()->flash('vencida','TARJETA GIFTCARD INACTIVA. YA FUE UTILIZADA!');
            $this->atr_btn_validar = 'hidden';
            $this->atr_btn_salir = '';
        }elseif(isset($valida_mem) and $valida_mem->status == 1){
            session()->flash('activa','MEMBRESIA ACTIVA!');
            $this->atr_acciones = '';
            $this->atr_btn_validar = 'hidden';
        }elseif(isset($valida_mem) and $valida_mem->status == 2){
            session()->flash('vencida','MEMBRESIA VENCIDA. DEBE RENOVAR!');
            $this->atr_btn_validar = 'hidden';
            $this->atr_btn_salir = '';
        }
        else{
            session()->flash('error','EL CODIGO NO EXISTE. INTENTE NUEVAMENTE!');
            $this->atr_btn_validar = 'hidden';
            $this->atr_btn_salir = '';
        }

    }

    public function salir(){
        return redirect('/l/e');
    }

    public function facturar(){
        $this->atr_facturar = '';
        $this->atr_acciones = 'hidden';
        $this->atr_btn_validar = 'hidden';
    }

    public function facturtar_servicio(){

        $this->validate();

        $user = Session::get('usuario');

        try {

            /**Tasa BCV del dia */
            $tasa = TasaBcv::where('fecha', date('d-m-Y'))->first()->tasa;

            /**1. pregunto por el servicio en base de datos y obtengo toda su informacion */
            $servicio = VentaServicio::where('cod_asignacion', $this->cod_asignacion)->first();

            /**Obtengo el codigo del servicio a facturar */
            $srv_vip = DetalleAsignacion::where('cod_asignacion', $this->cod_asignacion)
            ->where('status', '1')
            ->first()
            ->cod_servicio;

            /**Pregunto? si la asignacion del servicio en VIP */
            $tipoSrv = Servicio::where('cod_servicio', $srv_vip)->first()->asignacion;

            if(isset($servicio) && $servicio->fecha_venta == date('d-m-Y')){

                /**2. pregunto por la giftCard y me traigo toda la informacion */
                $giftCard = GiftCard::orWhere('codigo_seguridad', $this->barcode)
                ->orWhere('pgc', $this->pcs)
                ->first();

                /**3. pregunto por la membresia y me traigo toda la informacion */
                $membresia = Membresia::orWhere('cod_membresia', $this->barcode)
                ->orWhere('pm', $this->pcs)
                ->first();

                /**Si existe la giftcard realizo el pago */
                if(isset($giftCard)){

                    /**Restricciones */
                    /**1.- Debe pertenecer al mismo cliente que fue asignado en el servicio  */
                    if($giftCard->cliente_id == $servicio->cliente_id){

                        /**2.- El monto de la giftcard debe ser igual al total del servicio a facturar */
                        if($giftCard->monto >= $servicio->total_USD){

                            $res = UtilsController::cal_comision_giftCard($giftCard->monto, $tipoSrv);

                            DB::table('venta_servicios')->where('cod_asignacion', $this->cod_asignacion)
                                ->update([
                                    'metodo_pago'   => 'giftCard',
                                    'referencia'    => $giftCard->referencia,
                                    'pago_usd'      => 0.00,
                                    'pago_bsd'      => 0.00,
                                    'propina_usd'   => 0.00,
                                    'propina_bsd'   => 0.00,
                                    'comision_dolares' => $res['comision_usd_emp'],
                                    'comision_gerente' => $res['comision_usd_gte'],
                                    'responsable'   => $user->name,
                                ]);

                            DetalleAsignacion::where('cod_asignacion', $this->cod_asignacion)->where('status', '1')
                                ->update([
                                    'status' => '2',
                                ]);

                            Disponible::where('cod_asignacion', $this->cod_asignacion)->where('status', 'por facturar')
                                ->update([
                                    'status' => 'facturado'
                                ]);

                            /**4. Actualizo la giftcard a status 2 (Ya utilizada) */
                            GiftCard::where('cliente_id', $servicio->cliente_id)
                                ->orWhere('codigo_seguridad', $this->barcode)
                                ->orWhere('pgc', $this->pcs)
                                ->update([
                                    'status' => '2',
                                ]);

                            /** Notificacion para el administrador de sistemas al asignar una nueva giftcard */
                            $type = 'gift-card-usada';
                            $correo = env('GIFTCARD_EMAIL');
                            $mailData = [
                                'codigo_asignacion' => $this->cod_asignacion,
                                'codigo_seguridad'  => $giftCard->codigo_seguridad,
                                'cliente'           => $servicio->cliente,
                                'tecnico'           => $servicio->empleado,
                                'fecha_venta'       => $servicio->fecha_venta,
                                'servicio'          => Disponible::where('cod_asignacion', $this->cod_asignacion)->where('status', 'facturado')->first()->servicio,
                                'responsable'       => $servicio->responsable,
                                'tasa'              => $tasa,
                                'user_email'        => 'gusta.acp@gmail.com',
                            ];
                            NotificacionesController::notification($mailData, $type, $servicio->fecha_venta);
                            /**Fin del envio de notificacion al administrador */

                            $this->redirect('/pay/ex');
                        }else{
                            $error = ValidationException::withMessages(['gift' => 'La monto de la  GiftCard debe ser igual al monto total a pagar.']);

                            Notification::make()
                                ->title('NOTIFICACIÓN')
                                ->icon('heroicon-o-shield-check')
                                ->color('danger')
                                ->body($error->getMessage())
                                ->send();
                        }

                    }else{
                        $error = ValidationException::withMessages(['gift' => 'La tarjeta GiftCard no pertenece al cliente registrado en el servicio']);
                        Notification::make()
                            ->title('NOTIFICACIÓN')
                            ->icon('heroicon-o-shield-check')
                            ->color('danger')
                            ->body($error->getMessage())
                            ->send();
                    }

                }elseif(isset($membresia)){

                    if($membresia->cliente_id == $servicio->cliente_id){

                        $dia = date('w');
                        /** La membresia solo puede ser utilizada los dias Domingo = 0, Lunes = 1, Martes = 2, Miercoles = 3, Jueves = 4*/
                        if($dia == '0' || $dia == '1' || $dia == '2' || $dia == '3' || $dia == '4'){

                            /**Me traigo de la base de datos toda la informacion de dicha membresia */
                            $mov_membresia = MovimientoMembresia::where('cod_membresia', $this->barcode)->first();

                            /**Fecha de EXP de la membresia */
                            $fecha_exp = Membresia::where('cod_membresia', $this->barcode)->first()->fecha_exp;

                            DB::table('venta_servicios')->where('cod_asignacion', $this->cod_asignacion)
                                ->update([
                                    'metodo_pago'   => 'Membresia',
                                    'referencia'    => $mov_membresia->referencia,
                                    'membresia_exp' => date("m/y", strtotime($fecha_exp )),
                                    'pago_usd'      => 0.00,
                                    'pago_bsd'      => 0.00,
                                    'propina_usd'   => 0.00,
                                    'propina_bsd'   => 0.00,
                                    'responsable'   => $user->name,
                                ]);

                            DetalleAsignacion::where('cod_asignacion', $this->cod_asignacion)->where('status', '1')
                                ->update([
                                    'status' => '2',
                                ]);

                            Disponible::where('cod_asignacion', $this->cod_asignacion)->where('status', 'por facturar')
                                ->update([
                                    'status' => 'facturado'
                                ]);

                            /**Contador de membresias por cliente */
                            $cliente = Cliente::where('id', $membresia->cliente_id)->first();
                            $cliente->visitas_membresia = $cliente->visitas_membresia + 1;
                            $cliente->update([
                                    'visitas_membresia' => $cliente->visitas_membresia,
                                ]);

                            /** Notificacion para el administrador de sistemas al asignar una nueva giftcard */
                            $type = 'membresia-usada';
                            $correo = env('GIFTCARD_EMAIL');
                            $mailData = [
                                'codigo_asignacion' => $this->cod_asignacion,
                                'cod_membresia'     => $membresia->cod_membresia,
                                'cliente'           => $membresia->cliente->nombre.' '.$membresia->cliente->apellido,
                                'tecnico'           => $servicio->empleado,
                                'fecha_venta'       => $servicio->fecha_venta,
                                'servicio'          => Disponible::where('cod_asignacion', $this->cod_asignacion)->where('status', 'facturado')->first()->servicio,
                                'responsable'       => $servicio->responsable,
                                'tasa'              => $tasa,
                                'user_email'        => 'gusta.acp@gmail.com',
                            ];
                            NotificacionesController::notification($mailData, $type, $servicio->fecha_venta);
                            /**Fin del envio de notificacion al administrador */

                            $this->redirect('/pay/ex');
                        }else{
                            $error = ValidationException::withMessages(['gift' => 'La monto de la  GiftCard debe ser igual al monto total a pagar.']);
                            Notification::make()
                                ->title('NOTIFICACIÓN')
                                ->icon('heroicon-o-shield-check')
                                ->color('danger')
                                ->body($error->getMessage())
                                ->send();
                        }

                    }else{
                        $error = ValidationException::withMessages(['membresia' => 'La membresia no pertenece al cliente registrado en el servicio']);
                        Notification::make()
                            ->title('NOTIFICACIÓN')
                            ->icon('heroicon-o-shield-check')
                            ->color('danger')
                            ->body($error->getMessage())
                            ->send();
                    }

                }else{
                    $error = ValidationException::withMessages(['gift-membresia' => 'Esta operación no cumple con los requisitos de seguridad. Debe adquirir otra giftcard o renovar su membresia']);

                    Notification::make()
                        ->title('NOTIFICACIÓN')
                        ->icon('heroicon-o-shield-check')
                        ->color('danger')
                        ->body($error->getMessage())
                        ->send();

                    return redirect('/p/e');
                }

            }else{
                $error = ValidationException::withMessages(['servicio' => 'Debe cerrar el servicio para poder ejecutar esta acción. Cierre y vuelva a intentar']);

                Notification::make()
                    ->title('NOTIFICACIÓN')
                    ->icon('heroicon-o-shield-check')
                    ->color('danger')
                    ->body($error->getMessage())
                    ->send();

                return redirect('/p/e');
            }

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function render()
    {
        return view('livewire.pago-externo');
    }
}
