<?php

namespace App\Filament\Resources\VentaProductoResource\Widgets;

use Carbon\Carbon;
use App\Models\VentaProducto;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use App\Filament\Resources\VentaProductoResource\Pages\ListVentaProductos;

class ChartAverage extends ChartWidget
{
    use InteractsWithPageFilters;

    public array $filters_pages_resources;
    
    protected static ?string $heading = 'Average de ventas';

    protected static ?string $maxHeight = '400px';

    protected int | string | array $columnSpan = 'full';

    protected function getTablePage(): string
    {
        return ListVentaProductos::class;
    }


    protected function getData(): array
    {
//  dd($this->filters_pages_resources);


        $desde = $this->filters_pages_resources['desde'] == null ? now()->startOfYear() :  $this->filters_pages_resources['desde'] . ' 00:00:00';
        $hasta = $this->filters_pages_resources['hasta'] == null ? now()->endOfYear() : $this->filters_pages_resources['hasta'] . ' 23:59:59';
        
        $data = DB::table('venta_productos')
        ->select(DB::raw('COUNT(empleado_id) as cantidad, users.name as nombres', 'created_at'))
        ->join('users', 'venta_productos.empleado_id', '=', 'users.id')
        ->whereBetween('venta_productos.created_at', [$desde, $hasta])
        ->groupBy('empleado_id')
        ->get();
        // dd($data);
        // dd($data);
        $cantidad = $data->map(fn($data) => $data->cantidad);
        $labels = $data->map(fn($data) => $data->nombres);

        return [
            'datasets' => [
                [
                    'label' => 'Average de ventas',
                    'data' => $cantidad,
                    'backgroundColor' => '#00ce0026',
                    'borderColor' => '#00ce00',
                    'fill' => true,
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