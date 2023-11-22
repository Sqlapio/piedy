<?php

namespace App\Livewire;

use App\Models\Promocion;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class ModalPromociones extends ModalComponent
{

    public Promocion $promocion;

    public $servicios = [];
    public $empleado_id;

    public function asignar_sevicio()
    {
        dd($this->servicios);
    }

    public function render()
    {
        return view('livewire.modal-promociones');
    }
}
