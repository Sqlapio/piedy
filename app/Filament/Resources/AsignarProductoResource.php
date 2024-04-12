<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AsignarProductoResource\Pages;
use App\Filament\Resources\AsignarProductoResource\RelationManagers;
use App\Models\AsignarProducto;
use App\Models\Producto;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AsignarProductoResource extends Resource
{
    protected static ?string $model = AsignarProducto::class;

    protected static ?string $navigationGroup = 'Movimientos de inventario';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('producto_id')
                ->label('Producto')
                ->options(Producto::all()->pluck('descripcion', 'id'))
                ->searchable()
                ->required(),
                TextInput::make('cantidad')->required(),
                DatePicker::make('fecha_entrega')
                    ->label('Fecha de entraga')
                    ->format('d-m-Y')
                    ->required(),
                Select::make('user_id')
                    ->label('Empleado')
                    ->options(User::all()->where('tipo_usuario', 'empleado')->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                TextInput::make('responsable')->default(Auth::user()->name),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('producto.cod_producto')->searchable(),
                TextColumn::make('producto.descripcion')->searchable(),
                Tables\Columns\TextColumn::make('cantidad')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Empleado')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('responsable')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                ->label('Fecha de entrega')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAsignarProductos::route('/'),
            'create' => Pages\CreateAsignarProducto::route('/create'),
            'edit' => Pages\EditAsignarProducto::route('/{record}/edit'),
        ];
    }
}
