<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Attributes\Rule;
use Livewire\Component;

class LoginExterno extends Component
{

    #[Rule('required', message: 'Campo requerido')]
    public $cedula;

    public function validar_usuario(){

        $this->validate();

        try {

            $user = User::where('cedula', $this->cedula)->first();

            if(isset($user)){
                session(['usuario' => $user]);
                return redirect()->route('pago-externo');
            }else{
                $this->emit('error','Usuario no encontrado, verifique su cédula o cree una nueva cuenta.');
            }

        } catch (\Throwable $th) {
            throw $th;
        }

    }
    public function render()
    {

        return view('livewire.login-externo');
    }
}
