<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use WireUi\Traits\Actions;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    use Actions;

    public $email;
    public $password;

    private function resetInputFields()
    {
        $this->email = '';
        $this->password = '';
    }

    /**
     * Reglas de validación para todos los campos del formulario
     */
    protected $rules = [

        'email'    => 'required|email',
        'password' => 'required',


    ];

    protected $messages = [

        'password.required'  => 'Campo Requerido',
        'email.required'     => 'Campo Requerido',


    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function dashboard_gerenete()
    {
        redirect()->to('/');
    }

    public function dashboard_empleado()
    {
        redirect()->to('/dashboard_empleado');
    }

    public function login()
    {

        try {

            // Reglas de Validación
            $this->validate();

            $user = User::where('email', $this->email)->get();

            if (count($user) > 0)
            {
                foreach ($user as $item) {
                    $password = $item->password;
                    $empresa = $item->empresa;
                }

                    // Condicion para manera el login del checkmas para Banco del tesoro
                    if (Hash::check($this->password, $password))
                    {
                        $credenciales = [
                            'email' => $this->email,
                            'password' => $this->password,
                        ];

                        Auth::attempt($credenciales);
                        $user = Auth::user();

                        if($empresa == 'Trx' || $empresa == 'Banco del Tesoro')
                        {

                            /**
                             * Lógica para colocar el usuario inactivo en base de datos
                             */
                            UtilsController::userActivo($user->id);

                            /**
                             * Lógica para contar las veces que el usuario ingresa al sistema
                             */
                            UtilsController::actualizaContador($user->id, $user->contador);

                            /**
                             * Manejo de roles en checkmas -> Banco del Tesoro
                             */
                            if ($user->status_registro == '1')
                            {

                                if ($user->rol == '7' || $user->rol == '8') {
                                    $this->dashTecnicos();
                                }

                                if ($user->rol == '3' || $user->rol == '4') {
                                    $this->retornaListaTicket();
                                }

                                if ($user->rol == '6' || $user->rol == '5' || $user->rol == '2' || $user->rol == '1') {
                                    $this->retornaDash();
                                }

                                if ($user->rol == '200') {
                                    $this->completarRegistroBanco();
                                }

                                if ($user->rol == '400') {
                                    $this->notification()->success(
                                        $title = 'ERROR!',
                                        $description = 'El correo administrador@checkmas.com fue DESACTIVADO'
                                    );
                                }
                            }else{
                                $this->notification()->success(
                                    $title = 'NOTIFICACIÓN!',
                                    $description = 'El usuario esta en ESPERA DE APROBACIÓN!'
                                );
                                $this->reset();
                            }

                        }

                        if($empresa == 'IAIM')
                        {
                            /**
                             * Lógica para colocar el usuario inactivo en base de datos
                             */
                            UtilsController::userActivo($user->id);

                            /**
                             * Lógica para contar las veces que el usuario ingresa al sistema
                             */
                            UtilsController::actualizaContador($user->id, $user->contador);

                            /**
                             * Manejo de roles en checkmas -> Banco del Tesoro
                             */
                            if ($user->status_registro == '1') {
                                $this->retornaDashIaim();
                            }

                        }

                    } else {
                        $this->password = '';
                        $this->notification()->success(
                            $title = 'ERROR!',
                            $description = 'Password incorrecto'
                        );
                    }

            } else {

                $this->email = '';
                $this->notification()->success(
                    $title = 'ERROR!',
                    $description = 'El email no registrado'
                );
            }
        } catch (\Throwable $th) {
            $this->email = '';
            $this->notification()->error(
                $title = 'ERROR!',
                $description = $th->getMessage()
            );
        }
    }

    public function render()
    {
        return view('livewire.login');
    }
}
