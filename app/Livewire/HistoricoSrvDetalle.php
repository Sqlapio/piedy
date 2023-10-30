<?php

namespace App\Livewire;

use App\Models\Cita;
use App\Models\VentaServicio;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Auth\Access\Gate;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use WireUi\Traits\Actions;

class HistoricoSrvDetalle extends ModalComponent
{
    use Actions;
    
    public VentaServicio $venta;

    public function cerrar_modal() 
    {
        $this->forceClose()->closeModal();
    }

    public function render()
    {
        $data = VentaServicio::where('cod_asignacion', $this->venta->cod_asignacion)->first();
        return view('livewire.historico-srv-detalle', compact('data'));
    }
}
