<?php

namespace App\Filament\Resources\PreNominaResource\Pages;

use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PreNominaResource;
use App\Http\Controllers\PreNominaController;

class CreatePreNomina extends CreateRecord
{
    protected static string $resource = PreNominaResource::class;

}