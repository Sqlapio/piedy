<?php

namespace App\Livewire;

use App\Models\NomEncargado;
use App\Models\NominaGeneral;
use App\Models\NomManicurista;
use App\Models\NomQuiropedista;
use App\Models\PeriodoNomina;
use App\Models\Reporte as ModelsReporte;
use App\Models\TasaBcv;
use App\Models\User;
use App\Models\VentaServicio;
use Carbon\Carbon;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\Browsershot\Browsershot;

class Reporte extends Component
{
    #[Rule('required', message: 'Campo obligatorio')]
    public $empleado;

    #[Rule('required', message: 'Campo obligatorio')]
    public $periodo;

    public $atr_disabled;
    public $atr_hidden = 'hidden';
    public $atr_botton_hidden;

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

    public function cierre_nomina()
    {
        try {

            $nomQuiropedista               = NomQuiropedista::where('status', '1')->get();
            $totalNomQuiropedistaDolares   = $nomQuiropedista->sum('total_dolares');
            $totalNomQuiropedistaBolivares = $nomQuiropedista->sum('total_bolivares');

            $nomManicurista               = NomManicurista::where('status', '1')->get();
            $totalNomManicuristaDolares   = $nomManicurista->sum('total_dolares');
            $totalNomManicuristaBolivares = $nomManicurista->sum('total_bolivares');

            $nomEncargado               = NomEncargado::where('status', '1')->get();
            $totalNomEncargadoDolares   = $nomEncargado->sum('total_dolares');
            $totalNomEncargadoBolivares = $nomEncargado->sum('total_bolivares');


            /**Cambiamos los estatus de los calculos a 2 : Nomina cerrada */
            /**---------------------------------------------------------- */
            foreach ($nomQuiropedista as $nomQuiropedista) {
                $nomQuiropedista->status = '2';
                $nomQuiropedista->save();
            }

            foreach ($nomManicurista as $nomManicurista) {
                $nomManicurista->status = '2';
                $nomManicurista->save();
            }

            foreach ($nomEncargado as $nomEncargado) {
                $nomEncargado->status = '2';
                $nomEncargado->save();
            }

            $periodo_nomina = PeriodoNomina::where('status', '1')->first();
            if(isset($periodo_nomina)){
                $periodo = $periodo_nomina->cod_quincena;
            }else{
                throw new Exception("El periodo de nomina esta cerrado, por tanto no puede volver a ejecutar esta acción.", 401);
            }

            $nomina_general = new NominaGeneral();
            $nomina_general->fecha              = date('Y-m-d');
            $nomina_general->cod_quincena       = $periodo;
            $nomina_general->total_bolivares    = $totalNomQuiropedistaBolivares + $totalNomManicuristaBolivares + $totalNomEncargadoBolivares;
            $nomina_general->total_dolares      = $totalNomQuiropedistaDolares + $totalNomManicuristaDolares;
            $nomina_general->tasa_bcv           = TasaBcv::where('id', 1)->first()->tasa;
            $nomina_general->total_general      = ($nomina_general->total_bolivares / $nomina_general->tasa_bcv) + $nomina_general->total_dolares;

            /**Actualizo el estatus del pediodo de nomina a 2 : cerrado */
            /**---------------------------------------------------------- */
            $periodo_nomina->update([
                'status' => '2'
            ]);

            $nomina_general->save();

            $this->atr_disabled = '2';

            Notification::make()
            ->title('NOTIFICACIÓN')
            ->icon('heroicon-o-document-text')
            ->iconColor('info')
            ->color('info')
            ->body('El cierre de nomina fue ejecutado con exito')
            ->send();

            $this->dispatch('$refresh');

        } catch (\Throwable $th) {

            Notification::make()
            ->title('NOTIFICACIÓN')
            ->icon('heroicon-o-document-text')
            ->iconColor('danger')
            ->color('danger')
            ->body($th->getMessage())
            ->send();
        }

    }

    public function reportes()
    {
        $this->validate();

        try {
            $random = rand('11111', '99999');
            $pdf = 'E'.$this->empleado.'-'.$this->periodo.''.$random.'.pdf';

            $user = User::where('id', $this->empleado)->first();

            if($user->area_trabajo == 'quiropedia'){
                $nomina = NomQuiropedista::where('cod_quincena', $this->periodo)->where('user_id', $user->id)->first();
                $servicios = VentaServicio::whereBetween('created_at', [$nomina->fecha_ini, $nomina->fecha_fin])->where('empleado_id', $user->id)->get();
                $dias_trabajados = VentaServicio::whereBetween('created_at', [$nomina->fecha_ini, $nomina->fecha_fin])->where('empleado_id', $user->id)->groupBy('fecha_venta')->count();
                $rango = date('d-m-Y', strtotime($nomina->fecha_ini)).' al '.date('d-m-Y', strtotime($nomina->fecha_fin));
                $propinas_usd = VentaServicio::whereBetween('created_at', [$nomina->fecha_ini, $nomina->fecha_fin])->where('empleado_id', $user->id)->sum('propina_usd');
                $area_trabajo = $user->area_trabajo;

            }

            if($user->area_trabajo == 'manicure'){
                $nomina = NomManicurista::where('cod_quincena', $this->periodo)->where('user_id', $user->id)->first();
                $servicios = VentaServicio::whereBetween('created_at', [$nomina->fecha_ini, $nomina->fecha_fin])->where('empleado_id', $user->id)->get();
                $dias_trabajados = VentaServicio::whereBetween('created_at', [$nomina->fecha_ini, $nomina->fecha_fin])->where('empleado_id', $user->id)->groupBy('fecha_venta')->count();
                $rango = date('d-m-Y', strtotime($nomina->fecha_ini)).' al '.date('d-m-Y', strtotime($nomina->fecha_fin));
                $propinas_usd = VentaServicio::whereBetween('created_at', [$nomina->fecha_ini, $nomina->fecha_fin])->where('empleado_id', $user->id)->sum('propina_usd');
                $area_trabajo = $user->area_trabajo;
            }

            if($user->area_trabajo == 'Tienda'){
                $nomina = NomEncargado::where('cod_quincena', $this->periodo)->where('user_id', $user->id)->first();
                $servicios = VentaServicio::whereBetween('created_at', [$nomina->fecha_ini, $nomina->fecha_fin])->where('responsable_id', $user->id)->where('comision_gerente', '!=', 0)->get();
                $dias_trabajados = VentaServicio::whereBetween('created_at', [$nomina->fecha_ini, $nomina->fecha_fin])->where('responsable_id', $user->id)->groupBy('fecha_venta')->count();
                $rango = date('d-m-Y', strtotime($nomina->fecha_ini)).' al '.date('d-m-Y', strtotime($nomina->fecha_fin));
                $area_trabajo = $user->area_trabajo;
            }

            pdf::view('pdf.reporte',
                [
                    'cedula'                => User::where('id', $this->empleado)->first()->cedula,
                    'rango'                 => $rango,
                    'periodo'               => $this->periodo,
                    'nombre'                => $user->name,
                    'total_servicios'       => $nomina->total_servicios,
                    'propinas_bsd'          => ($user->area_trabajo == 'Tienda') ? '0.00' : $nomina->total_propina_bsd,
                    'propinas_usd'          => ($user->area_trabajo == 'Tienda') ? '0.00' : $propinas_usd,
                    'comision_bsd'          => ($user->area_trabajo == 'Tienda') ? '0.00' : $nomina->total_comision_bolivares,
                    'comision_usd'          => $nomina->total_comision_dolares,
                    'pro_dura_servicios'    => ($nomina->promedio_duracion_servicios != 'null') ? $nomina->promedio_duracion_servicios : '0.00',
                    'total_dolares'         => ($user->area_trabajo == 'Tienda') ? $nomina->total_dolares + $nomina->total_comision_dolares : $nomina->total_dolares,
                    'dias_trabajados'       => $dias_trabajados,
                    'total_bolivares'       => $nomina->total_bolivares,
                    'servicios'             => $servicios,
                    'nro_reporte'           => 'E'.$this->empleado.'-'.$this->periodo.''.$random,
                    'area_trabajo'          => $area_trabajo
                ])
            ->withBrowsershot(function (Browsershot $browsershot) {
                    $browsershot->setNodeBinary(env('NODE')); //location of node
                    $browsershot->setNpmBinary(env('NPM'));
                    $browsershot->setChromePath(env('CHROMIUM'));
                })
            ->format(Format::Letter)
            ->margins(5, 0, 18, 0)
            ->footerView('pdf.footer')
            ->save($pdf);

            /**Guardo el reporte en la tabla de reportes para tener el historico */
            $reporte = new ModelsReporte();
            $reporte->user_id = $this->empleado;
            $reporte->cod_reporte = 'E'.$this->empleado.'-'.$this->periodo.''.$random;
            $reporte->cod_quincena = $this->periodo;
            $reporte->descripcion = $pdf;
            $reporte->tipo = 'empleado';
            $reporte->fecha = date('d-m-Y');
            $reporte->responsable = Auth::user()->name;
            $reporte->save();

            Notification::make()
            ->title('NOTIFICACIÓN')
            ->icon('heroicon-o-document-text')
            ->iconColor('info')
            ->color('info')
            ->body('El reporte fue generado con exito')
            ->send();

            $this->reset();

        } catch (\Throwable $th) {
            dd($th);
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
        $periodo_nomina = PeriodoNomina::where('status', '1')->first();
        if(!isset($periodo_nomina)){
            $this->atr_hidden = '';
            $this->atr_botton_hidden = 'hidden';
        }

        return view('livewire.reporte', [
            'data' => ModelsReporte::orderBy('created_at', 'desc')
            ->where('tipo', 'empleado')
            ->get()
        ]);
    }
}
