<?php

namespace App\Livewire;

use App\Http\Controllers\NotificacionesController;
use App\Models\Cliente;
use App\Models\GiftCard as ModelsGiftCard;
use App\Models\TasaBcv;
use App\Models\Frecuencia;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Livewire\Component;

class GiftCard extends Component
{
    #[Validate('required', message: 'Campo requerido')]
    public $monto;

    #[Validate('required', message: 'Campo requerido')]
    public $metodo_pago;

    #[Validate('required', message: 'Campo requerido')]
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


    public $pago_bsd;
    public $cliente;
    public $cod_gift_card;
    public $fecha_emicion;
    public $fecha_vence;
    public $pgc;
    public $codigo_seguridad;

    public $atr_nuevo_cliente = 'hidden';
    public $atr_hidden = 'block';
    public $atr_tabla = 'block';

    public $servicios = [];

    public function nuevo_cliente()
    {
        $this->atr_nuevo_cliente = 'block';
        $this->atr_hidden = 'hidden';
        $this->atr_tabla = 'hidden';
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

            $this->redirect('/g/c');

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

            /**Tasa BCV del dia */
            $tasa = TasaBcv::where('fecha', date('d-m-Y'))->first()->tasa;

            $generator_email = new \Picqer\Barcode\BarcodeGeneratorJPG();
            $image = $generator_email->getBarcode($this->codigo_seguridad, $generator_email::TYPE_CODE_128);
            Storage::put('public/barcodes/'.$this->codigo_seguridad.'.jpg', $image);

            $asignar_giftCard = new ModelsGiftCard();
            $asignar_giftCard->cod_gift_card    = $this->cod_gift_card;
            $asignar_giftCard->codigo_seguridad = $this->codigo_seguridad;
            $asignar_giftCard->pgc              = $this->pgc;
            $asignar_giftCard->cliente_id       = $this->cliente_id;
            $asignar_giftCard->cliente          = $this->cliente;
            $asignar_giftCard->monto            = $this->monto;
            $asignar_giftCard->fecha_emicion    = $this->fecha_emicion;
            $asignar_giftCard->fecha_vence      = $this->fecha_vence;
            $asignar_giftCard->metodo_pago      = $this->metodo_pago;
            $asignar_giftCard->pago_usd         = ($this->metodo_pago == 'Zelle') ? $this->monto : 0.00;
            $asignar_giftCard->pago_bsd         = ($this->metodo_pago == 'Transferencia' || $this->metodo_pago == 'Pago Movil') ? $this->monto * $tasa : 0.00;
            $asignar_giftCard->referencia       = ($this->referencia == '') ? 'efectivo' : $this->referencia;
            $asignar_giftCard->barcode          = '/barcodes/'.$this->codigo_seguridad.'.jpg';
            $asignar_giftCard->responsable      = Auth::user()->name;
            $asignar_giftCard->save();


            /** Notificacion para el usuario cuando adquiere la giftcard */
            $type = 'gift-card';

            $correo = Cliente::where('id',  $asignar_giftCard->cliente_id)->first()->email;

            if($asignar_giftCard->monto == '20'){
                $image = 'gift20.png';
            }
            if($asignar_giftCard->monto == '40'){
                $image = 'gift40.png';
            }

            $mailData = [
                    'codigo_seguridad'  => $asignar_giftCard->codigo_seguridad,
                    'pgc'               => $asignar_giftCard->pgc,
                    'cliente'           => $asignar_giftCard->cliente,
                    'barcode'           => $asignar_giftCard->barcode,
                    'image'             => $image,
                    'user_email'        => $correo,
                ];

            NotificacionesController::notification($mailData, $type);

            /** Notificacion para el administrador de sistemas al asignar una nueva giftcard */
            $type = 'gift-card-creada';
            $correo_administracion = env('GIFTCARD_EMAIL');
            $mailData = [
                'codigo_seguridad'  => $asignar_giftCard->codigo_seguridad.'-'.$asignar_giftCard->pgc,
                'cliente'           => $asignar_giftCard->cliente,
                'emitida'           => date('m/y'),
                'vence'             => date("m/y",strtotime($this->fecha_emicion."+ 6 month")),
                'monto'             => $asignar_giftCard->monto,
                'metodo_pago'       => $asignar_giftCard->metodo_pago,
                'monto_pagado'      => ($asignar_giftCard->pago_usd != 0) ? $asignar_giftCard->pago_usd : $asignar_giftCard->pago_bsd,
                'referencia'        => $asignar_giftCard->referencia,
                'tasa'              => $tasa,
                'user_email'        => $correo_administracion,
            ];
            NotificacionesController::notification($mailData, $type, $asignar_giftCard->pgc);
            /**Fin del envio de notificacion al administrador */


            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->body("La GiftCard fue asignada de forma exitosa")
                ->send();

            $this->reset();

            $this->render();

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
        if(count($this->servicios) > 0){
            $srv = array_sum($this->servicios);
            $this->monto = $srv;
        }else{
            $srv = 0;
        }

        $this->codigo_seguridad = random_int(1111111111111111, 9999999999999999);
        $generator = new \Picqer\Barcode\BarcodeGeneratorHTML();
        $barcode = $generator->getBarcode($this->codigo_seguridad, $generator::TYPE_CODE_128);

        $this->cod_gift_card = 'Pgc-'.random_int(111111, 999999);
        $this->fecha_emicion = date('d-m-Y');
        $this->fecha_vence = date("d-m-Y",strtotime($this->fecha_emicion."+ 6 month"));

        $this->pgc = rand('1111', '9999');
        $pgc = $this->pgc;

        $cliente = Cliente::where('id', $this->cliente_id)->first();
        if(isset($cliente)){
            $nom_ape = $cliente->nombre.' '.$cliente->apellido;
            $this->cliente = $nom_ape;
        }else{
            $this->cliente = '---- ----';
        }


        $tasa = TasaBcv::where('fecha', date('d-m-Y'))->first()->tasa;

        return view('livewire.gift-card', compact('barcode', 'pgc', 'tasa', 'srv'));
    }
}

