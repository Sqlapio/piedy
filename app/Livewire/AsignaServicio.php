<?php

namespace App\Livewire;

use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Disponible;
use App\Models\Empleado;
use App\Models\Servicio;
use Filament\Notifications\Notification;
use Livewire\Component;
use App\Livewire\Citas;
use App\Models\DetalleAsignacion;
use App\Models\User;
use Barryvdh\Debugbar\Facades\Debugbar;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use LivewireUI\Modal\ModalComponent;
use WireUi\Traits\Actions;

use function Psy\debug;

class AsignaServicio extends ModalComponent
{
    use Actions;
    
    public $empleado_id;
    public Cita $cita;

    public $atr = 'mt-40';

    protected $listeners = [
        'selected',
    ];

    public function selected($value)
    {
        $count_disponible = Disponible::count();
        $this->empleado_id = $value;
    }

    public function asignar_tecnico()
    {

        try {

            $count_disponible = Disponible::count();
            $existe = Disponible::where('empleado_id', $this->empleado_id)->where('status', 'activo')->first();


            $cliente = Cliente::where('id', $this->cita->cliente_id)->first();
            $servicio = Servicio::where('id', $this->cita->servicio_id)->first();
            $empleado = User::where('id', $this->empleado_id)->first();

            $disponible = new Disponible();
            $disponible->cod_asignacion     = 'Pca-'.random_int(11111111, 99999999);
            $disponible->cliente_id         = $this->cita->cliente_id;
            $disponible->cliente            = $cliente->nombre . ' ' . $cliente->apellido;
            $disponible->empleado_id        = $this->empleado_id;
            $disponible->empleado           = $empleado->name;
            $disponible->area_trabajo       = $empleado->area_trabajo;
            $disponible->cod_servicio       = $servicio->cod_servicio;
            $disponible->servicio_id        = $this->cita->servicio_id;
            $disponible->servicio           = $servicio->descripcion;
            $disponible->servicio_categoria = $servicio->categoria;
            $disponible->costo              = $servicio->costo;

            Debugbar::info($disponible);


            if ($count_disponible == 8) 
            {
                Notification::make()
                    ->title('NOTIFICACION DE SISTEMA')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->iconColor('danger')
                    ->body('El servicio no puede ser asignado ya que todos los tecnicos se encuentran ocupados')
                    ->send();
                $this->forceClose()->closeModal();

            } else {

                if ($existe == null) 
                {

                    $disponible->save();

                    Notification::make()
                        ->title('Cliente asignado con éxito')
                        ->icon('heroicon-o-shield-check')
                        ->iconColor('danger')
                        ->body('El cliente será atendido por: ' . $disponible->empleado)
                        ->send();

                    $this->forceClose()->closeModal();

                    // Actualizamos status de la cita a 2 (asignada al tecnico)
                    Cita::where('id', $this->cita->id)
                        ->update([
                            'status' => 2
                        ]);

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

                    Debugbar::info($detalle_asignacion);

                    $detalle_asignacion->save();

                    $this->redirect('/citas');

                } else {
                    
                    Notification::make()
                        ->title('NOTIFICACION DE SISTEMA')
                        ->icon('heroicon-o-exclamation-triangle')
                        ->iconColor('danger')
                        ->body('Técnico no disponible')
                        ->send();
                    $this->forceClose()->closeModal();

                }
            }

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function cancel(){

        /**
         * Pregunta para cancelación de cita
         */
        $this->dialog()->confirm([
            'title'       => 'Confirmación',
            'description' => 'Esta seguro que desea cancelar la cita?',
            'acceptLabel' => 'Si',
            'method'      => 'delete',
            'params'      => 'deleted',
        ]);
    }

    public function delete(){
        try {
            
            $this->forceClose()->closeModal();

                    // Actualizamos status de la cita a 3 (cita cancelada)

                    Cita::where('id', $this->cita->id)
                        ->update([
                            'status' => 3
                        ]);

                    $this->redirect('/citas');

        } catch (\Throwable $th) {
            dd($th);
        }
        
    }

    public function render()
    {
        return view('livewire.asigna-servicio');
    }
}
