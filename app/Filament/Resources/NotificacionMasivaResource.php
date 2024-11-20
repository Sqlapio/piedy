<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificacionMasivaResource\Pages;
use App\Filament\Resources\NotificacionMasivaResource\RelationManagers;
use App\Http\Controllers\NotificacionesController;
use App\Models\NotificacionMasiva;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class NotificacionMasivaResource extends Resource
{
    protected static ?string $model = NotificacionMasiva::class;

    protected static ?string $navigationIcon = 'heroicon-s-megaphone';

    protected static ?string $navigationGroup = 'Sistema';

    protected static ?string $navigationLabel = 'Notificaciones Masivas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image')
                    ->label('Imagen de promocion')
                    ->image()
                    ->required(),
                Forms\Components\TextInput::make('caption')
                    ->label('Eslogan de la Promocion')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('responsable')
                    ->default(Auth::user()->name),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\ImageColumn::make('image')
                        ->height('80%')
                        ->width('80%'),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('caption')
                            ->weight(FontWeight::Bold),
                    ]),
                ])->space(3),
            ])
            ->filters([
                //
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('enviar')
                ->requiresConfirmation()
                ->label('Enviar Notificacion')
                ->icon('heroicon-o-rectangle-stack')
                ->action(function (NotificacionMasiva $record) {
                    NotificacionesController::notificacion_masiva($record->image, $record->caption);
                }),
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
            'index' => Pages\ListNotificacionMasivas::route('/'),
            'create' => Pages\CreateNotificacionMasiva::route('/create'),
            'edit' => Pages\EditNotificacionMasiva::route('/{record}/edit'),
        ];
    }
}
