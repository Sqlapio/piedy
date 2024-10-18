<?php

namespace App\Livewire;

use App\Models\CarProducto;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Livewire\Attributes\On;

class TablePreSelectPro extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    #[On('add-item-car')]
    public function updateItem()
    {
        $this->reset();
    }

    #[On('truncate-item-car')]
    public function truncateItems()
    {
        $this->reset();
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Pre-Orden')
            ->description('Producto agregados para la pre-compra')
            ->query(CarProducto::query())
            ->columns([

                TextColumn::make('precio_venta')
                    ->label('Precio')
                    ->icon('heroicon-m-currency-dollar')
                    ->color('primary')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('cantidad')
                    ->label('Cantidad')
                    ->icon('heroicon-c-shopping-bag')
                    ->color('primary')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('total_compra_usd')
                    ->label('USD($)')
                    ->icon('heroicon-m-currency-dollar')
                    ->color('success')
                    ->money('USD')
                        ->summarize(Sum::make()
                            ->money('USD')
                            ->label('Total($)')
                        )
                    ->sortable(),
                TextColumn::make('total_compra_bsd')
                    ->label('BSD(Bs.)')
                    ->color('info')
                    ->money('VES')
                        ->summarize(Sum::make()
                            ->money('VES')
                            ->label('Total(BS.)')
                        )
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('delete')
                ->requiresConfirmation()
                ->action(fn (CarProducto $record) => $record->delete())
                ->icon('heroicon-c-trash')
                ->color('danger')
                //
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ])->striped();
    }

    public function render(): View
    {
        return view('livewire.table-pre-select-pro');
    }
}
