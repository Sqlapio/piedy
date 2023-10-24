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
use LivewireUI\Modal\ModalComponent;

class AsignaServicio extends ModalComponent
{
    public $empleado_id;
    public Cita $cita;

    public $atr = 'mt-40';

    protected $listeners = [
        'selected'
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
            $empleado_id = Disponible::where('empleado_id', $this->empleado_id)->first();


            $cliente = Cliente::where('id', $this->cita->cliente_id)->first();
            $servicio = Servicio::where('id', $this->cita->servicio_id)->first();
            $empleado = Empleado::where('id', $this->empleado_id)->first();

            $disponible = new Disponible();

            $disponible->cliente_id = $this->cita->cliente_id;
            $disponible->cliente = $cliente->nombre . ' ' . $cliente->apellido;
            $disponible->empleado_id = $this->empleado_id;
            $disponible->empleado = $empleado->nombre . ' ' . $empleado->apellido;
            $disponible->servicio_id = $this->cita->servicio_id;
            $disponible->servicio = $servicio->descripcion;
            $disponible->costo = $servicio->costo;
            $disponible->duracion = $servicio->duracion_max;
            $disponible->finalizacion = Carbon::now('America/Caracas')->addMinutes($servicio->duracion_max)->format('H:i:s');
            $disponible->cubiculo_mesa = $servicio->cubiculo_mesa;

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

    public function render()
    {
        return view('livewire.asigna-servicio');
    }
}
