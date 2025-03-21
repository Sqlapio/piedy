<?php

namespace App\Livewire;

use Filament\Tables;
use Filament\Forms\Get;
use Livewire\Component;
use App\Models\Asistencia;
use Filament\Tables\Table;
use Illuminate\Http\Request;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Illuminate\Contracts\View\View;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\AsistenciaController;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class TableAsistencia extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('REGISTRO DE ASISTENCIA')
            ->description('Tabla para el registro de asistencia')
            ->query(Asistencia::query())
            ->columns([
                Tables\Columns\TextColumn::make('empleado.name')
                    ->label('Empleado')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('entrada')
                    ->label('Entrada')
                    ->alignCenter()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'warnnig',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('salida')
                    ->label('Salida')
                    ->alignCenter()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        '1' => 'danger',
                        '0' => 'warnnig',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Entrada')
                    ->badge()
                    ->color('success')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Salida')
                    ->badge()
                    ->color('warning')
                    ->dateTime()
                    ->sortable(),
                    
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ])
            ->headerActions([
                CreateAction::make('entrada')
                ->label('ENTRADA')
                ->color('success')
                ->model(Asistencia::class)
                ->form([
                    Section::make('Formulario de registro de Entrada')
                        ->description('Debe llenar los campos de forma correcta. Campos Requeridos(*)')
                        ->icon('heroicon-c-users')
                        ->schema([
                            Grid::make()
                            ->schema([
                                //codigo de requisicion
                                TextInput::make('cedula')
                                ->label('Cedula de Identidad')
                                ->prefixIcon('heroicon-c-users')
                                ->required()
                                ->placeholder('16007868'),
                            ]),
                        ]),
                ])
                ->action(function (array $data, Request $request) {
                    //ip
                    $ip = $request->ip();
                    $entrada = AsistenciaController::entrada($data['cedula']);
                })
                ->modalWidth(MaxWidth::TwoExtraLarge),

            CreateAction::make('salida')
                ->label('SALIDA')
                ->color('danger')
                ->model(Asistencia::class)
                ->form([
                    Section::make('Formulario de registro de Entrada')
                        ->description('Debe llenar los campos de forma correcta. Campos Requeridos(*)')
                        ->icon('heroicon-c-users')
                        ->schema([
                            Grid::make()
                                ->schema([
                                    //codigo de requisicion
                                    TextInput::make('cedula')
                                    ->label('Cedula de Identidad')
                                    ->prefixIcon('heroicon-c-users')
                                    ->required()
                                    ->placeholder('16007868'),
                                ]),
                        ]),
                ])
                ->action(function (array $data, Request $request) {
                    $ip = $request->ip();
                    $salida = AsistenciaController::salida($data['cedula']);
                })
                ->modalWidth(MaxWidth::TwoExtraLarge),

                
            ])->striped();
    }

    public function render(): View
    {
        return view('livewire.table-asistencia');
    }
}