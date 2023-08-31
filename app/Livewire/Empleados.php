<?php

namespace App\Livewire;

use Livewire\Component;

class Empleados extends Component
{

    public $nombre;
    public $apellido;
    public $cedula;
    public $email;
    public $telefono;
    public $direccion_corta;
    public $tipo_empleado;
    public $Fecha_Contratación;

    public $buscar;









    public function render()
    {
        return view('livewire.empleados');
    }
}
