<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Gasto;
use App\Models\PreNomina;
use Filament\Tables\Table;
use App\Models\NominaGeneral;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LogController;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\AnalisisReporteController;
use App\Filament\Resources\NominaGeneralResource\Pages;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
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
                ->numeric(),

                    
                Tables\Columns\TextColumn::make('status.descripcion')
                ->badge()
                ->color(fn(string $state): string => match ($state) {
                    'Nomina-pre-calculada'  => 'warning',
                    'Nomina-totalizada'     => 'success',
                    'Periodo-cerrado'       => 'danger',
                })
                ->numeric(),

                    
                Tables\Columns\TextColumn::make('total_dolares')
                ->label('Total Dolares($)')
                ->numeric(decimalPlaces: 2, locale: 'es')
                ->alignCenter(),

                    
                Tables\Columns\TextColumn::make('total_bolivares')
                ->label('Total Bolivares')
                ->alignCenter()
                ->numeric(decimalPlaces: 2, locale: 'es'),

                    
                Tables\Columns\TextColumn::make('tasa_bcv')
                ->label('Tasa BCV')
                ->alignCenter()
                ->numeric(decimalPlaces: 2, locale: 'es'),

                    
                Tables\Columns\TextColumn::make('conversion_usd')
                ->label('Conversion($)')
                ->alignCenter()
                ->numeric(decimalPlaces: 2, locale: 'es'),

                    
                Tables\Columns\TextColumn::make('total_general')
                ->alignCenter()
                ->numeric(decimalPlaces: 2, locale: 'es'),

                    
                Tables\Columns\TextColumn::make('created_at')
                ->label('Calculada el:')
                ->dateTime(),

                    
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

                    Tables\Actions\BulkAction::make('cerrar_periodo')
                    ->label('Cerrar Periodo')
                    ->color('success')
                    ->icon('heroicon-m-lock-closed')
                    ->requiresConfirmation()
                    ->action(function (Collection $records) {
                        // dd($records);
                        $cierre_periodo = AnalisisReporteController::cierre($records, $records->first()->fecha_ini, $records->first()->fecha_fin, $records->first()->cod_nomina);
                        
                        if($cierre_periodo == true)
                        {
                            //Actualizamos el estatus de la nomina
                            $records->first()->status_id = 9;
                            $records->first()->save();
                            
                            LogController::log(Auth::user()->id, 'cierre de periodo', 'Cierre de periodo desde: '. $records->first()->fecha_ini.' hasta: '. $records->first()->fecha_fin, $response = null);
                            Notification::make()
                            ->title('NOTIFICACIÓN')
                            ->icon('heroicon-o-shield-check')
                            ->iconColor('success')
                            ->color('success')
                            ->body('El periodo comprendido entre el: ' . $records->first()->fecha_ini . ' - ' . $records->first()->fecha_fin . ' fue cerrado con exito')
                            ->send();
                        }else{
                            Notification::make()
                            ->title('NOTIFICACIÓN')
                            ->icon('heroicon-o-shield-check')
                            ->iconColor('danger')
                            ->color('danger')
                            ->body('La nomina debe estar es estatus Periodo-cerrado')
                            ->send();
                        }
                         
                    })->deselectRecordsAfterCompletion(),

                    ExportBulkAction::make()
                    ->label('Exportar')
                    ->color('success'),
                    
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
            'index'  => Pages\ListNominaGenerals::route('/'),
            'create' => Pages\CreateNominaGeneral::route('/create'),
            'edit'   => Pages\EditNominaGeneral::route('/{record}/edit'),
        ];
    }
}