<?php

namespace App\Livewire;

use App\Models\DetalleAsignacion;
use App\Models\Disponible;
use App\Models\Servicio;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\Actions;

class AgregraServicios extends Component
{

    use Actions;

    use WithPagination;

    public $buscar;

    public function render(Request $request)
    {
        /**
         * El codigo es tomado de la variables de sesion
         * del usuario
         *
         * @param $codigo
         */
        $codigo = $request->session()->all();

        $data = Disponible::where('cod_asignacion', $codigo['cod_asignacion'])->first();

        $detalle = DetalleAsignacion::where('cod_asignacion', $codigo['cod_asignacion'])
            ->where('status', '1')
            ->where('servicio_categoria', 'principal')
            ->get();

        $total = DB::table('detalle_asignacions')
            ->select(DB::raw('SUM(costo) as total'))
            ->where('cod_asignacion', $codigo['cod_asignacion'])
            ->where('status', '1')
            ->first();

        $total_vista = $total->total;

        $servicios_adicionales = Servicio::Where('categoria', 'principal')
            ->Where('descripcion', 'like', "%{$this->buscar}%")
            ->orderBy('id', 'desc')
            ->simplePaginate(4);
            
        return view('livewire.agregra-servicios', compact('data', 'detalle', 'total_vista', 'servicios_adicionales'));
    }
}
