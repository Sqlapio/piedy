<?php

namespace App\Livewire;

use App\Models\Promocion as ModelsPromocion;
use Livewire\Component;

class Promocion extends Component
{
    public function render()
    {
        $data = ModelsPromocion::all();
        return view('livewire.promocion', compact('data'));
    }
}
