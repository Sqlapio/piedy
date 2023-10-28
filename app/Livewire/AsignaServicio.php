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
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use LivewireUI\Modal\ModalComponent;
use WireUi\Traits\Actions;

class AsignaServicio extends ModalComponent
{
    use Actions;
    
    public $empleado_id;
    public $cubiculo_mesa;
    public Cita $cita;

    public $atr = 'mt-40';

    protected $listeners = [
        'selected',
    ];

    public function selected($value, $value2)
    {
        $count_disponible = Disponible::count();
        $this->empleado_id = $value;
    }

    public function asignar_tecnico()
    {

        try {

            $count_disponible = Disponible::count();
            $empleado_id = Disponible::where('empleado_id', $this->empleado_id)->first();


            $cliente = Cliente::where('id', $this->cita->cliente_id)->first();
            $servicio = Servicio::where('id', $this->cita->servicio_id)->first();
            $empleado = Empleado::where('id', $this->empleado_id)->first();

            $disponible = new Disponible();

            $disponible->cod_asignacion = 'Pca-'.random_int(11111111, 99999999);
            $disponible->cliente_id     = $this->cita->cliente_id;
            $disponible->cliente        = $cliente->nombre . ' ' . $cliente->apellido;
            $disponible->empleado_id    = $this->empleado_id;
            $disponible->empleado       = $empleado->nombre . ' ' . $empleado->apellido;
            $disponible->area_trabajo   = $empleado->area_trabajo;
            $disponible->cod_servicio   = $servicio->cod_servicio;
            $disponible->servicio_id    = $this->cita->servicio_id;
            $disponible->servicio       = $servicio->descripcion;
            $disponible->costo          = $servicio->costo;
            // $disponible->duracion = $servicio->duracion_max;
            // $disponible->finalizacion = Carbon::now('America/Caracas')->addMinutes($servicio->duracion_max)->format('H:i:s');
            $disponible->cubiculo_mesa  = $servicio->cubiculo_mesa;

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

                if ($empleado_id == null) 
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

                    // Actualizamos status de la cita a 4 (cita eliminada)

                    Cita::where('id', $this->cita->id)
                        ->update([
                            'status' => 4
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
