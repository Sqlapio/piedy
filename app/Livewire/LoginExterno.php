<?php

namespace App\Livewire;

use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Validate;
use Livewire\Component;

class LoginExterno extends Component
{

    #[Validate('required', message: 'Campo requerido')]
    public $cedula;

    public function validar_usuario(){

        $this->validate();

        try {

            $user = User::where('cedula', $this->cedula)->first();

            if(isset($user)){
                session(['usuario' => $user]);
                return redirect()->route('pago-externo');
            }else{
                $error = ValidationException::withMessages(['Usuario no encontrado, verifique su cédula o intente con otra diferente.']);
                Notification::make()
                    ->title('NOTIFICACIÓN')
                    ->icon('heroicon-o-shield-check')
                    ->color('danger')
                    ->body($error->getMessage())
                    ->send();
            }

        } catch (\Throwable $th) {
            Notification::make()
                    ->title('NOTIFICACIÓN')
                    ->icon('heroicon-o-shield-check')
                    ->color('danger')
                    ->body($th->getMessage())
                    ->send();
        }

    }
    public function render()
    {
        return view('livewire.login-externo');
    }
}
