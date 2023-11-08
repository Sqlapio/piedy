<?php

namespace App\Livewire;

use App\Http\Controllers\NotificacionesController;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Horario;
use Barryvdh\Debugbar\Facades\Debugbar;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use LivewireUI\Modal\ModalComponent;
use WireUi\Traits\Actions;

class AgendarCita extends ModalComponent
{
    use WithPagination;

    use Actions;
    
    public Horario $horario;

    #[Rule('required')]
    public $fecha;

    #[Rule('required')]
    public $cliente_id;

    #[Rule('required')]
    public $servicio_id;

    protected $messages = [

        'fecha'         => 'Campo requerido',
        'cliente_id'    => 'Campo requerido',
        'servicio_id'   => 'Campo requerido',

    ];

    public function store()
    {
        try {

            $user = Auth::user();

            $cita = new Cita();
            $cita->cod_cita = 'Pci-'.random_int(11111, 99999);
            $cita->fecha = $this->fecha;
            $cita->hora = $this->horario->hora;
            $cita->cliente_id = $this->cliente_id;
            $cita->servicio_id = $this->servicio_id;
            $cita->responsable = $user->id;

            $citas = Cita::where('cliente_id', $cita->cliente_id)->latest()->first();
            dd($citas, $this->horario->hora);
            if($citas != null)
            {
                if ($citas->fecha == $this->fecha && $citas->hora == $this->horario->hora) {

                    Notification::make()
                        ->title('Ya posee una cita')
                        ->icon('heroicon-o-exclamation-triangle')
                        ->iconColor('danger')
                        ->body('Por favor intente agendar en horas diferentes.')
                        ->send();
                    
                } else {
                    $cita->save();

                    $this->reset();

                    $this->forceClose()->closeModal();

                    $this->dialog()->success(
                        $title = 'Cliente agendado',
                        $description = 'El cliente fue agendado de forma exitosa'
                    );
    
                    $cliente = Cliente::where('id', $cita->cliente_id)->first();
                    $type = 'cliente';
                    
                    $mailData = [
                        'cliente_email' => $cliente->email,
                        'cliente_fullname' => $cliente->nombre.' '.$cliente->apellido,
                        'fecha_cita' => Carbon::createFromFormat('Y-m-d', $cita->fecha)->format('d-m-Y'),
                        
                        'hora_cita' => Carbon::createFromFormat('H:i', $cita->hora)->timezone('America/Caracas')->format('h:i A'),
                        'servicio' => $cita->servicio->descripcion,
                        'costo' => $cita->servicio->costo,
    
                    ];
    
                    NotificacionesController::notification($mailData, $type);

    
                }
            }else{

                $cita->save();
                
                $this->reset();

                $this->forceClose()->closeModal();
    
                    $this->dialog()->success(
                        $title = 'Cliente agendado',
                        $description = 'El cliente fue agendado de forma exitosa'
                    );
    
                    $cliente = Cliente::where('id', $cita->cliente_id)->first();
                    $type = 'cliente';
                    
                    $mailData = [
                        'cliente_email' => $cliente->email,
                        'cliente_fullname' => $cliente->nombre.' '.$cliente->apellido,
                        'fecha_cita' => Carbon::createFromFormat('Y-m-d', $cita->fecha)->format('d-m-Y'),
                        // 'hora_cita' => Carbon::createFromFormat('H:i', $this->horario->hora)->timezone('America/Caracas')->format('h:i A'),
                        'hora_cita' => Carbon::createFromFormat('H:i', $cita->hora)->timezone('America/Caracas')->format('h:i A'),
                        'servicio' => $cita->servicio->descripcion,
                        'costo' => $cita->servicio->costo,
    
                    ];
    
                    NotificacionesController::notification($mailData, $type);

                    $this->forceClose()->closeModal();

                    $this->redirect('/citas');
            }

        } catch (\Throwable $th) {
            dd($th);
        }
    }
    
    public function render()
    {
        return view('livewire.agendar-cita');
    }
}
