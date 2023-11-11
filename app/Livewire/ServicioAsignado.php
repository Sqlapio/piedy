<?php

namespace App\Livewire;

use App\Models\Disponible;
use App\Models\DetalleAsignacion;
use App\Models\Empleado;
use App\Models\Servicio;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\Actions;

class ServicioAsignado extends Component
{

    use Actions;

    use WithPagination;

    public $extra = [];

    public $total_vista;
    public $buscar;

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

    public function total()
    {
        $user = Auth::user();
        $data = Disponible::where('empleado_id', $user->id)->where('status', 'activo')->first();

        $valores = [];

        for ($i=0; $i < count($this->extra) ; $i++) {
            $costo = Servicio::where('id', $this->extra[$i])->first()->costo;
            array_push($valores, $costo);
        }

        $this->total_vista = $data->costo + array_sum($valores);

    }

    public function store($value)
    {
        $user = Auth::user();
        $data = Disponible::where('id', $value)->where('status', 'activo')->first();

        for ($i=0; $i < count($this->extra) ; $i++)
        {
            $data_servicios = Servicio::where('id', $this->extra[$i])->first();
            $detalle_asignacion = new DetalleAsignacion();
            $detalle_asignacion->cod_asignacion     = $data->cod_asignacion;
            $detalle_asignacion->cod_servicio       = $data->cod_servicio;
            $detalle_asignacion->empleado_id        = $data->empleado_id;
            $detalle_asignacion->empleado           = $data->empleado;
            $detalle_asignacion->cliente_id         = $data->cliente_id;
            $detalle_asignacion->cliente            = $data->cliente;
            $detalle_asignacion->servicio_id        = $data_servicios->id;
            $detalle_asignacion->servicio           = $data_servicios->descripcion;
            $detalle_asignacion->servicio_categoria = $data_servicios->categoria;
            $detalle_asignacion->costo              = $data_servicios->costo;
            $detalle_asignacion->fecha              = date('d-m-Y');
            $detalle_asignacion->save();
        }

        $this->notification()->success(
            $title = 'Operación exitosa !!',
            $description = 'El servicio fue cerrado correctamente.'
        );

        Disponible::where('empleado_id', $user->id)
        ->where('status', 'activo')
            ->update([
                'status' => 'cerrado'
            ]);

        redirect()->to('/dashboard');

    }

    public function render()
    {
        $user = Auth::user();
        
        return view('livewire.servicio-asignado',[
            'data' => Servicio::Where('categoria', 'principal')
            ->Where('descripcion', 'like', "%{$this->buscar}%")
               ->orderBy('id', 'desc')
               ->paginate(6),
            'data_user' => Disponible::where('empleado_id', $user->id)->where('status', 'activo')->first()
       ]);
    }
}
