<?php

namespace App\Livewire;

use Livewire\Component;

class Clientes extends Component
{

    public $nombre;
    public $apellido;
    public $cedula;
    public $email;
    public $telefono;
    public $direccion_corta;

    public $buscar;


    public function render()
    {
        return view('livewire.clientes');
    }
}
