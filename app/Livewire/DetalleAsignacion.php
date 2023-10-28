<?php

namespace App\Livewire;

use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Disponible;
use App\Models\Empleado;
use App\Models\Servicio;
use Filament\Notifications\Notification;
use Livewire\Component;
use App\Livewire\Citas;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use LivewireUI\Modal\ModalComponent;
use WireUi\Traits\Actions;

class DetalleAsignacion extends ModalComponent
{
    use Actions;
    public Disponible $disponible;
    
    public function render()
    {
        $data = Disponible::where('id', $this->disponible->id)->first();
        return view('livewire.detalle-asignacion', compact('data'));
    }
}
