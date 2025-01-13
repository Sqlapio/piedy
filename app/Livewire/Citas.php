<?php

namespace App\Livewire;

use Closure;
use Carbon\Carbon;
use App\Models\Cita;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Horario;
use Filament\Forms\Get;
use Livewire\Component;
use App\Models\Servicio;
use Flowframe\Trend\Trend;
use WireUi\Traits\Actions;
use Filament\Actions\Action;
use Livewire\WithPagination;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use App\Http\Controllers\UtilsController;
use App\Http\Controllers\AgendaController;
use Filament\Actions\Contracts\HasActions;
use App\Http\Controllers\AsignacionController;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Http\Controllers\NotificacionesController;
use Filament\Actions\Concerns\InteractsWithActions;

class Citas extends Component implements HasForms, HasActions
{
    use WithPagination;

    use Actions;

    use InteractsWithActions;
    use InteractsWithForms;

    public $opcion, $inicio, $fin;

    public $mes;
    public $largo;
    public $scroll;

    public function mount(Cita $cita)
    {
        $this->mes = Carbon::now()->format('m');
        $this->opcion = 'mes';
    }

    public function CreateAction(): Action
    {
        return Action::make('create')
            ->modalHeading(false)
            ->color('success')
            ->form([
                Section::make('Formulario de Citas')
                    ->description('Debe llenar los campos de forma correcta. Campos Requeridos(*)')
                    ->icon('heroicon-s-calendar-days')
                    ->schema([
                        //Seleccion de servicio
                        Select::make('cliente_id')
                            ->label('Seleccione el Cliente')
                            ->prefixIcon('heroicon-c-users')
                            ->options(Cliente::all()->pluck('nombre', 'id'))
                            ->searchable()
                            ->required()
                            ->live(),
                        Select::make('servicio_id')
                            ->label('Seleccione el Servicio')
                            ->prefixIcon('heroicon-o-swatch')
                            ->options(Servicio::where('sucursal_id', Auth::user()->sucursal_id)->pluck('descripcion', 'id'))
                            ->searchable()
                            ->required()
                            ->live(),
                        Select::make('user_id')
                            ->label('Seleccione el Tecnico')
                            ->prefixIcon('heroicon-c-users')
                            ->options(User::where('sucursal_id', Auth::user()->sucursal_id)->whereBetween('rol_id', [1, 2])->pluck('name', 'id'))
                            ->searchable()
                            ->live(),
                        Select::make('horario')
                            ->label('Hora de la Cita')
                            ->prefixIcon('heroicon-s-calendar-days')
                            ->options(Horario::all()->pluck('hora', 'id'))
                            ->searchable()
                            ->required(),
                    ])->columns(2)
            ])
            ->action(function (array $arguments, array $data) {
                // dd($arguments);
                $array = UtilsController::agenda($arguments['mes'], $this->opcion);

                $valida = AgendaController::validaciones($data['horario'], $array[$arguments['id']], $data['cliente_id'], $data['servicio_id'], $data['user_id']);

                if($valida['success'] == true) {
                    $agendar = AgendaController::agendar_cita($data['cliente_id'], $data['servicio_id'], $data['user_id'], $array[$arguments['id']], $data['horario']);
                    if($agendar['success'] == true) {
                        Notification::make()
                            ->title('Notificacion')
                            ->icon('heroicon-o-check-circle')
                            ->iconColor('success')
                        ->color('success')
                            ->body($agendar['message'])
                            ->send();
                    }
                } else {
                    Notification::make()
                        ->title('Notificacion')
                        ->icon('heroicon-o-exclamation-triangle')
                        ->iconColor('danger')
                        ->color('danger')
                        ->body($valida['message'])
                        ->send();
                }
            });
    }

    public function AsignarAction(): Action
    {
        return Action::make('asignar')
            ->icon('heroicon-c-user-plus')
            ->modalHeading(false)
            ->color('success')
            ->form([
                    Section::make('Asignar Tecnico')
                        ->description('Debe llenar los campos de forma correcta. Campos Requeridos(*)')
                        ->icon('heroicon-c-user-plus')
                        ->schema([
                    //Seleccion de servicio
                    Select::make('user_id')
                        ->label('Seleccione el Tecnico')
                        ->prefixIcon('heroicon-c-users')
                        ->options(User::where('sucursal_id', Auth::user()->sucursal_id)->whereBetween('rol_id', [1, 2])->pluck('name', 'id'))
                        ->searchable(),
                    ])
            ])
            ->action(function (array $arguments, array $data) {

                $res = AgendaController::asignar_tecnico($arguments['cita'], $data['user_id']);

                if ($res) {
                    Notification::make()
                        ->title('Notificacion')
                        ->icon('heroicon-m-check-circle')
                        ->iconColor('success')
                        ->color('success')
                        ->body('Tecnico asignado con exito!!!')
                        ->send();
                } else {
                    Notification::make()
                        ->title('Notificacion')
                        ->icon('heroicon-o-exclamation-triangle')
                        ->iconColor('danger')
                        ->body('No se pudo asignar al tecnico, por favor vuelva a intentar')
                        ->send();
                }
            });
    }

    public function ActivarAction(): Action
    {
        return Action::make('activar')
            ->icon('heroicon-c-power')
            ->color('colorOne')
            ->requiresConfirmation()
            ->modalHeading('Activar Servicio')
            ->modalDescription('Esta seguro que desea activar el servício?')
            ->modalSubmitActionLabel('Si, activar servício')
            ->modalIcon('heroicon-c-power')
            ->action(function (array $arguments) {

                $info_cita = Cita::find($arguments)->first();

                //Controller para asignacion de servicio
                $res = AsignacionController::asignacion_servicio($info_cita->cliente_id, $info_cita->empleado_id, $info_cita->servicio_id);

                if ($res) {

                    $info_cita->status = 2;
                    $info_cita->save();

                    Notification::make()
                        ->title('NOTIFICACIÓN')
                        ->icon('heroicon-o-shield-check')
                        ->iconColor('success')
                        ->body('El servicio fue asignado correctamente!')
                        ->send();
                } else {
                    Notification::make()
                        ->title('NOTIFICACIÓN')
                        ->icon('heroicon-s-exclamation-triangle')
                        ->iconColor('danger')
                        ->body('El tecnico ya posee un servicio abierto. Por favor realiza la facturación y vuelve a intentar!')
                        ->send();
                }
            });
    }

    public function EliminarAction(): Action
    {
        return Action::make('eliminar')
            ->color('danger')
            ->icon('heroicon-s-trash')
            ->requiresConfirmation()
            ->modalHeading('Eliminar Cita')
            ->modalDescription('Esta seguro que desea eliminar la cita?')
            ->modalSubmitActionLabel('Si, eliminar cita')
            ->modalIcon('heroicon-o-trash')
            ->action(function (array $arguments) {
                $cita = Cita::find($arguments['cita']);
                $cita->status = 3;
                $cita->save();
            });
    }

    public function RecordarAction(): Action
    {
        return Action::make('recordar')
            ->color('success')
            ->icon('heroicon-m-device-phone-mobile')
            ->requiresConfirmation()
            ->modalHeading('Recordatorio de Cita')
            ->modalDescription('Esta seguro que desea enviar el recordatorio de cita?')
            ->modalSubmitActionLabel('Si, enviar')
            ->modalIcon('heroicon-m-device-phone-mobile')
            ->action(function (array $arguments) {

                try {

                    $cita = Cita::find($arguments['cita']);
                    $mailData = [
                        'id'                => $cita->id,
                        'cliente_email'     => $cita->correo,
                        'cliente_fullname'  => $cita->cliente,
                        'fecha_cita'        => $cita->fecha,
                        'hora_cita'         => $cita->hora,
                        'telefono'          => $cita->telefono,
                    ];
                    
                    /**Notificacion por Whatsapp */
                    $notificacion = NotificacionesController::notificacion_cita_wp($mailData);

                    if ($notificacion['success'] == true) {
                        Notification::make()
                        ->title('NOTIFICACIÓN')
                        ->icon('heroicon-o-document-text')
                        ->iconColor('success')
                        ->color('success')
                        ->body($notificacion['message'])
                        ->send();
                        
                    } else {
                        Notification::make()
                        ->title('NOTIFICACIÓN')
                        ->icon('heroicon-o-document-text')
                        ->iconColor('danger')
                        ->color('danger')
                        ->body($notificacion['message'])
                        ->send();
                    }
                    //code...
                } catch (\Throwable $th) {
                    Notification::make()
                    ->title('NOTIFICACIÓN')
                    ->icon('heroicon-o-document-text')
                    ->iconColor('danger')
                    ->color('danger')
                    ->body($th->getMessage())
                    ->send();
                }
                    
            });
    }

    public function filtro()
    {
        if ($this->opcion == 'semana') {
            $this->inicio = now()->startOfWeek();
            $this->fin = now()->endOfWeek();
            // dd($this->inicio, $this->fin);
        }
        if ($this->opcion == 'mes') {
            $this->inicio = now()->startOfMonth()->month($this->mes);
            $this->fin = now()->endOfMonth()->month($this->mes);
        }
        if ($this->opcion == 'dia') {
            $this->inicio = now()->startOfDay()->month($this->mes);
            $this->fin = now()->endOfDay()->month($this->mes);
        }
    }

    public function div_largo()
    {
        if ($this->opcion == 'mes') {
            $this->largo = 'h-64';
            $this->scroll = 'h-96';
        }

        if ($this->opcion == 'semana') {
            $this->largo = 'h-96';
            $this->scroll = 'h-96';
        }

        if ($this->opcion == 'dia') {
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

        $data_citas = Cita::where('status', 1)
            ->where('fecha_formateada', 'like', '%' . $fecha . '%')
            ->where('sucursal_id', Auth::user()->sucursal_id)
            ->get();

        // dump($data_citas);

        $datas = Trend::model(Cita::class)
            ->between(
                $start,
                $end,
            )
            ->perDay()
            ->count();

        $array = $datas->map(fn(TrendValue $value) => Carbon::parse($value->date)->isoFormat('dddd, D MMM'))->toArray();

        $horario = Horario::all();

        $data_citas_dia = Cita::where('status', 1)
            ->where('sucursal_id', Auth::user()->sucursal_id)
            ->where('fecha_formateada', date('Y-m-d'))
            ->get();

        // dd($data_citas_dia, $data_citas);

        return view('livewire.citas', [
            'array'             => $array,
            'data_citas'        => $data_citas,
            'horas'             => $horario,
            'data_citas_dia'    => $data_citas_dia
        ]);
    }
}