<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AsignarProductoResource\Pages;
use App\Filament\Resources\AsignarProductoResource\RelationManagers;
use App\Models\AsignarProducto;
use App\Models\Producto;
use App\Models\Sucursal;
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

    protected static ?string $navigationIcon = 'heroicon-s-user-plus';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('producto_id')
                ->label('Producto')
                ->options(Producto::all()->where('uso', 'consumo-interno')->pluck('descripcion', 'id'))
                ->searchable()
                ->required(),
                TextInput::make('cantidad')->required(),
                Select::make('sucursal_id')
                ->label('Sucursal')
                ->options(Sucursal::all()->pluck('nombre', 'id'))
                ->searchable()
                ->required(),
                DatePicker::make('fecha_entrega')
                    ->label('Fecha de entraga')
                    ->format('d-m-Y')
                    ->required(),
                Select::make('user_id')
                    ->label('Empleado')
                    ->options(User::all()->where('tipo_usuario', 'empleado')->where('status', 1)->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                TextInput::make('responsable')->default(Auth::user()->name),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(AsignarProducto::query()->orderBy('created_at', 'desc'))
            ->columns([
                TextColumn::make('producto.descripcion')
                    ->color('colorTree')
                    ->icon('heroicon-o-check')
                    ->searchable(),
                TextColumn::make('cantidad')
                    ->icon('heroicon-o-square-3-stack-3d')
                    ->color('success')
                    ->searchable(),
                TextColumn::make('sucursal.nombre')
                    ->icon('heroicon-c-building-office-2')
                    ->color('colorTwo')
                    ->searchable()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Empleado')
                    ->color('colorTwo')
                    ->icon('heroicon-m-user')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('responsable')
                    ->label('Responsable')
                    ->color('colorTwo')
                    ->icon('heroicon-m-user')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Fecha de entrega')
                    ->label('Fecha de Creación')
                    ->icon('heroicon-s-calendar-days')
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
