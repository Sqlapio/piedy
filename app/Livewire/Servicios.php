<?php

namespace App\Livewire;

use App\Models\Servicio;
use Livewire\Component;
use Livewire\WithPagination;

class Servicios extends Component
{

    use WithPagination;

    public function promociones(){
        redirect()->to('/promociones');
    }

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
        return view('livewire.servicios', [
            'data' => Servicio::orderBy('id', 'asc')                                   
                ->paginate(5)
        ]);
    }
}
