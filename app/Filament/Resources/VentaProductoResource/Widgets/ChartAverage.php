<?php

namespace App\Filament\Resources\VentaProductoResource\Widgets;

use Carbon\Carbon;
use App\Models\VentaProducto;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class ChartAverage extends ChartWidget
{

    use InteractsWithPageFilters;
    
    protected static ?string $heading = 'Average de ventas';

    public ?string $filter = 'week';

    protected static ?string $maxHeight = '400px';

    protected int | string | array $columnSpan = 'full';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hoy',
            'week'  => 'Semana',
            'month' => 'Mes',
            'year'  => 'Año',
        ];
    }

    protected function getData(): array
    {

        $activeFilter = $this->filter;

        if ($activeFilter === 'today') {
            $rangeStartDate = now()->startOfDay();
            $rangeEndDate = now()->endOfDay();
        } elseif ($activeFilter === 'week') {
            $rangeStartDate = now()->startOfWeek();
            $rangeEndDate = now()->endOfWeek();
        } elseif ($activeFilter === 'month') {
            $rangeStartDate = now()->startOfMonth();
            $rangeEndDate = now()->endOfMonth();
        } elseif ($activeFilter === 'year') {
            $rangeStartDate = now()->startOfYear();
            $rangeEndDate = now()->endOfYear();
        }

        // $datos = VentaProducto::all()->select(['empleado_id', 'created_at'])->groupBy('empleado_id')->count();

        $data = DB::table('venta_productos')
        ->select(DB::raw('COUNT(empleado_id) as cantidad, users.name as nombres', 'created_at'))
        ->whereBetween('venta_productos.created_at', [$rangeStartDate, $rangeEndDate])
            ->join('users', 'venta_productos.empleado_id', '=', 'users.id')
            ->groupBy('empleado_id')
            // ->take(10)
            ->get();
        // dd($data);
        $cantidad = $data->map(fn($data) => $data->cantidad);
        $labels = $data->map(fn($data) => $data->nombres);

        return [
            'datasets' => [
                [
                    'label' => 'Average de ventas',
                    'data' => $cantidad,
                    'backgroundColor' => '#22c55e',
                    'borderColor' => '#22c55e',
                ]
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public function getDescription(): ?string
    {
        return 'Grafico Cantidad de Productos vendidos por Empleado';
    }
}