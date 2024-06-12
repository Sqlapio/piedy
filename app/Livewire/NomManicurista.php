<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Attributes\Rule;
use Livewire\Component;

class NomManicurista extends Component
{
    public array $asignacion_dolares;
    public array $asignacion_bolivares;
    public array $deduccion_dolares;
    public array $deduccion_bolivares;

    #[Rule('required', message: 'Campo obligatorio')]
    public $desde;

    #[Rule('required', message: 'Campo obligatorio')]
    public $hasta;

    public function conver_asignacion_dolares($id)
    {
        $this->asignacion_dolares[$id] = number_format(floatval(($this->asignacion_dolares[$id]) / 100), 2, ',', '.');
    }

    public function conver_asignacion_bolivares($id)
    {
        $this->asignacion_bolivares[$id] = number_format(floatval(($this->asignacion_bolivares[$id]) / 100), 2, ',', '.');
    }

    public function conver_deduccion_dolares($id)
    {
        $this->deduccion_dolares[$id] = number_format(floatval(($this->deduccion_dolares[$id]) / 100), 2, ',', '.');
    }

    public function conver_deduccion_bolivares($id)
    {
        $this->deduccion_bolivares[$id] = number_format(floatval(($this->deduccion_bolivares[$id]) / 100), 2, ',', '.');
    }

    public function render()
    {
        $data = User::where('tipo_servicio_id', '1')->where('status', '1')->get();
        return view('livewire.nom-manicurista', compact('data'));
    }
}
