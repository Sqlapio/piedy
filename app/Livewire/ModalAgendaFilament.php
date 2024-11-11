<?php

namespace App\Livewire;

use App\Models\Agenda;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Action;
use Livewire\Attributes\On; 
use Livewire\Component;

class ModalAgendaFilament extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    public Agenda $agenda;
 
    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->requiresConfirmation()
            ->action(fn () => $this->post->delete());
    }

    public function render()
    {
        return view('livewire.modal-agenda-filament');
    }
}
