<?php

namespace App\Livewire;

use App\Models\GiftCard as ModelsGiftCard;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Component;

class GiftCard extends Component
{
    #[Rule('required', message: 'Campo requerido')]
    public $monto;

    #[Rule('required', message: 'Campo requerido')]
    public $cliente_id;

    public $cod_gift_card;
    public $fecha_emicion;

    public function store()
    {
        $this->validate();

        try {

            $saldo = $this->monto;

            $asignar_giftCard = new ModelsGiftCard();
            $asignar_giftCard->cod_gift_card = $this->cod_gift_card;
            $asignar_giftCard->cliente_id = $this->cliente_id;
            $asignar_giftCard->monto = $this->monto;
            $asignar_giftCard->saldo = $saldo;
            $asignar_giftCard->fecha_emicion= $this->fecha_emicion;
            $asignar_giftCard->responsable= Auth::user()->name;
            $asignar_giftCard->save();

            $this->reset();

            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->body("La GiftCard fue asignada de forma exitosa")
                ->send();

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
        $this->cod_gift_card = 'Pgc-'.random_int(111111, 999999);
        $this->fecha_emicion = date('d-m-Y');
        return view('livewire.gift-card');
    }
}
