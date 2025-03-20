<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\FichaMedica;
use App\Models\Frecuencia;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Filament\Notifications\Notification;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use WireUi\Traits\Actions;

class Clientes extends Component
{
    public function render()
    {
        return view('livewire.clientes');
    }
}
