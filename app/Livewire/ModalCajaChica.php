<?php

namespace App\Livewire;


use App\Models\CajaChica as ModelsCajaChica;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use Livewire\Attributes\Rule;
use WireUi\Traits\Actions;

class ModalCajaChica extends ModalComponent
{

    use Actions;

    #[Rule('required')]
    public $monto;

    protected $messages = [
        'required' => 'Campo requerido',
    ];

    public function actualiza_caja_chica()
    {

        $hoy = date('d-m-Y');

        $caja_chica_apr = new ModelsCajaChica();
        $caja_chica_apr->monto = $this->monto;
        $caja_chica_apr->saldo = $this->monto;
        $caja_chica_apr->fecha = $hoy;
        $caja_chica_apr->save();

        $this->forceClose()->closeModal();

        redirect()->to('/gastos');

    }

    public function render()
    {
        return view('livewire.modal-caja-chica');
    }
}
