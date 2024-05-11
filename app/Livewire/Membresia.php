<?php

namespace App\Livewire;

use App\Http\Controllers\NotificacionesController;
use App\Models\Cliente;
use App\Models\GiftCard as ModelsGiftCard;
use App\Models\TasaBcv;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Membresia extends Component
{
    #[Validate('required', message: 'Campo requerido')]
    public $monto;

    #[Validate('required', message: 'Campo requerido')]
    public $metodo_pago;

    #[Validate('required', message: 'Campo requerido')]
    public $referencia;

    #[Validate('required', message: 'Campo requerido')]
    public $cliente_id;

    public $pago_bsd;
    public $cliente;
    public $cod_gift_card;
    public $fecha_emicion;
    public $fecha_vence;
    public $pgc;
    public $codigo_seguridad;

    public function render()
    {
        $this->codigo_seguridad = random_int(111111, 999999);
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

        $tasa = TasaBcv::where('fecha', date('d-m-Y'))->first()->tasa;

        return view('livewire.membresia', compact('barcode', 'pgc', 'tasa'));
    }
}
