<?php

namespace App\Http\Controllers;

use App\Models\User;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {

        try {

            $user = User::where('email', $request->email)->get();

            if (count($user) > 0) 
            {
                foreach ($user as $item) {
                    $password = $item->password;
                    $empresa = $item->empresa;
                }

                    // Condicion para manera el login del checkmas para Banco del tesoro
                    if (Hash::check($request->password, $password)) 
                    {
                        $credenciales = [
                            'email' => $request->email,
                            'password' => $request->password,
                        ];

                        Auth::attempt($credenciales);
                        $user = Auth::user();

                        if($user->email == 'gusta.acp@gmail.com')
                        {
                            return view('dashboard');

                        }else{
                            return view('dashboard_empleado');
                        }
    
                    } else {
                        Notification::make()
                        ->title('Passwork incorreto')
                        ->success()
                        ->send();
                    }

            } else {
                Notification::make()
                ->title('La direccion de correo no exite en el sistema')
                ->success()
                ->send();
            }
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function logout(Request $request): LogoutResponse
    {
        /**
         * Lógica para colocar el usuario inactivo en base de datos
         */
        $user = Auth::user();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $request->session()->flush();

        return app(LogoutResponse::class);
    }
    //
}
