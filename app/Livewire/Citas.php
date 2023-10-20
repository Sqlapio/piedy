<?php

namespace App\Livewire;

use App\Http\Controllers\NotificacionesController;
use App\Models\Cita;
use App\Models\Cliente;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule; 

class Citas extends Component
{
    use WithPagination;
    
    #[Rule('required')]
    public $fecha;

    #[Rule('required')]
    public $hora;

    #[Rule('required')]
    public $cliente_id;

    #[Rule('required')]
    public $servicio_id;
    // public $empleado_id;

    public $ocultar = 'hidden';
    public $botton_agendar_cita = '';

    public bool $myModal = false;

    protected $messages = [

        'fecha'         => 'Campo requerido',
        'hora'          => 'Campo requerido',
        'cliente_id'    => 'Campo requerido',
        'servicio_id'   => 'Campo requerido',

    ];

    public function mostrar(){
        $this->ocultar = '';
        $this->botton_agendar_cita = 'hidden';
    }

    public function store() 
    {

        $this->validate(); 

        try {

            $user = Auth::user();

            $cita = new Cita();
            $cita->cod_cita = 'Pci-'.random_int(11111, 99999);
            $cita->fecha = $this->fecha;
            $cita->hora = $this->hora;
            $cita->cliente_id = $this->cliente_id;
            $cita->servicio_id = $this->servicio_id;
            // $cita->empleado_id = $this->empleado_id;
            $cita->responsable = $user->id;

            $citas = Cita::where('cliente_id', $cita->cliente_id)->latest()->first();
            if($citas != null)
            {
                if ($citas->fecha == $cita->fecha && $citas->hora == $cita->hora) {

                    Notification::make()
                        ->title('Ya posee una cita')
                        ->icon('heroicon-o-exclamation-triangle')
                        ->iconColor('danger')
                        ->body('Por favor intente agendar en horas diferentes.')
                        ->send();
                    
                } else {
                    $cita->save();

                    $this->reset();
    
                    Notification::make()
                        ->title('Cita agendada con éxito')
                        ->icon('heroicon-o-document-text')
                        ->iconColor('success')
                        ->send();
    
                    $cliente = Cliente::where('id', $cita->cliente_id)->first();
                    $type = 'cliente';
                    
                    $mailData = [
                        'cliente_email' => $cliente->email,
                        'cliente_fullname' => $cliente->nombre.' '.$cliente->apellido,
                        'fecha_cita' => $cita->fecha,
                        'hora_cita' => $cita->hora,
                        // 'empleado_cita' => $cita->get_empleado->nombre.' '.$cita->get_empleado->apellido,
                        'servicio' => $cita->get_servicio->descripcion,
                        'costo' => $cita->get_servicio->costo,
    
                    ];
    
                    NotificacionesController::notification($mailData, $type);
    
                }
            }else{
                
                $cita->save();

                Notification::make()
                    ->title('Cita agendada con éxito')
                    ->icon('heroicon-o-document-text')
                    ->iconColor('success')
                    ->send();
            }

            
                
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function render()
    {
        $date = date('Y-m-d');
        return view('livewire.citas', [
            'data' => Cita::where('fecha', $date)->get()
        ]);
    }
}
