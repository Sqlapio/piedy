<?php

namespace App\Livewire;

use App\Models\VentaServicio;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class HistoricoServicios extends Component
{
    use WithPagination;

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
        $user = Auth::user();

        return view('livewire.historico-servicios', [
            'data' => VentaServicio::where('empleado_id', $user->id)
            ->orderBy('id', 'asc')                                   
                ->paginate(5)
        ]);
    }
}
