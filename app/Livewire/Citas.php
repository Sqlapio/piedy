<?php

namespace App\Livewire;

use App\Http\Controllers\NotificacionesController;
use App\Http\Controllers\UtilsController;
use App\Http\Controllers\AgendaController;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Horario;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use WireUi\Traits\Actions;
use Carbon\Carbon;
use App\Models\Agenda;
use App\Models\Servicio;
use App\Models\User;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class Citas extends Component implements HasForms, HasActions
{
    use WithPagination;

    use Actions;

    use InteractsWithActions;
    use InteractsWithForms;

    #[Rule('required')]
    public $fecha;

    #[Rule('required')]
    public $hora;

    #[Rule('required')]
    public $cliente_id;

    #[Rule('required')]
    public $servicio_id;

    public $opcion, $inicio, $fin;

    public $mes;
    public $largo;
    public $scroll;

    public Cita $cita;

    public $ocultar = 'hidden';
    public $botton_agendar_cita = '';

    public bool $myModal = false;

    public function mount()
    {
        $this->mes = Carbon::now()->format('m');
        $this->opcion = 'mes';
    }

    public function CreateAction(): Action
    {
        return Action::make('create')
        ->modalHeading(false)
        ->form([
            Section::make('Formulario de Citas')
                ->description('Debe llenar los campos de forma correcta. Campos Requeridos(*)')
                ->icon('heroicon-o-swatch')
                ->schema([
                    //Seleccion de servicio
                    Select::make('cliente_id')
                        ->label('Seleccion del Tecnico')
                        ->prefixIcon('heroicon-o-swatch')
                        ->options(Cliente::all()->pluck('nombre', 'id'))
                        ->searchable()
                        ->required(),
                    Select::make('servicio_id')
                        ->label('Seleccion del Tecnico')
                        ->prefixIcon('heroicon-o-swatch')
                        ->options(Servicio::where('sucursal_id', Auth::user()->sucursal_id)->pluck('descripcion', 'id'))
                        ->searchable()
                        ->required(),
                    Select::make('user_id')
                        ->label('Seleccion del Servicio')
                        ->prefixIcon('heroicon-o-swatch')
                        ->options(User::where('sucursal_id', Auth::user()->sucursal_id)->whereBetween('rol_id', [1,2])->pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                    Select::make('horario')
                        ->label('Hora de la Cita')
                        ->prefixIcon('heroicon-o-swatch')
                        ->options(Horario::all()->pluck('hora', 'id'))
                        ->searchable()
                        ->required(),
                ])
        ])
        ->action(function (array $arguments, array $data) {

            $array = UtilsController::agenda($arguments['mes'], $this->opcion);

            $agendar = AgendaController::agendar_cita($data['cliente_id'], $data['servicio_id'], $data['user_id'], $array[$arguments['id']], $data['horario']);

            if($agendar)
            {
                redirect(route('citas'));

            }else{
                Notification::make()
                ->title('Notificacion')
                ->icon('heroicon-o-exclamation-triangle')
                ->iconColor('danger')
                ->body('No se pudo agendar la cita, por favor vuelva a intentarlo')
                ->send();
            }

        });
    }

    public function AsignarAction(): Action
    {
        return Action::make('asignar')
        ->modalHeading(false)
        ->form([
            Section::make('Formulario de Citas')
                ->description('Debe llenar los campos de forma correcta. Campos Requeridos(*)')
                ->icon('heroicon-o-swatch')
                ->schema([
                    //Seleccion de servicio
                    Select::make('cliente_id')
                        ->label('Seleccion del Tecnico')
                        ->prefixIcon('heroicon-o-swatch')
                        ->options(Cliente::all()->pluck('nombre', 'id'))
                        ->searchable()
                        ->required(),
                    Select::make('servicio_id')
                        ->label('Seleccion del Tecnico')
                        ->prefixIcon('heroicon-o-swatch')
                        ->options(Servicio::where('sucursal_id', Auth::user()->sucursal_id)->pluck('descripcion', 'id'))
                        ->searchable()
                        ->required(),
                    Select::make('user_id')
                        ->label('Seleccion del Servicio')
                        ->prefixIcon('heroicon-o-swatch')
                        ->options(User::where('sucursal_id', Auth::user()->sucursal_id)->whereBetween('rol_id', [1,2])->pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                    Select::make('horario')
                        ->label('Hora de la Cita')
                        ->prefixIcon('heroicon-o-swatch')
                        ->options(Horario::all()->pluck('hora', 'id'))
                        ->searchable()
                        ->required(),
                ])
        ])
        ->action(function (array $arguments, array $data) {

            $array = UtilsController::agenda($arguments['mes'], $this->opcion);

            $agendar = AgendaController::agendar_cita($data['cliente_id'], $data['servicio_id'], $data['user_id'], $array[$arguments['id']], $data['horario']);

            if($agendar)
            {
                redirect(route('citas'));

            }else{
                Notification::make()
                ->title('Notificacion')
                ->icon('heroicon-o-exclamation-triangle')
                ->iconColor('danger')
                ->body('No se pudo agendar la cita, por favor vuelva a intentarlo')
                ->send();
            }

        });
    }

    public function EliminarAction(): Action
    {
        return Action::make('eliminar')
        ->requiresConfirmation()
        ->action(fn () => $this->cita->delete());
    }

    protected $messages = [
        'fecha'         => 'Campo requerido',
        'hora'          => 'Campo requerido',
        'cliente_id'    => 'Campo requerido',
        'servicio_id'   => 'Campo requerido',
    ];

    public function mostrar()
    {
        $this->ocultar = '';
        $this->botton_agendar_cita = 'hidden';
    }

    public function asignar()
    {
        redirect()->to('/clientes');
    }

    public function store()
    {

        $this->validate();

        try {

            $user = Auth::user();

            $cita = new Cita();
            $cita->cod_cita     = 'Pci-'.random_int(11111, 99999);
            $cita->fecha        = $this->fecha;
            $cita->hora         = $this->hora;
            $cita->cliente_id   = $this->cliente_id;
            $cita->servicio_id  = $this->servicio_id;
            $cita->responsable  = $user->id;

            $citas = Cita::where('cliente_id', $cita->cliente_id)->latest()->first();

            if($citas != null)
            {
                if ($citas->fecha == $this->fecha && $citas->hora == $this->hora) {

                    Notification::make()
                        ->title('Ya posee una cita')
                        ->icon('heroicon-o-exclamation-triangle')
                        ->iconColor('danger')
                        ->body('Por favor intente agendar en horas diferentes.')
                        ->send();

                } else {
                    $cita->save();

                    $this->reset();

                    $this->dialog()->success(
                        $title = 'Cliente agendado',
                        $description = 'El cliente fue agendado de forma exitosa'
                    );

                    $cliente = Cliente::where('id', $cita->cliente_id)->first();
                    $type = 'cliente';

                    $mailData = [
                        'cliente_email'     => $cliente->email,
                        'cliente_fullname'  => $cliente->nombre.' '.$cliente->apellido,
                        'fecha_cita'        => Carbon::createFromFormat('Y-m-d', $cita->fecha)->format('d-m-Y'),
                        'hora_cita'         => Carbon::createFromFormat('H:i', $cita->hora)->timezone('America/Caracas')->format('h:i A'),
                        'servicio'          => $cita->servicio->descripcion,
                        'costo'             => $cita->servicio->costo,
                    ];

                    NotificacionesController::notification($mailData, $type);

                }
            }else{

                $cita->save();

                $this->reset();

                    $this->dialog()->success(
                        $title = 'Cliente agendado',
                        $description = 'El cliente fue agendado de forma exitosa'
                    );

                    $cliente = Cliente::where('id', $cita->cliente_id)->first();
                    $type = 'cliente';

                    $mailData = [
                        'cliente_email'     => $cliente->email,
                        'cliente_fullname'  => $cliente->nombre.' '.$cliente->apellido,
                        'fecha_cita'        => $cita->fecha,
                        'hora_cita'         => $cita->hora,
                        // 'empleado_cita' => $cita->get_empleado->nombre.' '.$cita->get_empleado->apellido,
                        'servicio'          => $cita->servicio->descripcion,
                        'costo'             => $cita->servicio->costo,

                    ];

                    // NotificacionesController::notification($mailData, $type);
            }

        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function filtro()
    {
        if($this->opcion == 'semana'){
            $this->inicio = now()->startOfWeek()->month($this->mes);
            $this->fin = now()->endOfWeek()->month($this->mes);
        }
        if($this->opcion == 'mes'){
            $this->inicio = now()->startOfMonth()->month($this->mes);
            $this->fin = now()->endOfMonth()->month($this->mes);
        }
        if($this->opcion == 'dia'){
            $this->inicio = now()->startOfDay()->month($this->mes);
            $this->fin = now()->endOfDay()->month($this->mes);
        }
    }

    public function div_largo(){
        if($this->opcion == 'mes')
        {
            $this->largo = 'h-64';
            $this->scroll = 'h-96';
        }

        if($this->opcion == 'semana')
        {
            $this->largo = 'h-[450px]';
            $this->scroll = 'h-[450px]';
        }

        if($this->opcion == 'dia')
        {
            $this->largo = '';
        }
    }

    public function render()
    {
        $this->filtro();

        $this->div_largo();

        $fecha = date('Y-m');

        $start = $this->inicio;
        $end = $this->fin;

        $data_citas = Cita::where('status', 1)->where('fecha_formateada', 'like', '%'.$fecha.'%')->get();
        // dd($data_citas);
        $datas = Trend::model(Cita::class)
                ->between(
                    $start,
                    $end,
                )
                ->perDay()
                ->count();
        $array = $datas->map(fn (TrendValue $value) => Carbon::parse($value->date)->isoFormat('dddd, D MMM'))->toArray();

        return view('livewire.citas', [
            'array' => $array,
            'data_citas' => $data_citas
        ]);
    }
}
