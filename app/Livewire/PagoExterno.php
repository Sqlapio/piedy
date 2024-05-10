<?php

namespace App\Livewire;

use App\Http\Controllers\NotificacionesController;
use App\Http\Controllers\UtilsController;
use App\Models\DetalleAsignacion;
use App\Models\Disponible;
use App\Models\GiftCard;
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

    public $pgc;

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
        $valida = GiftCard::Where('codigo_seguridad', $this->barcode)
        ->orWhere('pgc', $this->pgc)
        ->first();

        if(isset($valida) and $valida->status == 1){
            session()->flash('activa','TARJETA ACTIVA!');
            $this->atr_acciones = '';
            $this->atr_btn_validar = 'hidden';
        }elseif(isset($valida) and $valida->status == 2){
            session()->flash('vencida','TARJETA INACTIVA. YA FUE UTILIZADA!');
            $this->atr_btn_validar = 'hidden';
            $this->atr_btn_salir = '';
        }else{
            session()->flash('error','LA TARJETA NO EXISTE. INTENTE NUEVAMENTE!');
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

            if(isset($servicio) && $servicio->fecha_venta == date('d-m-Y')){

                /**2. pregunto por la giftCard y me traigo toda la informacion */
                $giftCard = GiftCard::orWhere('codigo_seguridad', $this->barcode)
                ->orWhere('pgc', $this->pgc)
                ->first();

                if(isset($giftCard)){

                    /**Restricciones */
                    /**1.- Debe pertenecer al mismo cliente que fue asignado en el servicio  */
                    if($giftCard->cliente_id == $servicio->cliente_id){

                        /**2.- El monto de la giftcard debe ser igual al total del servicio a facturar */
                        if($giftCard->monto == $servicio->total_USD){

                            DB::table('venta_servicios')->where('cod_asignacion', $this->cod_asignacion)
                                ->update([
                                    'metodo_pago'   => 'giftCard',
                                    'referencia'    => $giftCard->referencia,
                                    'pago_usd'      => 0.00,
                                    'pago_bsd'      => 0.00,
                                    'propina_usd'   => 0.00,
                                    'propina_bsd'   => 0.00,
                                    'comision_dolares' => UtilsController::cal_comision_empleado($giftCard->monto),
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
                                ->orWhere('pgc', $this->pgc)
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

                }else{
                    $error = ValidationException::withMessages(['gift' => 'La tarjeta GiftCard ya fue utilizada. Debe adquirir otra tarjeta y repetir esta acción']);

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
