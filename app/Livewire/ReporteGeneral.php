<?php

namespace App\Livewire;

use App\Models\NomEncargado;
use App\Models\NominaGeneral;
use App\Models\NomManicurista;
use App\Models\NomQuiropedista;
use App\Models\Reporte;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Rule;
use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\LaravelPdf\Enums\Format;
use Livewire\Component;

class ReporteGeneral extends Component
{
    #[Rule('required', message: 'Campo obligatorio')]
    public $periodo;

    public function reporte_general()
    {
        $this->validate();

        try {

            $nominas = DB::table('nom_quiropedistas')
            ->select(
                'nom_quiropedistas.name',
                'nom_quiropedistas.total_servicios',
                'nom_quiropedistas.total_dolares',
                'nom_quiropedistas.total_bolivares'
                )
            ->where('nom_quiropedistas.cod_quincena', $this->periodo)
                ->union(
                    DB::table('nom_manicuristas')
                    ->select(
                        'nom_manicuristas.name',
                        'nom_manicuristas.total_servicios',
                        'nom_manicuristas.total_dolares',
                        'nom_manicuristas.total_bolivares'
                    )
                    ->where('nom_manicuristas.cod_quincena', $this->periodo)
                )
            ->get();

            $totales = NominaGeneral::where('cod_quincena', $this->periodo)->first();

            $random = rand('11111', '99999');
            $pdf = $this->periodo.'_'.$random.'.pdf';

            pdf::view('pdf.reporte-general',
                [
                    'nomina' => $nominas,
                    'total_general_dolares' => $totales->total_dolares,
                    'total_general_bolivares' => $totales->total_bolivares,
                    'total_general' => $totales->total_general,
                    'tasa_bcv' => $totales->tasa_bcv,


                ])
            ->landscape()
            ->margins(0, 0, 15, 0)
            ->footerView('pdf.footer')
            ->save($pdf);

            /**Guardo el reporte en la tabla de reportes para tener el historico */
            $reporte = new Reporte();
            $reporte->cod_reporte = $this->periodo.'_'.$random;
            $reporte->descripcion = $pdf;
            $reporte->tipo = 'general';
            $reporte->fecha = date('d-m-Y');
            $reporte->responsable = Auth::user()->name;
            $reporte->save();

            $this->reset();

            $this->dispatch('reporte_general_save');

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
        $nomina_quiropedistas = NomQuiropedista::where('cod_quincena', $this->periodo)->where('status', '2')->get();
        $nomina_manicuristas = NomManicurista::where('cod_quincena', $this->periodo)->where('status', '2')->get();
        $nomina_encargado = NomEncargado::where('cod_quincena', $this->periodo)->where('status', '2')->get();
        return view('livewire.reporte-general', compact('nomina_quiropedistas', 'nomina_manicuristas', 'nomina_encargado'));
    }
}
