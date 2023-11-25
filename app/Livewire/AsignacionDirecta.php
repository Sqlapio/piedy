<?php

namespace App\Livewire;

use App\Models\Cita;
use App\Models\Cliente;
use App\Models\DetalleAsignacion;
use App\Models\Disponible;
use App\Models\Servicio;
use App\Models\User;
use Barryvdh\Debugbar\Facades\Debugbar;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use LivewireUI\Modal\ModalComponent;
use WireUi\Traits\Actions;

class AsignacionDirecta extends ModalComponent
{
    use WithPagination;

    use Actions;

    #[Rule('required', message: 'Campo obligatorio')]
    public $empleado_id;

    #[Rule('required', message: 'Campo obligatorio')]
    public $servicio_id;

    #[Rule('required', message: 'Campo obligatorio')]
    public $cliente_id;


    public function asignar_tecnico()
    {
        $this->validate();

        try {

            $existe = Disponible::where('empleado_id', $this->empleado_id)->where('status', 'activo')->first();

            $cliente = Cliente::where('id', $this->cliente_id)->first();
            $servicio = Servicio::where('id', $this->servicio_id)->first();
            $empleado = User::where('id', $this->empleado_id)->first();

            $disponible = new Disponible();
            $disponible->cod_asignacion     = 'Pca-'.random_int(11111111, 99999999);
            $disponible->cliente_id         = $this->cliente_id;
            $disponible->cliente            = $cliente->nombre . ' ' . $cliente->apellido;
            $disponible->empleado_id        = $this->empleado_id;
            $disponible->empleado           = $empleado->name;
            $disponible->area_trabajo       = $empleado->area_trabajo;
            $disponible->cod_servicio       = $servicio->cod_servicio;
            $disponible->servicio_id        = $this->servicio_id;
            $disponible->servicio           = $servicio->descripcion;
            $disponible->servicio_categoria = $servicio->categoria;
            $disponible->costo              = $servicio->costo;

            if($existe == null)
            {
                $disponible->save();

                Notification::make()
                    ->title('Cliente asignado con éxito')
                    ->icon('heroicon-o-shield-check')
                    ->iconColor('danger')
                    ->body('El cliente será atendido por: ' . $disponible->empleado)
                    ->send();

                $this->forceClose()->closeModal();

                /**
                 * Guardamos la asignacion directa en la
                 * tabla de citas para llevar el control
                 * de la entrada de los clientes
                 */
                $user = Auth::user();

                $cita = new Cita();
                $cita->cod_cita = 'Pci-'.random_int(11111, 99999);
                $cita->fecha = date('d-m-Y');
                $cita->hora = date('HH:i');
                $cita->cliente_id = $this->cliente_id;
                $cita->servicio_id = $this->servicio_id;
                $cita->status = 2;
                $cita->responsable = $user->id;
                $cita->save();

                /**
                 * Cargamos el servicio principal asigando
                 * en la tabla de detalle de asignacion
                 */
                $detalle_asignacion = new DetalleAsignacion();
                $detalle_asignacion->cod_asignacion     = $disponible->cod_asignacion;
                $detalle_asignacion->cod_servicio       = $disponible->cod_servicio;
                $detalle_asignacion->empleado_id        = $disponible->empleado_id;
                $detalle_asignacion->empleado           = $disponible->empleado;
                $detalle_asignacion->cliente_id         = $disponible->cliente_id;
                $detalle_asignacion->cliente            = $disponible->cliente;
                $detalle_asignacion->servicio_id        = $disponible->servicio_id;
                $detalle_asignacion->servicio           = $servicio->descripcion;
                $detalle_asignacion->servicio_categoria = $servicio->categoria;
                $detalle_asignacion->costo              = $disponible->costo;
                $detalle_asignacion->fecha              = date('d-m-Y');
                /** Logica para agregar promocion asociada al servicio */
                
                $detalle_asignacion->save();

                $this->redirect('/cabinas');

            }else{

                $this->notification([
                    'title'       => 'Acción no permitida!',
                    'description' => 'El tecnico debe cerrar el servicio anterior.',
                    'icon'        => 'error'
                ]);

            }

        } catch (\Throwable $th) {
            dd($th);
        }

    }

    // public static function modalMaxWidth(): string
    // {
    //     return 'xl';
    // }

    public function render()
    {
        return view('livewire.asignacion-directa');
    }
}
