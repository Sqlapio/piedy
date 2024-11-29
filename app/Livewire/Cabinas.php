<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Servicio;
use App\Models\Disponible;
use Illuminate\Support\Facades\Auth;

class Cabinas extends Component
{
    public function render()
    {
        $data = Disponible::where('status', 'activo')
        ->where('sucursal_id', Auth::user()->sucursal_id)
        ->orWhere('status', 'cerrado')
        ->orWhere('status', 'por facturar')
        ->get();

        return view('livewire.cabinas', compact('data'));
    }
}