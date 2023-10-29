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

    public function inicio(){
        redirect()->to('/dashboard');
    }

    public function citas(){
        redirect()->to('/citas');
    }

    public function clientes(){
        redirect()->to('/clientes');
    }

    public function cabinas(){
        redirect()->to('/cabinas');
    }

    public function productos(){
        redirect()->to('/productos');
    }

    public function servicios(){
        redirect()->to('/servicios');
    }

    
    public function render()
    {
        return view('livewire.productos');
    }
}
