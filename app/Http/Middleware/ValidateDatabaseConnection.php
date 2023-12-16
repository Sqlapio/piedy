<?php

namespace App\Http\Middleware;

use App\Http\Controllers\UtilsController;
use Closure;
use Illuminate\Http\Request;
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

                $tabla = 'tasa_bcvs';
                UtilsController::sincronizacion($tabla);

                /** Sincronizamos la tabla clientes data guardada en la local */
                $tabla = 'clientes';
                UtilsController::sincronizacion($tabla);

                /** Sincronizamos la tabla clientes data guardada en la local */
                $tabla = 'venta_servicios';
                UtilsController::sincronizacion($tabla);

                return $next($request);  

        } catch (\Throwable $th) {
            return $next($request);  
            // return response()->json(['error' => 'Error al conectar con la base de datos'], 500);
        }
        
    }
}
