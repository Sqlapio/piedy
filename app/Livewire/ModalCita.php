<?php

namespace App\Livewire;

use App\Models\Cita;
use App\Models\DetalleAsignacion;
use App\Models\Disponible;
use App\Models\Servicio;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Rule;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class ModalCita extends ModalComponent
{

    public Cita $cita;

    #[Rule('required', message: 'Campo obligatorio')]
    public $empleado_id;

    #[Rule('required', message: 'Campo obligatorio')]
    public $servicio_id;

    public function mount()
    {
        if($this->cita->cliente_id == null)
        {
            $this->redirect('/clientes');
        }
    }

    public function asignar_tecnico()
    {

        $this->validate();

        try {

                $existe = Disponible::where('empleado_id', $this->empleado_id)->where('status', 'activo')->first();

                $servicio = Servicio::where('id', $this->servicio_id)->first();

                $empleado = User::where('id', $this->empleado_id)->first();

                $disponible = new Disponible();
                $disponible->cod_asignacion     = 'Pca-'.random_int(11111111, 99999999);
                $disponible->cliente_id         = $this->cita->cliente_id;
                $disponible->cliente            = $this->cita->cliente;
                $disponible->empleado_id        = $this->empleado_id;
                $disponible->empleado           = $empleado->name;
                $disponible->area_trabajo       = $empleado->area_trabajo;
                $disponible->cod_servicio       = $servicio->cod_servicio;
                $disponible->servicio_id        = $this->servicio_id;
                $disponible->servicio           = $servicio->descripcion;
                $disponible->servicio_categoria = $servicio->categoria;
                $disponible->servicio_asignacion= $servicio->asignacion;
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
                    $detalle_asignacion->save();

                    /**
                     * Actualizamos la cita
                     */
                    DB::table('citas')
                        ->where('id', $this->cita->id)
                        ->update([
                            'status' => 2
                        ]);

                    $this->forceClose()->closeModal();

                    $this->redirect('/cabinas');

                }else{

                    Notification::make()
                        ->title('Acción no permitida!!')
                        ->icon('heroicon-o-shield-check')
                        ->iconColor('danger')
                        ->body('El tecnico debe cerrar el servicio anterior')
                        ->send();

                }


            } catch (\Throwable $th) {
                Notification::make()
                    ->title('Notificación del Sistema')
                    ->icon('heroicon-o-shield-check')
                    ->iconColor('danger')
                    ->body($th->getMessage())
                    ->send();
            }

    }

    public function render()
    {
        return view('livewire.modal-cita');
    }
}
