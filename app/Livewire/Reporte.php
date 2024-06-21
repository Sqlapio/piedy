<?php

namespace App\Livewire;

use App\Models\NomEncargado;
use App\Models\NominaGeneral;
use App\Models\NomManicurista;
use App\Models\NomQuiropedista;
use App\Models\PeriodoNomina;
use App\Models\TasaBcv;
use App\Models\User;
use Exception;
use Filament\Notifications\Notification;
use Livewire\Component;
use Spatie\Browsershot\Browsershot;

use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\LaravelPdf\Enums\Format;

class Reporte extends Component
{
    public $empleado;
    public $periodo;

    public $atr_disabled;

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

        $pdf = date('YmdHms').'_'.$this->empleado.'_'.$this->periodo.'.pdf';

        $nombre = User::where('id', $this->empleado)->first()->name;

        pdf::view('pdf.reporte', 
            [
                'id' => $this->empleado, 
                'periodo' => $this->periodo,
                'nombre' => $nombre,
            ])
        ->format(Format::A4)
        ->save($pdf);

        // Browsershot::url('http://piedy.test/reporte/nomina/'.$this->empleado.'/'.$this->periodo)
        // ->format(Format::A4)
        // ->save($pdf);
    }


    public function render()
    {
        $periodo_nomina = PeriodoNomina::where('status', '1')->first();
        if(isset($periodo_nomina)){
            $this->atr_disabled = '2';
        }
        return view('livewire.reporte');
    }
}
