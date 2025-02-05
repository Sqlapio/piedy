<?php

namespace App\Filament\Resources\AnalisisReporteResource\Pages;

use App\Filament\Resources\AnalisisReporteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnalisisReporte extends EditRecord
{
    protected static string $resource = AnalisisReporteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
