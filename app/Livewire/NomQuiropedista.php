<?php

namespace App\Livewire;

use App\Models\NomQuiropedista as ModelsNomQuiropedista;
use App\Models\User;
use App\Models\VentaServicio;
use Livewire\Attributes\Rule;
use Livewire\Component;

class NomQuiropedista extends Component
{
    // public array $asignacion_dolares;
    public array $asignacion_bolivares;
    public array $deduccion_dolares;
    // public array $deduccion_bolivares;

    #[Rule('required', message: 'Campo obligatorio')]
    public $desde;

    #[Rule('required', message: 'Campo obligatorio')]
    public $hasta;

    public function conver_asignacion_bolivares($id)
    {
        $this->asignacion_bolivares[$id] = number_format(floatval(($this->asignacion_bolivares[$id]) / 100), 2, ',', '.');
    }

    public function conver_deduccion_dolares($id)
    {
        $this->deduccion_dolares[$id] = number_format(floatval(($this->deduccion_dolares[$id]) / 100), 2, ',', '.');
    }

    public function accion($value)
    {
        if($value == 1){
            $this->redirect('/n/q');
        }
        if($value == 2){
            $this->redirect('/n/m');
        }
        if($value == 3){
            $this->redirect('/n/q');
        }
        if($value == 4){
            $this->redirect('/n/e');
        }
        if($value == 5){
            $this->redirect('/empleados');
        }
    }

    public function store(){

        $this->validate();

        try {

            $data = User::where('tipo_servicio_id', '2')->where('status', '1')->get();

            foreach($data as $item){

                $nomina = new ModelsNomQuiropedista();

                $nomina->user_id = $item->id;
                $nomina->name = $item->name;
                $nomina->total_servicios = VentaServicio::where('empleado_id', $item->id)->whereBetween('created_at', [$this->desde.'.000', $this->hasta.'.000'])->count();

                //Promedio de duracion de los servicios
                $total_servicios = VentaServicio::where('empleado_id', $item->id)->whereBetween('created_at', [$this->desde.'.000', $this->hasta.'.000'])->count();
                $sum_servicios = VentaServicio::where('empleado_id', $item->id)->whereBetween('created_at', [$this->desde.'.000', $this->hasta.'.000'])->sum('duracion');
                $promedio  = $sum_servicios / $total_servicios;
                $nomina->promedio_duracion_servicios    = $promedio;

                $nomina->total_comision_dolares         = VentaServicio::where('empleado_id', $item->id)->whereBetween('created_at', [$this->desde.'.000', $this->hasta.'.000'])->sum('comision_dolares');
                $nomina->total_comision_bolivares       = VentaServicio::where('empleado_id', $item->id)->whereBetween('created_at', [$this->desde.'.000', $this->hasta.'.000'])->sum('comision_bolivares');
                $nomina->total_propina_bsd              = VentaServicio::where('empleado_id', $item->id)->whereBetween('created_at', [$this->desde.'.000', $this->hasta.'.000'])->sum('propina_bsd');

                //Recorro el array de las asignaciones en bolivares
                for ($i=0; $i < count($this->asignacion_bolivares); $i++) {
                    # code...
                    $_bolivares = $this->asignacion_dolares[$item->id];
                    $nomina->asignaciones_bolivares         = str_replace(',', '.', str_replace('.', '', $_bolivares));
                }

                //Recorro el array de las asignaciones en bolivares
                for ($i=0; $i < count($this->deduccion_dolares); $i++) {
                    # code...
                    $_dedu_dolares = $this->deduccion_dolares[$item->id];
                    $nomina->deducciones_dolares            = str_replace(',', '.', str_replace('.', '', $_dedu_dolares));
                }

                $nomina->fecha_ini = $this->desde;
                $nomina->fecha_fin = $this->hasta;
                $nomina->total_dolares = ($nomina->total_comision_dolares + $nomina->asignaciones_dolares) - $nomina->deducciones_dolares;
                $nomina->total_bolivares = ($nomina->total_comision_bolivares + $nomina->asignaciones_bolivares) - $nomina->deducciones_bolivares;
                $nomina->save();

            }

            $this->reset();

            $this->dispatch('nomina-calculada-quiropedista');
            //code...
        } catch (\Throwable $th) {
            throw $th;
        }


    }
    public function render()
    {
        $data = User::where('tipo_servicio_id', '2')->where('status', '1')->get();
        return view('livewire.nom-quiropedista', compact('data'));
    }
}
