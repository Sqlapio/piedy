<?php

namespace App\Livewire;

use App\Http\Controllers\NotificacionesController;
use App\Models\Cliente;
use App\Models\GiftCard as ModelsGiftCard;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Rule;
use Livewire\Component;

class GiftCard extends Component
{
    #[Rule('required', message: 'Campo requerido')]
    public $monto;

    #[Rule('required', message: 'Campo requerido')]
    public $cliente_id;

    public $cliente;

    public $cod_gift_card;
    public $fecha_emicion;
    public $fecha_vence;
    public $pgc;
    public $codigo_seguridad;

    public $metodo_pago;
    public $referencia;

    public function store()
    {

        $this->validate();

        try {

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
            $asignar_giftCard->referencia      = ($this->referencia == '') ? 'efectivo' : $this->referencia;
            $asignar_giftCard->barcode          = $this->codigo_seguridad.'.jpg';
            $asignar_giftCard->responsable      = Auth::user()->name;
            $asignar_giftCard->save();


            /** Notificacion para el usuario cuando su servicio fue anulado */
            $type = 'gift-card';
            // $correo = Cliente::where('id',  $asignar_giftCard->cliente_id)->first()->email;
            $correo = 'gusta.acp@gmail.com';

            

            $mailData = [
                    'codigo_seguridad'  => $asignar_giftCard->codigo_seguridad,
                    'pgc'               => $asignar_giftCard->pgc,
                    'cliente'           => $asignar_giftCard->cliente,
                    'barcode'           => $asignar_giftCard->barcode,
                    'user_email'        => $correo,
                ];

            NotificacionesController::notification($mailData, $type);
            
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->body("La GiftCard fue asignada de forma exitosa")
                ->send();

            $this->reset();

        } catch (\Throwable $th) {
            dd($th);
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
        $this->codigo_seguridad = random_int(111111111111111, 999999999999999);
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
        }

        return view('livewire.gift-card', compact('barcode', 'pgc'));
    }
}

