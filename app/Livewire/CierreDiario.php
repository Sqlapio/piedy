<?php

namespace App\Livewire;

use App\Http\Controllers\CierreDiarioController;
use App\Models\CierreDiario as ModelsCierreDiario;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use WireUi\Traits\Actions;
use Filament\Tables\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Support\RawJs;
use Filament\Forms\Components\Textarea;

class CierreDiario extends Component implements HasForms, HasTable
{
    use Actions;
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->heading('CIERRE DIARIO')
            ->description('Tabla de cierre diario por turno')
            ->query(ModelsCierreDiario::query()
            ->whereDate('created_at', now()->toDateString())
            ->where('sucursal_id', auth()->user()->sucursal_id))
            ->columns([
                TextColumn::make('total_ventas')
                    ->money('USD')
                    ->label('Venta Total($)')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('total_dolares_efectivo')
                    ->money('USD')
                    ->icon('heroicon-m-currency-dollar')
                    ->color('success')
                    ->label('Efectivo($)')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('total_dolares_zelle')
                    ->money('USD')
                    ->icon('heroicon-m-credit-card')
                    ->color('success')
                    ->label('Zelle($)')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('total_bolivares')
                    ->label('Bolivares(Bs)')
                    ->icon('heroicon-m-credit-card')
                    ->color('info')
                    ->money('VES')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('ref_debito')
                    ->label('Ref. Débito')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('ref_credito')
                    ->label('Ref. Credito')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('ref_visaMaster')
                    ->label('Ref. Visa/Master')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Fecha de cierre')
                    ->icon('heroicon-s-calendar-days')
                    ->color('colorTree')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('responsable')
                    ->icon('heroicon-s-user')
                    ->color('colorOne')
                    ->sortable()
                    ->searchable(),
                
                TextColumn::make('observaciones')
                    ->icon('heroicon-s-user')
                    ->color('colorOne')
                    ->sortable()
                    ->searchable(),
            ])
            ->groups([
                'responsable',
            ])
            ->filters([
                DateRangeFilter::make('created_at')->timezone('America/Caracas'),
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ])
            ->headerActions([
                CreateAction::make()
                ->model(CierreDiario::class)
                ->form([
                    Section::make('Formulario')
                        ->description('Debe llenar los campos de forma correta')
                        ->icon('heroicon-s-newspaper')
                        ->schema([
                            Grid::make()
                            ->schema([

                                //Debito
                                TextInput::make('ref_debito')
                                    ->label('Ref. Debito')
                                    ->prefixIcon('heroicon-c-hashtag')
                                    ->mask(RawJs::make(<<<'JS'
                                        $input.startsWith('34') || $input.startsWith('37') ? '999999' : '999999'
                                    JS))
                                    ->numeric(),
                                TextInput::make('monto_ref_debito')
                                    ->label('Monto Debito')
                                    ->prefixIcon('heroicon-s-building-library')
                                    ->mask(RawJs::make(<<<'JS'
                                        $money($input, ',')
                                    JS)),

                                //Credito
                                TextInput::make('ref_credito')
                                    ->label('Ref. Credito')
                                    ->prefixIcon('heroicon-c-hashtag')
                                    ->mask(RawJs::make(<<<'JS'
                                        $input.startsWith('34') || $input.startsWith('37') ? '999999' : '999999'
                                    JS))
                                    ->numeric(),
                                TextInput::make('monto_ref_credito')
                                    ->label('Monto Credito')
                                    ->prefixIcon('heroicon-s-building-library')
                                    ->mask(RawJs::make(<<<'JS'
                                        $money($input, ',')
                                    JS)),

                                //Vida/Master
                                TextInput::make('ref_visaMaster')
                                    ->label('Ref. Visa/Master')
                                    ->prefixIcon('heroicon-c-hashtag')
                                    ->mask(RawJs::make(<<<'JS'
                                        $input.startsWith('34') || $input.startsWith('37') ? '999999' : '999999'
                                    JS))
                                    ->numeric(),

                                TextInput::make('monto_ref_visaMaster')
                                    ->label('Monto Visa/Master')
                                    ->prefixIcon('heroicon-s-building-library')
                                    ->mask(RawJs::make(<<<'JS'
                                        $money($input, ',')
                                    JS)),
                                // ...
                            ]),
                        Textarea::make('observaciones')
                        ->autosize()

                        ])
                ])
                ->action(function (array $data) {
                    CierreDiarioController::cierreDiario(
                        $data['ref_debito'],
                        $data['monto_ref_debito'],
                        $data['ref_credito'],
                        $data['monto_ref_credito'],
                        $data['ref_visaMaster'],
                        $data['monto_ref_visaMaster'],
                        $data['observaciones']);

                })
            ]);
    }

    public function render()
    {
        return view('livewire.cierre-diario');
    }
}