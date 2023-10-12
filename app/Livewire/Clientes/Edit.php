<?php

namespace App\Livewire\Clientes;

use App\Models\Cliente;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class Edit extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public Cliente $record;

    public function mount(): void
    {
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextColumn::make('nombre'),
                TextColumn::make('apellido'),
                TextColumn::make('cedula'),
                TextColumn::make('email'),
                TextColumn::make('telefono'),
                TextColumn::make('direccion_corta'),
            ])
            ->statePath('data')
            ->model($this->record);
    }

    public function edit(): void
    {
        $data = $this->form->getState();

        $this->record->update($data);
    }

    public function render(): View
    {
        return view('livewire.clientes.edit');
    }
}