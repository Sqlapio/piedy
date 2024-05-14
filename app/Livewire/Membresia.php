<?php

namespace App\Livewire;

use App\Http\Controllers\NotificacionesController;
use App\Models\Cliente;
use App\Models\Membresia as ModelsMembresia;
use App\Models\MovimientoMembresia;
use App\Models\TasaBcv;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Membresia extends Component
{
    #[Validate('required', message: 'Campo requerido')]
    public $monto;

    #[Validate('required', message: 'Campo requerido')]
    public $metodo_pago;

    public $referencia;

    #[Validate('required', message: 'Campo requerido')]
    public $cliente_id;

    public $tasa;


    public function store()
    {
        $this->validate();

        try {

            /**Restriccion para que solo sea asignada un solo codgio de membresia */
            $cliente_existe = ModelsMembresia::find($this->cliente_id);

            if (isset($cliente_existe)) {
                $error = ValidationException::withMessages(['error' => 'El cliente ya tiene una membresia asigana. Debe verificar el estatus de la misma']);
                Notification::make()
                    ->title('NOTIFICACIÓN')
                    ->icon('heroicon-o-shield-check')
                    ->color('danger')
                    ->body($error->getMessage())
                    ->send();
            } else {
                /**Tasa BCV del dia */
                $tasa = TasaBcv::where('fecha', date('d-m-Y'))->first()->tasa;

                /**Genero el codigo de la membresia */
                $barcode = random_int(111111111111111, 999999999999999);

                /**genero el codigo de barra y lo guardo como imagen .jpg */
                $generator = new \Picqer\Barcode\BarcodeGeneratorJPG();
                $image = $generator->getBarcode($barcode, $generator::TYPE_CODE_128);

                /**Guardo la imagen */
                Storage::put('public/barcodes/membresias/' . $barcode . '.jpg', $image);

                /**Guardo la informacion de la membresia y del usuario asignado */
                $asignar_membresia = new ModelsMembresia();
                $asignar_membresia->cod_membresia    = $barcode;
                $asignar_membresia->pm                  = rand('1111', '9999');
                $asignar_membresia->cliente_id          = $this->cliente_id;
                $asignar_membresia->fecha_activacion    = now()->format('d-m-Y');
                $asignar_membresia->fecha_exp           = date("d-m-Y", strtotime(date($asignar_membresia->fecha_activacion) . "+1 month"));
                $asignar_membresia->monto               = $this->monto;
                $asignar_membresia->barcode             = $barcode . '.jpg';
                $asignar_membresia->save();

                if ($asignar_membresia->save()) {

                    $exite = MovimientoMembresia::where('cod_membresia', $asignar_membresia->cod_membresia)
                        ->where('descripcion', 'activacion')
                        ->first();

                    $mov_membresia = new MovimientoMembresia();
                    $mov_membresia->membresia_id        = $asignar_membresia->id;
                    $mov_membresia->cod_membresia       = $asignar_membresia->cod_membresia;
                    $mov_membresia->descripcion         = (isset($exite)) ? 'renovacion' : 'activacion';
                    $mov_membresia->cliente_id          = $this->cliente_id;
                    $mov_membresia->fecha_inicio        = $asignar_membresia->fecha_activacion;
                    $mov_membresia->fecha_fin           = date("d-m-Y", strtotime(date($asignar_membresia->fecha_activacion) . "+1 month"));
                    $mov_membresia->monto               = $asignar_membresia->monto;
                    $mov_membresia->metodo_pago         = $this->metodo_pago;
                    $mov_membresia->pago_usd            = ($this->metodo_pago == 'Zelle') ? $this->monto : 0.00;
                    $mov_membresia->pago_bsd            = ($this->metodo_pago == 'Transferencia' || $this->metodo_pago == 'Pago Movil') ? $this->monto * $tasa : 0.00;
                    $mov_membresia->referencia          = ($this->referencia == '') ? 'efectivo' : $this->referencia;
                    $mov_membresia->save();
                } else {
                    throw new Exception("Error al guardar en la informacion");
                }

                /** Notificacion para el usuario cuando adquiere la membresia */
                $type = 'membresia';
                $info_cliente = Cliente::where('id',  $asignar_membresia->cliente_id)->first();

                $mailData = [
                    'pm'                => $asignar_membresia->pm,
                    'exp'               => date("m/y", strtotime($asignar_membresia->fecha_exp)),
                    'cliente'           => $info_cliente->nombre . ' ' . $info_cliente->apellido,
                    'barcode'           => $asignar_membresia->barcode,
                    'user_email'        => 'gusta.acp@gmail.com',
                ];

                NotificacionesController::notification($mailData, $type);

                /** Notificacion para el administrador de sistemas al activar una nueva membresia */
                $type = 'membresia-activada';
                $correo = env('GIFTCARD_EMAIL');
                $mailData = [
                    'codigo_seguridad'  => $asignar_membresia->cod_membresia . '-' . $asignar_membresia->pm,
                    'cliente'           => $info_cliente->nombre . ' ' . $info_cliente->apellido,
                    'emitida'           => date('m/y'),
                    'vence'             => date("m/y", strtotime($asignar_membresia->fecha_exp)),
                    'monto'             => $asignar_membresia->monto,
                    'metodo_pago'       => $mov_membresia->metodo_pago,
                    'monto_pagado'      => ($mov_membresia->pago_usd != 0) ? $mov_membresia->pago_usd : $mov_membresia->pago_bsd,
                    'referencia'        => $mov_membresia->referencia,
                    'tasa'              => $tasa,
                    'user_email'        => 'gusta.acp@gmail.com',
                ];
                // dd($mailData);
                NotificacionesController::notification($mailData, $type, $asignar_membresia->pm);
                /**Fin del envio de notificacion al administrador */

                Notification::make()
                    ->title('NOTIFICACIÓN')
                    ->icon('heroicon-o-shield-check')
                    ->iconColor('danger')
                    ->body("La Membresia fue activada de forma exitosa")
                    ->send();

                $this->reset();
            }
        } catch (\Throwable $th) {
            Notification::make()
                ->title('NOTIFICACIÓN DE ERROR')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
    }

    public function render()
    {
        /**Tasa BCV del dia */
        $tasa = TasaBcv::where('fecha', date('d-m-Y'))->first()->tasa;
        return view('livewire.membresia', compact('tasa'));
    }
}
