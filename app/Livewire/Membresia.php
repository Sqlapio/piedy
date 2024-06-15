<?php

namespace App\Livewire;

use App\Http\Controllers\NotificacionesController;
use App\Models\Cliente;
use App\Models\Frecuencia;
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

    /**Propiedades para agregar a un nuevo cliente */
    /***************************************************************** */
    public $nombre;
    public $apellido;
    public $cedula;
    public $email;
    public $telefono;
    /***************************************************************** */

    public $tasa;
    public $cliente;
    public $codigo_seguridad;
    public $code;

    public $atr_nuevo_cliente = 'hidden';
    public $atr_hidden = 'block';

    public function nuevo_cliente()
    {
        $this->atr_nuevo_cliente = 'block';
        $this->atr_hidden = 'hidden';
    }

    public function store_nuevo_cliente()
    {

        try {

            $user = Auth::user();

            $cliente = new Cliente();
            $cliente->nombre      = strtoupper($this->nombre);
            $cliente->apellido    = strtoupper($this->apellido);
            $cliente->cedula      = $this->cedula;
            $cliente->email       = $this->email;
            $cliente->telefono    = $this->telefono;
            $cliente->user_id     = $user->id;
            $cliente->responsable = $user->name;
            $cliente->save();

            /** El nuevo cliente es cargado en la tabla de frecuencias
             * para fines estadisticos
             */
            $cliente_nuevo = new Frecuencia();
            $cliente_nuevo->cliente_id  = $cliente->id;
            $cliente_nuevo->nombre      = strtoupper($cliente->nombre.' '.$cliente->apellido);
            $cliente_nuevo->save();

            Notification::make()
                ->title('Cliente creado con éxito')
                ->success()
                ->send();

            $this->reset();

            $this->redirect('/m');

        } catch (\Throwable $th) {
            Notification::make()
            ->title('NOTIFICACIÓN')
            ->icon('heroicon-o-shield-check')
            ->color('primary')
            ->body($th->getMessage())
            ->send();
        }
    }

    public function regresar(){
        $this->redirect('/g/m');
    }

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
                $barcode = random_int(11111111, 99999999);

                /**genero el codigo de barra y lo guardo como imagen .jpg */
                $generator = new \Picqer\Barcode\BarcodeGeneratorJPG();
                $image = $generator->getBarcode($barcode, $generator::TYPE_CODE_128);

                /**Guardo la imagen */
                Storage::put('public/barcodes/' . $barcode . '.jpg', $image);

                /**Guardo la informacion de la membresia y del usuario asignado */
                $asignar_membresia = new ModelsMembresia();
                $asignar_membresia->cod_membresia       = $barcode;
                $asignar_membresia->pm                  = rand('1111', '9999');
                $asignar_membresia->cliente_id          = $this->cliente_id;
                $asignar_membresia->fecha_activacion    = now()->format('d-m-Y');
                $asignar_membresia->fecha_exp           = date("d-m-Y", strtotime(date($asignar_membresia->fecha_activacion) . "+1 month"));
                $asignar_membresia->monto               = $this->monto;
                $asignar_membresia->referencia          = $this->referencia;
                $asignar_membresia->barcode             = '/barcodes/'.$barcode.'.jpg';
                $asignar_membresia->save();

                if ($asignar_membresia->save()) {
                    $mov_membresia = new MovimientoMembresia();
                    $mov_membresia->membresia_id   = $asignar_membresia->id;
                    $mov_membresia->descripcion    = 'activacion';
                    $mov_membresia->cliente_id     = $this->cliente_id;
                    $mov_membresia->cliente        = $asignar_membresia->cliente->nombre.' '.$asignar_membresia->cliente->apellido;
                    $mov_membresia->cedula         = $asignar_membresia->cliente->cedula;
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
        $this->codigo_seguridad = random_int(1111111111111111, 9999999999999999);
        $generator = new \Picqer\Barcode\BarcodeGeneratorHTML();
        $barcode = $generator->getBarcode($this->codigo_seguridad, $generator::TYPE_CODE_128);

        $cliente = Cliente::where('id', $this->cliente_id)->first();
        if(isset($cliente)){
            $nom_ape = $cliente->nombre.' '.$cliente->apellido;
            $this->cliente = $nom_ape;
        }else{
            $this->cliente = '---- ----';
        }
        /**Tasa BCV del dia */
        $tasa = TasaBcv::where('fecha', date('d-m-Y'))->first()->tasa;
        return view('livewire.membresia', compact('tasa', 'barcode'));
    }
}
