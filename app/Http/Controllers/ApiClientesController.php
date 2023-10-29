<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ApiClientesController extends Controller
{
    public function lista_clientes(Request $request): Collection
    {
        return Cliente::query()
            ->select('id', 'nombre', 'apellido', 'email')
            ->orderBy('nombre')
            ->when(
                $request->search,
                fn (Builder $query) => $query
                    ->where('nombre', 'like', "%{$request->search}%")
                    ->orWhere('apellido', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%")
            )
            ->when(
                $request->exists('selected'),
                fn (Builder $query) => $query->whereIn('id', $request->input('selected', [])),
                fn (Builder $query) => $query->limit(10)
            )
            ->get()
            ->map(function (Cliente $cliente) {
                $cliente->nombre = $cliente->nombre.' '.$cliente->apellido;
                return $cliente;
            });
    }

    public function lista_empleados(Request $request): Collection
    {
        return User::query()
            ->select('id', 'name', 'email')
            ->where('tipo_usuario', 'empleado')
            ->orderBy('name')
            ->when(
                $request->search,
                fn (Builder $query) => $query
                    ->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%")
            )
            ->when(
                $request->exists('selected'),
                fn (Builder $query) => $query->whereIn('id', $request->input('selected', [])),
                fn (Builder $query) => $query->limit(10)
            )
            ->get()
            ->map(function (User $user) {
                // $user->name = $empleado->nombre.' '.$empleado->apellido;
                return $user;
            });
    }

    public function lista_servicios(Request $request): Collection
    {
        return Servicio::query()
            ->select('id', 'descripcion')
            ->where('categoria', 'principal')
            ->orderBy('descripcion')
            ->when(
                $request->search,
                fn (Builder $query) => $query
                    ->where('descripcion', 'like', "%{$request->search}%")
            )
            ->when(
                $request->exists('selected'),
                fn (Builder $query) => $query->whereIn('id', $request->input('selected', [])),
                fn (Builder $query) => $query->limit(10)
            )
            ->get()
            ->map(function (Servicio $servicio) {
                return $servicio;
            });
    }
}
