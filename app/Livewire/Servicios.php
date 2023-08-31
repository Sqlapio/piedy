<?php

namespace App\Livewire;

use Livewire\Component;

class Servicios extends Component
{

    public $cod_servicio;
    public $descripcion;
    public $costo;
    public $duracion_max; //este valor debe ser expresado en minutos
    public $comision_id;

    public $buscar;




    
    public function render()
    {
        return view('livewire.servicios');
    }
}
