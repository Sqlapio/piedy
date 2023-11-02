<?php

namespace App\Livewire;

use Livewire\Component;

class Perfil extends Component
{

    public function inicio()
    {
        redirect()->to('/dashboard');
    }

    public function historico()
    {
        redirect()->to('/historico/servicios');
    }

    public function asignados()
    {
        redirect()->to('/servicio/asignado');
    }

    public function perfil()
    {
        redirect()->to('/perfil');
    }
    
    public function render()
    {
        return view('livewire.perfil');
    }
}
