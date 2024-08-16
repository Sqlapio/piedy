<?php

namespace App\Livewire;

use App\Models\NomEncargado;
use App\Models\NominaGeneral;
use App\Models\NomManicurista;
use App\Models\NomQuiropedista;
use App\Models\PeriodoNomina;
use App\Models\Reporte;
use App\Models\VentaServicio;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Rule;
use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\Browsershot\Browsershot;
use Livewire\Component;

class ReporteGeneral extends Component
{
    #[Rule('required', message: 'Campo obligatorio')]
    public $periodo;

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

            $nomina_encargados = NomEncargado::where('cod_quincena', $this->periodo)->get();

            $totales = NominaGeneral::where('cod_quincena', $this->periodo)->first();

            $rango_fechas = PeriodoNomina::where('cod_quincena', $this->periodo)->first();

            $total_facturado_usd_quiropedistas = NomQuiropedista::where('cod_quincena', $this->periodo)->sum('total_dolares');
            $total_facturado_bsd_quiropedistas = NomQuiropedista::where('cod_quincena', $this->periodo)->sum('total_bolivares');

            $total_facturado_usd_manicuristas = NomManicurista::where('cod_quincena', $this->periodo)->sum('total_dolares');
            $total_facturado_bsd_manicuristas = NomManicurista::where('cod_quincena', $this->periodo)->sum('total_bolivares');

            $total_facturado_bsd_encargados = NomEncargado::where('cod_quincena', $this->periodo)->sum('total_bolivares');

            $total_general_dolares = $total_facturado_usd_quiropedistas + $total_facturado_usd_manicuristas;
            $total_general_bolivares = $total_facturado_bsd_quiropedistas + $total_facturado_bsd_manicuristas + $total_facturado_bsd_encargados;
            $nomina_general_dolares =  ($total_general_bolivares / $totales->tasa_bcv) + $total_general_dolares;

            $random = rand('11111', '99999');
            
            $pdf = $this->periodo.'_'.$random.'.pdf';

            pdf::view('pdf.reporte-general',
                [
                    'nomina'                    => $nominas,
                    'nomina_encargados'         => $nomina_encargados,
                    'periodo'                   => $this->periodo,
                    'rango'                     => Carbon::createFromFormat('Y-m-d', $rango_fechas->fecha_ini)->format('d-m-Y').' al '.Carbon::createFromFormat('Y-m-d', $rango_fechas->fecha_fin)->format('d-m-Y'),
                    'total_general_dolares'     => number_format($total_general_dolares, 2),
                    'total_general_bolivares'   => number_format($total_general_bolivares, 2, ",", "."),
                    'nomina_general_dolares'    => number_format($nomina_general_dolares, 2),
                    'tasa_bcv'                  => $totales->tasa_bcv,
                ])
            ->withBrowsershot(function (Browsershot $browsershot) {
                    $browsershot->setNodeBinary(env('NODE')); //location of node
                    $browsershot->setNpmBinary(env('NPM')); //location of npm
                    $browsershot->setChromePath(env('CHROMIUM'));
                })
            ->landscape()
            ->margins(0, 0, 15, 0)
            ->footerView('pdf.footer')
            ->save($pdf);

            /**Guardo el reporte en la tabla de reportes para tener el historico */
            $reporte = new Reporte();
            $reporte->cod_reporte = $this->periodo.'_'.$random;
            $reporte->cod_quincena = $this->periodo;
            $reporte->descripcion = $pdf;
            $reporte->tipo = 'general';
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

    public function render()
    {
        return view('livewire.reporte-general', [
            'data' => Reporte::orderBy('created_at', 'desc')
            ->where('tipo', 'general')
            ->get()
        ]);
    }
}
