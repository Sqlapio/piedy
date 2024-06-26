<?php

namespace App\Livewire;

use App\Http\Controllers\NotificacionesController;
use App\Http\Controllers\UtilsController;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\Actions;
use LivewireUI\Modal\ModalComponent;

class ModalAgenda extends ModalComponent
{
    use WithPagination;

    use Actions;

    public $fecha;
    public $mes;

    public $nuevo = 'hidden';
    public $ocultar = '';

    public $correo;

    #[Validate('required')]
    public $telefono;

    #[Validate('required')]
    public $hora;

    public $cliente;
    public $cliente_id;

    protected $messages = [
        'hora'         => 'Campo requerido',
        'correo'       => 'Campo requerido',
        'nom_ape'      => 'Campo requerido',
    ];

    public function mostrar_nuevo()
    {
        $this->nuevo = '';
        $this->ocultar = 'hidden';
    }

    public function store()
    {

        try {

            $dia = UtilsController::agenda($this->fecha, $this->mes);
            $citas = new Cita();
            $citas->cod_cita = 'Pci-'.random_int(11111, 99999);
            $fecha_formateada = date('Y-m-d', strtotime(date('Y-'.$this->mes.'-'.$this->fecha+1)));
            // $cliente_existe = Cita::where('cliente_id', $this->cliente_id)->where('fecha_formateada', $fecha_formateada)->first();

            /**Restriccion para horario de atencion al cliente */
            if($this->hora > '22:00' || $this->hora < '10:00'){
                throw new Exception("La hora debe estar entre las 10:00am y las 22:00pm. Por favor intente nuevamente");
            }

            /**Restriccion para dia anterior */
            if($fecha_formateada < date('Y-m-d')){
                throw new Exception("No puede agendar citas en días anteriores a la fecha actual. Por favor intente con otro dia");
            }

            if(isset($this->cliente_id)){

                $cliente_existe = Cita::where('cliente_id', $this->cliente_id)->where('fecha_formateada', $fecha_formateada)->first();
                if(isset($cliente_existe) && $cliente_existe->hora == date("h:i a", strtotime($this->hora))){
                    throw new Exception("No puede agendar citas al mismo cliente a la misma hora. Debe agendar en otra hora");
                }else{
                    $cliente = Cliente::find($this->cliente_id);
                    $citas->cliente_id = $cliente->id;
                    $citas->correo = $cliente->email;
                    $citas->telefono = $cliente->telefono;
                    $citas->cliente = $cliente->nombre.' '.$cliente->apellido;
                }
            }else{

                $citas->correo = $this->correo;
                $citas->cliente = $this->cliente;
                $citas->telefono = $this->telefono;
            }


            $citas->hora = date("h:i a", strtotime($this->hora));
            $citas->fecha = $dia;
            $citas->fecha_formateada = $fecha_formateada;
            $citas->responsable = Auth::user()->name;
            $citas->status = 1;
            $citas->save();

            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->body('La cita fue agendada con éxito')
                ->send();

            $cliente_citado = Cita::where('id', $citas->id)->first();
            $type = 'cliente';
            $mailData = [
                'cliente_email' => $cliente_citado->correo,
                'cliente_fullname' => $cliente_citado->cliente,
                'fecha_cita' => $cliente_citado->fecha,
                'hora_cita' => $cliente_citado->hora,
                'telefono' => $cliente_citado->telefono,
            ];

            if(isset($this->cliente_id)){
                /**Notificacion por Whatsapp */
                NotificacionesController::notificacion_cita_wp($mailData);

                /**Notificacion por correo */
                NotificacionesController::notification($mailData, $type);

            }else{
                /**Notificacion por Whatsapp */
                NotificacionesController::notificacion_cita_wp($mailData);
            }

            $this->reset();

            $this->closeModal();

            redirect(route('citas'));

        } catch (\Throwable $th) {
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
    }

    public function render()
    {
        $dia = UtilsController::agenda($this->fecha, $this->mes);
        return view('livewire.modal-agenda', compact('dia'));
    }
}

