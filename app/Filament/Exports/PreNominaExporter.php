<?php

namespace App\Filament\Exports;

use App\Models\PreNomina;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PreNominaExporter extends Exporter
{
    protected static ?string $model = PreNomina::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('user_id'),            
            ExportColumn::make('sucursal_id'),
            ExportColumn::make('total_clientes_atendidos'),
            ExportColumn::make('total_servicios'),
            ExportColumn::make('total_productos'),
            ExportColumn::make('comision_usd'),
            ExportColumn::make('comision_bsd'),
            ExportColumn::make('comision_prod'),
            ExportColumn::make('propinas_usd'),
            ExportColumn::make('propinas_bsd'),
            ExportColumn::make('asignaciones_usd'),
            ExportColumn::make('asignaciones_bsd'),
            ExportColumn::make('deducciones_usd'),
            ExportColumn::make('deducciones_bsd'),
            ExportColumn::make('total_venta_sin_iva'),
            ExportColumn::make('iva'),
            ExportColumn::make('retencion_isrl'),
            ExportColumn::make('total_general_usd'),
            
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your pre nomina export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}