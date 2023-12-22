<?php

namespace App\Http\Middleware;

use App\Http\Controllers\UtilsController;
use App\Models\Job;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ValidateDatabaseConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {

            DB::connection()->getPdo();

            $array_tables = ['users', 'comisions', 'clientes', 'venta_servicios', 'factura_multiples', 'gastos'];

            foreach ($array_tables as $value)
            {
                UtilsController::sincronizacion($value);
            }

            $failed_job = DB::table('failed_jobs')->get();
            if (count($failed_job) > 0) {
                /**Refresco la tabla de jobs para que se realice el envio de los correos */
                Artisan::call('queue:retry all');

                /**Ejecuto el worker para disparar los correo de la cola */
                Artisan::call('queue:work');
            }

            return $next($request);

        } catch (\Throwable $th) {
            return $next($request);

        }

    }
}
