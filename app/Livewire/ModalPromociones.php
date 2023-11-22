<?php

namespace App\Livewire;

use App\Models\Promocion;
use App\Models\Servicio;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class ModalPromociones extends ModalComponent
{

    public Promocion $promocion;

    public $servicios = [];
    public $empleado_id;

    public function asignar_sevicio()
    {
        dd($this->servicios, $this->empleado_id, $this->promocion);
    }

    public function render()
    {
        $servicios = Servicio::select(DB::raw("descripcion as servicios"))
            ->where('asignacion', 'promocion')
            ->orderBy('servicios', 'asc')
            ->get();
        $array_servicios = $servicios->pluck('servicios');

        return view('livewire.modal-promociones', compact('array_servicios'));
    }
}
