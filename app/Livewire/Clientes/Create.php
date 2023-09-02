<?php

namespace App\Livewire\Clientes;

use App\Models\Cliente;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class Create extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
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
            ->columns('3')
            ->model(Cliente::class);
    }

    public function create(): void
    {
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

    public function render(): View
    {
        return view('livewire.clientes.create');
    }
}