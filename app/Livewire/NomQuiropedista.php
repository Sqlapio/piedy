<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class NomQuiropedista extends Component
{
    public array $asignacion_dolares;
    public array $asignacion_bolivares;
    public array $deduccion_dolares;
    public array $deduccion_bolivares;

    public $desde;
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

    public function store(){
        dd($this->asignacion_dolares, $this->asignacion_bolivares);
        dd($this->asignacion_dolares, $this->asignacion_bolivares, $this->deduccion_dolares, $this->deduccion_bolivares);
    }
    public function render()
    {
        $data = User::where('tipo_servicio_id', '2')->where('status', '1')->get();
        return view('livewire.nom-quiropedista', compact('data'));
    }
}
