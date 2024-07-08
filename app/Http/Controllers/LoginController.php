<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use WireUi\Traits\Actions;

class LoginController extends Controller
{
    use Actions;

    public function login(Request $request)
    {
        dd('aqui');
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

    public function actualiza_password(Request $request)
    {

        try {

            $validated = $request->validate([
                'email' => ['required'],
                'password' => ['required'],
                'password_two' => ['required'],
            ]);

            if ($validated) {

                $user = User::where('email', $request->email)->first();

                if ($user != null) {
                    if ($request->password == $request->password_two) {
                        User::where('email', $request->email)
                            ->update([
                                'password' => Hash::make($request->password),
                            ]);

                            Notification::make()
                                ->title('NOTIFICACIÓN')
                                ->icon('heroicon-o-document-text')
                                ->iconColor('info')
                                ->color('info')
                                ->body('La contraseña fue actualizada de forma exitosa')
                                ->send();

                            $type = 'reseteo_password';
                            $mailData = [
                                'user_email' => $user->email,
                                'user_fullname' => $user->name,
                            ];

                            // NotificacionesController::notification($mailData, $type);

                    } else {
                        throw new Exception("Las contraseñas no son iguales, por favor verifique y vuelva a intentar", 401);
                    }
                } else {
                    throw new Exception("Correo invalido, por favor verifique y vuelva a intentar", 401);

                }

                return redirect('/');
            }


        } catch (\Throwable $th) {
            Notification::make()
            ->title('NOTIFICACIÓN')
            ->icon('heroicon-o-document-text')
            ->iconColor('danger')
            ->color('danger')
            ->body($th->getMessage())
            ->send();
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
