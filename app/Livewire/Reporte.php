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
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\LaravelPdf\Enums\Format;

class Reporte extends Component
{
    #[Rule('required', message: 'Campo obligatorio')]
    public $empleado;

    #[Rule('required', message: 'Campo obligatorio')]
    public $periodo;

    public $atr_disabled;
    public $atr_hidden = 'hidden';
    public $atr_botton_hidden;

    public function cierre_nomina()
    {
        try {

            $nomQuiropedista = NomQuiropedista::where('status', '1')->get();
            $totalNomQuiropedistaDolares = $nomQuiropedista->sum('total_dolares');
            $totalNomQuiropedistaBolivares = $nomQuiropedista->sum('total_bolivares');

            $nomManicurista = NomManicurista::where('status', '1')->get();
            $totalNomManicuristaDolares = $nomManicurista->sum('total_dolares');
            $totalNomManicuristaBolivares = $nomManicurista->sum('total_bolivares');

            $nomEncargado = NomEncargado::where('status', '1')->get();
            $totalNomEncargadoDolares = $nomEncargado->sum('total_dolares');
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

            $this->dispatch('reporte_empleado_save');

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
            }

            if($user->area_trabajo == 'manicure'){
                $nomina = NomManicurista::where('cod_quincena', $this->periodo)->where('user_id', $user->id)->first();
                $servicios = VentaServicio::whereBetween('created_at', [$nomina->fecha_ini, $nomina->fecha_fin])->where('empleado_id', $user->id)->get();
            }

            pdf::view('pdf.reporte',
                [
                    'id' => $this->empleado,
                    'periodo' => $this->periodo,
                    'nombre' => $user->name,
                    'servicios' => $servicios,
                    'nro_reporte' => $user->id.$this->periodo,
                ])
            ->format(Format::A4)
            ->margins(10, 0, 18, 0)
            ->footerView('pdf.footer')
            ->save($pdf);

            /**Guardo el reporte en la tabla de reportes para tener el historico */
            $reporte = new ModelsReporte();
            $reporte->cod_reporte = 'E'.$this->empleado.'-'.$this->periodo.''.$random;
            $reporte->descripcion = $pdf;
            $reporte->tipo = 'empleado';
            $reporte->fecha = date('d-m-Y');
            $reporte->responsable = Auth::user()->name;
            $reporte->save();

            $this->reset();

            $this->dispatch('reporte_empleado_save');

            Notification::make()
            ->title('NOTIFICACIÓN')
            ->icon('heroicon-o-document-text')
            ->iconColor('info')
            ->color('info')
            ->body('El reporte fue generado con exito')
            ->send();

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


    public function render()
    {
        $periodo_nomina = PeriodoNomina::where('status', '1')->first();
        if(!isset($periodo_nomina)){
            $this->atr_hidden = '';
            $this->atr_botton_hidden = 'hidden';
        }
        return view('livewire.reporte');
    }
}
