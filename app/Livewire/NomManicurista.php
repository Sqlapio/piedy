<?php

namespace App\Livewire;

use App\Models\Comision;
use App\Models\Membresia;
use App\Models\MovimientoMembresia;
use App\Models\NomManicurista as ModelsNomManicurista;
use App\Models\TasaBcv;
use App\Models\User;
use App\Models\VentaServicio;
use Livewire\Attributes\Rule;
use Livewire\Component;

class NomManicurista extends Component
{
    public array $asignacion_bolivares;
    public array $deduccion_dolares;

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

    public function store()
    {
        $this->validate();

        try {
            $tasa_bcv = TasaBcv::where('id', 1)->first()->tasa;
            //code...
            $data = User::where('tipo_servicio_id', '1')->where('status', '1')->get();

            //Total de membresias vendidas
            $membresias = Membresia::all()->SUM('monto');

            $comision = Comision::where('aplicacion', 'membresia')->where('beneficiario', 'empleado')->where('status', 1)->first();

            //Calclo del 40% del totalm de las membresias vendidas para ser repartido equitativamente entre las manicuristas
            $_40porcen = ($comision->porcentaje * $membresias) / 100;

            //Total de membresiaatendidas en el rabgo de fecha seleccionado
            $sum_membresia = MovimientoMembresia::where('descripcion', '=', 'consumo en tienda')->count();

            foreach($data as $item)
            {

                $nomina = new ModelsNomManicurista();
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

                //Calculo de porcentaje de acuerdo con el total de membresias atendias
                $emp_membresia = MovimientoMembresia::where('descripcion', '=', 'consumo en tienda')
                ->where('empleado_id', $item->id)
                ->count();

                $_porcen_representacion = ($emp_membresia * 100) / $sum_membresia;
                $total_comision = ($_porcen_representacion * $_40porcen) / 100;

                $nomina->comision_membresias = $total_comision * $tasa_bcv;
                $nomina->fecha_ini = $this->desde;
                $nomina->fecha_fin = $this->hasta;
                $nomina->total_dolares = $nomina->total_comision_dolares - $nomina->deducciones_dolares;
                $nomina->total_bolivares = $nomina->total_comision_bolivares + $nomina->asignaciones_bolivares + $nomina->comision_membresias;
                $nomina->save();

            }

        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function render()
    {
        $data = User::where('tipo_servicio_id', '1')->where('status', '1')->get();
        return view('livewire.nom-manicurista', compact('data'));
    }
}
