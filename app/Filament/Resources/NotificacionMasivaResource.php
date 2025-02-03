<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use App\Models\NotificacionMasiva;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\FontWeight;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\NotificacionesController;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\NotificacionMasivaResource\Pages;
use App\Filament\Resources\NotificacionMasivaResource\RelationManagers;

class NotificacionMasivaResource extends Resource
{
    protected static ?string $model = NotificacionMasiva::class;

    protected static ?string $navigationIcon = 'heroicon-s-megaphone';

    protected static ?string $navigationGroup = 'Notificaciones';

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
                    
                Forms\Components\DatePicker::make('fecha_programacion')
                    ->label('Programar para el:'),
                    
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
                    $envio = NotificacionesController::notificacion_masiva($record->image, $record->caption);
                    if($envio['success'] == true) {
                        Notification::make()
                        ->title('NOTIFICACIÓN')
                        ->icon('heroicon-o-document-text')
                        ->iconColor('success')
                        ->color('success')
                        ->body($envio['message'])
                        ->send();
                    }
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