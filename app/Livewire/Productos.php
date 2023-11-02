<?php

namespace App\Livewire;

use App\Models\Producto;
use Livewire\Component;
use Livewire\WithPagination;

class Productos extends Component
{

    use WithPagination;

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
        return view('livewire.productos', [
            'data' => Producto::orderBy('id', 'asc')                                   
                ->paginate(5)
        ]);
    }
}
