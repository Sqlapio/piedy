<?php

namespace App\Filament\Exports;

use App\Models\DetalleMovimientoInventarioSucursal;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\CellVerticalAlignment;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;

class DetalleMovimientoInventarioSucursalExporter extends Exporter
{
    protected static ?string $model = DetalleMovimientoInventarioSucursal::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('producto.descripcion'),
            ExportColumn::make('consumo'),
            ExportColumn::make('cantidad')
                ->numeric(),
            ExportColumn::make('costo')
                ->numeric(),
            ExportColumn::make('total')
                ->numeric(),
            ExportColumn::make('fecha_movimiento')
                ->label('Fecha de Salida'),
            ExportColumn::make('responsable'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your detalle movimiento inventario sucursal export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }

    public function getXlsxHeaderCellStyle(): ?Style
    {
        return (new Style())
            ->setFontBold()
            ->setFontItalic()
            ->setFontSize(12)
            ->setFontName('Auditoria de Servicios')
            ->setFontColor(Color::rgb(0, 0, 0))
            ->setBackgroundColor(Color::rgb(123, 149, 166))
            ->setCellAlignment(CellAlignment::CENTER)
            ->setCellVerticalAlignment(CellVerticalAlignment::CENTER);
    }
}