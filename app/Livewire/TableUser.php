<?php

namespace App\Livewire;

use App\Filament\Resources\UserResource;
use App\Livewire\FormularioCrearUsuario;
use App\Models\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Filament\Actions\EditAction;
use Filament\Actions\Modal\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;


class TableUser extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query()->where('status', '1')->whereBetween('tipo_usuario', ['empleado', 'encargado']))
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre y Apellido')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('cedula')
                    ->label('Cedula')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('telefono')
                    ->label('Telefono')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('area_trabajo')
                    ->label('Area de Trabajo')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
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
        return view('livewire.table-user');
    }
}
