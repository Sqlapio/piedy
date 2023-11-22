<?php

namespace App\Livewire;

use App\Models\Promocion as ModelsPromocion;
use Livewire\Component;

class Promocion extends Component
{
    
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

    public function atras(){
        redirect()->to('/dashboard');
    }

    public function render()
    {
        $data = ModelsPromocion::all();
        return view('livewire.promocion', compact('data'));
    }
}
