<?php

namespace App\Livewire;

use App\Models\Cita;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class AsignaServicio extends ModalComponent
{
    public $empleado_id;
    public Cita $cita;

    protected $listeners = [
        'selected'
    ];

    public function selected($value)
    {
        $this->empleado_id = $value;
    }

    public function asignar_tecnico()
    {
        dd($this->empleado_id, $this->cita->cliente_id);
    }

    public function render()
    {
        return view('livewire.asigna-servicio');
    }
}
