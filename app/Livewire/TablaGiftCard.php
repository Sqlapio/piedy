<?php

namespace App\Livewire;

use App\Models\GiftCard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class TablaGiftCard extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(GiftCard::query())
            ->columns([
                TextColumn::make('pgc')
                    ->label('PCS')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('cliente')
                    ->label('Cliente')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('cliente.cedula')
                    ->label('Cedula')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('fecha_emicion')
                    ->label('Emitida')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('fecha_vence')
                    ->label('Vence')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('monto')
                    ->label('Monto($)')
                    ->sortable()
                    ->searchable(),
                IconColumn::make('status')
                ->label('Estatus')
                    ->options([
                        'heroicon-o-check-circle' => fn ($state, $record): bool => $record->status === '1',
                        'heroicon-s-x-mark' => fn ($state, $record): bool => $record->status === '2',
                    ])
                //
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
            ]);
    }

    public function render(): View
    {
        return view('livewire.tabla-gift-card');
    }
}
