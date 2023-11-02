<?php

namespace App\Livewire;

use App\Models\Empleado;
use Livewire\Component;
use Livewire\WithPagination;

class Empleados extends Component
{

    use WithPagination;

    public function render()
    {
        return view('livewire.empleados', [
            'data' => Empleado::orderBy('id', 'asc')                                   
                ->paginate(8)
        ]);
    }
}
