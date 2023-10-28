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

    public function increment() {
        $this->hora = Carbon::now('America/Caracas')->toArray();
        $minutos = $this->hora['minute'];
        $this->count++;
    }

    public function render()
    {
        $data = Disponible::all();
        return view('livewire.cabinas', compact('data'));
    }
}
