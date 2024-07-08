<?php

namespace App\Livewire;

use Livewire\Component;

class ListaServicioEmpleado extends Component
{
    public $desde;
    public $hasta;

    public function render()
    {
        return view('livewire.lista-servicio-empleado');
    }
}
