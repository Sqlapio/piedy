<?php

namespace App\Livewire;

use App\Models\Reporte;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ListaServicioEmpleado extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $desde;
    public $hasta;

    public function render()
    {
        return view('livewire.lista-servicio-empleado', [
            'info' => Reporte::where('user_id', Auth::user()->id)
                ->orderBy('fecha', 'desc')
                ->get()
        ]);
    }
}
