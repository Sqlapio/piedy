<?php

namespace App\Livewire;

use Livewire\Component;

class Productos extends Component
{

    public $cod_producto;
    public $descripcion;
    public $existencia;
    public $precio;
    public $comision_id;

    public $buscar;

    
    public function render()
    {
        return view('livewire.productos');
    }
}
