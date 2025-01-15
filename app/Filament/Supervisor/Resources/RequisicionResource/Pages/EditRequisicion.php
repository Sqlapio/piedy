<?php

namespace App\Filament\Supervisor\Resources\RequisicionResource\Pages;

use App\Filament\Supervisor\Resources\RequisicionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRequisicion extends EditRecord
{
    protected static string $resource = RequisicionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
