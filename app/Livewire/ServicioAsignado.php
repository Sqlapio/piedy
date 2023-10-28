<?php

namespace App\Livewire;

use App\Models\Disponible;
use App\Models\Empleado;
use App\Models\Servicio;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\Actions;

class ServicioAsignado extends Component
{

    use Actions;

    use WithPagination;
    
    public $extra = [];
    public $total_vista = '';
    public $buscar;

    public function total($value){
        $cod_asignacion = Disponible::where('id', $value)->where('status', 'activo')->first();
        dd($cod_asignacion);
    }

    public function store($value){
        $cod_asignacion = Disponible::where('id', $value)->where('status', 'activo')->first()->cod_asignacion;
        dd($cod_asignacion, $this->extra);
    }

    public function render()
    {
        $user_email = Auth::user()->email;

        $empleado = Empleado::where('email', $user_email)->first();
        

        $srv_asignado = Disponible::where('empleado_id', $empleado->id)->where('status', 'activo')->first();

        return view('livewire.servicio-asignado',[
            'data' => Servicio::Where('categoria', 'adicional')
            ->Where('descripcion', 'like', "%{$this->buscar}%")
               ->orderBy('id', 'desc')
               ->paginate(6),
            'data_user' => $srv_asignado
       ]);
    }
}
