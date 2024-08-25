<?php

namespace App\Livewire;

use App\Models\NomQuiropedista as ModelsNomQuiropedista;
use App\Models\User;
use App\Models\VentaServicio;
use Exception;
use Filament\Notifications\Notification;
use Livewire\Attributes\Rule;
use App\Models\PeriodoNomina;
use App\Models\VentaProducto;
use Livewire\Component;

class NomQuiropedista extends Component
{
    public array $asignacion_bolivares;
    public array $deduccion_dolares;
    public array $deduccion_bolivares;

    #[Rule('required', message: 'Campo obligatorio')]
    public $desde;

    #[Rule('required', message: 'Campo obligatorio')]
    public $hasta;

    #[Rule('required', message: 'Campo obligatorio')]
    public $quincena;

    public function conver_asignacion_bolivares($id)
    {
        try {
            $this->asignacion_bolivares[$id] = number_format(floatval(($this->asignacion_bolivares[$id]) / 100), 2, ',', '.');

        } catch (\Throwable $th) {
            Notification::make()
                    ->title('NOTIFICACIÓN')
                    ->icon('heroicon-o-shield-check')
                    ->color('danger')
                    ->body($th->getMessage())
                    ->send();
        }
    }

    public function conver_deduccion_bolivares($id)
    {
        try {
            $this->deduccion_bolivares[$id] = number_format(floatval(($this->deduccion_bolivares[$id]) / 100), 2, ',', '.');

        } catch (\Throwable $th) {
            Notification::make()
                    ->title('NOTIFICACIÓN')
                    ->icon('heroicon-o-shield-check')
                    ->color('danger')
                    ->body($th->getMessage())
                    ->send();
        }
    }

    public function conver_deduccion_dolares($id)
    {
        try {
            $this->deduccion_dolares[$id] = number_format(floatval(($this->deduccion_dolares[$id]) / 100), 2, ',', '.');

        } catch (\Throwable $th) {
            Notification::make()
                    ->title('NOTIFICACIÓN')
                    ->icon('heroicon-o-shield-check')
                    ->color('danger')
                    ->body($th->getMessage())
                    ->send();
        }
    }

    public function accion($value)
    {
        if($value == 1){
            $this->redirect('/n/q');
        }
        if($value == 2){
            $this->redirect('/n/m');
        }
        if($value == 3){
            $this->redirect('/n/q');
        }
        if($value == 4){
            $this->redirect('/n/e');
        }
        if($value == 5){
            $this->redirect('/empleados');
        }
        if($value == 6){
            $this->redirect('/reporte');
        }
        if($value == 7){
            $this->redirect('/reporte/general');
        }
    }

    public function store()
    {

        $this->validate();

        try {

            $data = User::where('tipo_servicio_id', '2')->where('status', '1')->get();
            $nro_empleados = count($data);

            $periodo_nomina = PeriodoNomina::where('status', '1')->first();
            if(isset($periodo_nomina)){
                $periodo = $periodo_nomina->cod_quincena;
            }else{
                throw new Exception("El periodo de nomina esta inactivo. Por favor contacte al administrador del sistema", 401);
            }

            foreach($data as $item){

                $nomina = new ModelsNomQuiropedista();

                $nomina->user_id = $item->id;
                $nomina->name = $item->name;
                $nomina->total_servicios = VentaServicio::where('empleado_id', $item->id)->whereBetween('created_at', [$this->desde.'.000', $this->hasta.'.000'])->count();

                //Promedio de duracion de los servicios
                $total_servicios = VentaServicio::where('empleado_id', $item->id)->whereBetween('created_at', [$this->desde.'.000', $this->hasta.'.000'])->count();
                $sum_servicios = VentaServicio::where('empleado_id', $item->id)->whereBetween('created_at', [$this->desde.'.000', $this->hasta.'.000'])->sum('duracion');

                /**
                 * Restriccion para validar el caso cuando el empleado no
                 * realizo ningun servicio o esta de vacaciones.
                 * ----------------------------------------------------------------
                 */
                if($total_servicios == 0){
                    $nomina->promedio_duracion_servicios = 0;
                }else{
                    $promedio  = $sum_servicios / $total_servicios;
                    $nomina->promedio_duracion_servicios    = $promedio;
                }

                $nomina->total_comision_dolares   = VentaServicio::where('empleado_id', $item->id)->whereBetween('created_at', [$this->desde.'.000', $this->hasta.'.000'])->sum('comision_dolares');
                $nomina->total_comision_bolivares = VentaServicio::where('empleado_id', $item->id)->whereBetween('created_at', [$this->desde.'.000', $this->hasta.'.000'])->sum('comision_bolivares');
                $nomina->total_comision_venprod   = VentaProducto::where('empleado_id', $item->id)->whereBetween('created_at', [$this->desde.'.000', $this->hasta.'.000'])->sum('comision_empleado');
                $nomina->total_propina_bsd        = VentaServicio::where('empleado_id', $item->id)->whereBetween('created_at', [$this->desde.'.000', $this->hasta.'.000'])->sum('propina_bsd');
                $nomina->total_propina_usd        = VentaServicio::where('empleado_id', $item->id)->whereBetween('created_at', [$this->desde.'.000', $this->hasta.'.000'])->sum('propina_usd');

                //Recorro el array de las asignaciones en bolivares
                for ($i=0; $i < count($this->asignacion_bolivares); $i++) {
                    # code...
                    $_bolivares = $this->asignacion_dolares[$item->id];
                    $nomina->asignaciones_bolivares         = str_replace(',', '.', str_replace('.', '', $_bolivares));
                }

                //Recorro el array de las asignaciones en bolivares
                for ($i=0; $i < count($this->deduccion_dolares); $i++) {
                    # code...
                    $_dedu_dolares = $this->deduccion_dolares[$item->id];
                    $nomina->deducciones_dolares            = str_replace(',', '.', str_replace('.', '', $_dedu_dolares));
                }

                //Recorro el array de las deducciones en bolivares
                for ($i=0; $i < count($this->deduccion_bolivares); $i++) {
                    # code...
                    $_dedu_bolivares = $this->deduccion_bolivares[$item->id];
                    $nomina->deducciones_bolivares            = str_replace(',', '.', str_replace('.', '', $_dedu_bolivares));
                }

                $nomina->fecha_ini = $this->desde;
                $nomina->fecha_fin = $this->hasta;
                $nomina->total_dolares = ($nomina->total_comision_dolares + $nomina->asignaciones_dolares + $nomina->total_propina_usd + $nomina->total_comision_venprod) - $nomina->deducciones_dolares;
                $nomina->total_bolivares = ($nomina->total_comision_bolivares + $nomina->asignaciones_bolivares + $nomina->total_propina_bsd) - $nomina->deducciones_bolivares;
                $nomina->quincena = $this->quincena;
                $nomina->cod_quincena = $periodo;

                /**
                 * Restriccion para validar el periodo de nomina correcto esto,
                 * evita que se calculen nominas en meses diferentes al actual
                 * ----------------------------------------------------------------
                 */
                if($nomina->cod_quincena != $periodo){
                    throw new Exception("El periodo de nomina no coincide con el periodo actual", 401);
                }

                /**
                 * Restriccion para validar nomina duplicada.
                 * ----------------------------------------------------------------
                 */
                $q_duplicada = ModelsNomQuiropedista::where('cod_quincena', $nomina->cod_quincena)->get();
                if(isset($q_duplicada) and count($q_duplicada) == $nro_empleados){
                    throw new Exception("La quincena que estas calculando ya exite. Por favor verifica el perido que estas calculando", 401);

                }else{

                    $nomina->save();
                }

            }

            $this->reset();

            $this->dispatch('nomina-calculada-quiropedista');

            Notification::make()
            ->title('NOTIFICACIÓN')
            ->icon('heroicon-o-document-text')
            ->iconColor('info')
            ->color('info')
            ->body('La nomina fue calculada de forma correcta.')
            ->send();

        } catch (\Throwable $th) {
            // dd($th);
            Notification::make()
                ->title('NOTIFICACIÓN')
                ->icon('heroicon-o-document-text')
                ->iconColor('danger')
                ->color('danger')
                ->body($th->getMessage())
                ->send();
        }


    }

    public function render()
    {
        $data = User::where('tipo_servicio_id', '2')->where('status', '1')->get();
        return view('livewire.nom-quiropedista', compact('data'));
    }
}
