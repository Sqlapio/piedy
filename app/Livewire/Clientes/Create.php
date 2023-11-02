<?php

namespace App\Livewire\Clientes;

use App\Models\Cliente;
use Filament\Tables\Actions\EditAction;
use Livewire\Component;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;



class Create extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Cliente::query())
            ->columns([
                    // TextColumn::make('nombre')->searchable(isIndividual: true),
                    TextColumn::make('nombre'),
                    TextColumn::make('apellido'),
                    TextColumn::make('cedula'),
                    TextColumn::make('email')->icon('heroicon-m-envelope'),
                    TextColumn::make('telefono'),
                    TextColumn::make('direccion_corta')
                        ->size(TextColumn\TextColumnSize::Large),
            ])
            ->emptyStateHeading('Clientes')
            ->emptyStateIcon('heroicon-o-bookmark')
            ->emptyStateDescription('No se han creado registros ')
            ->filters([
                // Filter::make('cedula')
                //     ->toggle()
                // ...
            ])
            ->actions([

                EditAction::make()
                ->form([
                    TextInput::make('nombre')
                        ->required()
                        ->maxLength(255),
                        TextInput::make('apellido')
                        ->required()
                        ->maxLength(255),
                        TextInput::make('cedula')
                        ->required()
                        ->maxLength(255),
                        TextInput::make('email')
                        ->required()
                        ->maxLength(255),
                        TextInput::make('telefono')
                        ->required()
                        ->maxLength(255),
                        TextInput::make('direccion_corta')
                        ->required()
                        ->maxLength(255),
                    // ...
                ]),
 
                Action::make('delete')
                    ->requiresConfirmation()
                    ->action(fn (Cliente $record) => $record->delete())
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nombre')->required(),
                TextInput::make('apellido')->required(),
                TextInput::make('cedula')->required(),
                TextInput::make('email')->required(),
                TextInput::make('telefono')->required(),
                TextInput::make('direccion_corta')->required(),
            ])
            ->statePath('data')
            ->columns('6')
            ->model(Cliente::class);
    }

    public function create(): void
    {

        /**
         * Esta variable almacena los
         * datos cargados enn el formulario
         * en forma de arreglo.
         * 
         * @array $data 
         */
        $data = $this->form->getState();

        $record = Cliente::create($data);

        $this->form->model($record)
        ->saveRelationships();

        Notification::make()
        ->title('Saved successfully')
        ->success()
        ->body('Changes to the post have been saved.')
        ->send();
    }

    public function edit(): void
    {

        $data = $this->form->getState();

        $this->record->update($data);
    }

    public function render(): View
    {
        return view('livewire.clientes.create');
    }
}