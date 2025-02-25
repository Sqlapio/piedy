<?php

namespace App\Filament\Widgets;

use App\Models\Cliente;
use App\Models\Frecuencia;
use App\Models\VentaServicio;
use App\Models\VentaProducto;

// use Carbon\Carbon;
use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\DB;

class ClienteNuevoChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Clientes';

    protected static ?string $maxHeight = '300px';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 5;

    protected function getData(): array
    {

        $start  = $this->filters['startDate'] == null ? now()->startOfDay() : $this->filters['startDate'] . ' 00:00:00';
        $end    = $this->filters['endDate'] == null ? now()->endOfDay() : $this->filters['endDate'] . ' 23:59:59';

        /**Clientes Atendidos */
        /**********************************************************************************************************/
        $clientes = DB::table('venta_servicios')
        ->select(DB::raw('DATE(created_at) as fecha'), DB::raw('count(DISTINCT cliente_id) as clientes'))
        ->whereBetween('venta_servicios.created_at', [$start, $end])
        ->groupBy('fecha')
        ->get();
        // dd($clientes);
        $data_clientes_atendidos = $clientes->map(fn($data) => $data->clientes);
        /**********************************************************************************************************/


        /**Clientes Recurrentes */
        /*********************************************************************************************************************************/
        $clientes_recurentes = DB::table('venta_servicios')
        ->select(DB::raw('DATE(venta_servicios.created_at) as fecha'), DB::raw('count(DISTINCT venta_servicios.cliente_id) as clientes'))
        ->join('clientes', 'venta_servicios.cliente_id', '=', 'clientes.id')
        ->where('clientes.visitas', '>=', 2)
        ->whereBetween('venta_servicios.created_at', [$start, $end])
        ->groupBy('fecha')
        ->get();
        $data_clientes_recurentes = $clientes_recurentes->map(fn($data) => $data->clientes);
        /*********************************************************************************************************************************/


        /**Clientes Nuevos */
        /*********************************************************************************************************************************/
        $clientes_nuevos = DB::table('venta_servicios')
        ->select(DB::raw('DATE(venta_servicios.created_at) as fecha'), DB::raw('count(DISTINCT venta_servicios.cliente_id) as clientes'))
        ->join('clientes', 'venta_servicios.cliente_id', '=', 'clientes.id')
        ->where('clientes.visitas', 1)
        ->whereBetween('venta_servicios.created_at', [$start, $end])
        ->groupBy('fecha')
        ->get();
        $data_clientes_nuevos = $clientes_nuevos->map(fn($data) => $data->clientes);
        /*********************************************************************************************************************************/


        /**Labels (Escala del eje X) */
        /*********************************************************************************************************************************/
        $labels = $clientes->map(fn($data) => Carbon::parse($data->fecha)->isoFormat('dd, D'));
        /*********************************************************************************************************************************/

        return [
            'datasets' => [
                [
                    'label' => 'Clientes Atendidos',
                    'data' => $data_clientes_atendidos,
                    'backgroundColor' => '#00ce0026',
                    'borderColor' => '#00ce00',
                    'fill' => true,
                ],
                [
                    'label' => 'Clientes Recurentes',
                    'data' => $data_clientes_recurentes,
                    'borderColor' => '#ff0000',
                    'backgroundColor' => '#ff000045',
                ],
                [
                    'label' => 'Clientes Nuevos',
                    'data' => $data_clientes_nuevos,
                    'borderColor' => '#0a0aff',
                    'backgroundColor' => '#3b82f670',
                ],

            ],
            'labels' => $labels,

            // 'labels' => ($data_atendidos->map(fn (TrendValue $value) => Carbon::parse($value->date)->isoFormat('dd, D'))->toArray()),

        ];
    }

    public function getDescription(): ?string
    {
        return 'Clientes Nuevos/Recurentes/Atendidos';
    }

    protected function getType(): string
    {
         return 'bar';
    }

}
