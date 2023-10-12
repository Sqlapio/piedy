<?php

namespace App\Livewire;

use App\Models\Servicio;
use Livewire\Component;
use Livewire\WithPagination;

class Servicios extends Component
{

    use WithPagination;

    public function render()
    {
        return view('livewire.servicios', [
            'data' => Servicio::orderBy('id', 'asc')                                   
                ->paginate(8)
        ]);
    }
}
