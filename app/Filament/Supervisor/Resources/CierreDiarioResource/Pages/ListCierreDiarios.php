<?php

namespace App\Filament\Supervisor\Resources\CierreDiarioResource\Pages;

use Filament\Actions;
use Filament\Support\RawJs;
use App\Models\CierreDiario;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use App\Http\Controllers\CierreDiarioController;
use App\Filament\Supervisor\Resources\CierreDiarioResource;

class ListCierreDiarios extends ListRecords
{
    protected static string $resource = CierreDiarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
