<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Gasto;
use Filament\Forms\Form;
use App\Models\PreNomina;
use Filament\Tables\Table;
use App\Models\NominaGeneral;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LogController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\NominaGeneralResource\Pages;
use App\Filament\Resources\NominaGeneralResource\RelationManagers;
use App\Filament\Resources\NominaGeneralResource\RelationManagers\PreNominasRelationManager;

class NominaGeneralResource extends Resource
{
    protected static ?string $model = NominaGeneral::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Modulo Administrativo';

    protected static ?string $navigationLabel = 'Nomina';

    protected static ?int $navigationSort = 8;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cod_nomina')
                ->label('Codigo')
                    ->searchable(),

                Tables\Columns\TextColumn::make('fecha_ini')
                ->label('Fecha Inicio')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_fin')
                ->label('Fecha Fin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sucursal.nombre')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status.descripcion')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Nomina-pre-calculada'  => 'warning',
                        'Nomina-totalizada'     => 'success',
                    })
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_dolares')
                    ->label('Total Dolares($)')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_bolivares')
                    ->label('Total Bolivares')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tasa_bcv')
                    ->label('Tasa BCV')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('conversion_usd')
                    ->label('Conversion($)')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_general')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Calculada el:')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('responsable')
                    ->searchable(),
                ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('delete')
                        ->label('Reversar Cálculo')
                        ->color('primary')
                        ->icon('heroicon-c-arrow-uturn-left')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            //Eliminamos el pre calculo
                            $pre_nominas = PreNomina::where('cod_nomina', $records->first()->cod_nomina)->get();
                            $pre_nominas->each->delete();

                            //log
                            LogController::log(Auth::user()->id, 'reverso', 'reverso de calculo de nomina', $response = null);

                            //Eliminamos el asiente generado por el calculo de nomina
                            $nomina_general = NominaGeneral::where('cod_nomina', $records->first()->cod_nomina)->first();
                            $nomina_general->delete();
                            //log
                            LogController::log(Auth::user()->id, 'reverso', 'reverso de nomina general', $response = null);

                            //Eliminamos el asiento creado en la tabla de gastos
                            $gastos = Gasto::where('numero_factura_gasto', 'Nom-' . $nomina_general->cod_nomina)->first();
                            if (isset($gastos)) {
                                $gastos->delete();
                            }
                            //log
                            LogController::log(Auth::user()->id, 'reverso', 'reverso de gasto de nomina', $response = null);

                            // $this->resetTable();
                        })->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PreNominasRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNominaGenerals::route('/'),
            'create' => Pages\CreateNominaGeneral::route('/create'),
            'edit' => Pages\EditNominaGeneral::route('/{record}/edit'),
        ];
    }
}