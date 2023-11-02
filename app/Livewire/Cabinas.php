<?php

namespace App\Livewire;

use App\Models\Disponible;
use Livewire\Component;
use Carbon\Carbon;

class Cabinas extends Component
{
    //Hora de la aplicacion
    public $hora;

    public $porcent = 1;

    //Hora actual + los minutos de duracion del servicio
    public $hora_final;

    public $duracion_max;

    public $count = 0;
    public $minutos = 120;
    public $tiempo = 0;
    public $atr_activo = '';
    public $atr_finalizado = 'hidden';

    public $bg = 'bg-[#99ccff]';

    public function increment() {
        $this->hora = Carbon::now('America/Caracas')->toArray();
        $minutos = $this->hora['minute'];
        $this->count++;
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
        $data = Disponible::all();
        return view('livewire.cabinas', compact('data'));
    }
}
