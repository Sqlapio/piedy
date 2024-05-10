<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Mes;
use App\Models\MetodoPago;
use App\Models\Producto;
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
            ->orderBy('id', 'desc')
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
                fn (Builder $query) => $query->limit(4)
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
                fn (Builder $query) => $query->limit(8)
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
            ->where('status', 'activo')
            ->orderBy('descripcion')
            ->when(
                $request->search,
                fn (Builder $query) => $query
                    ->where('descripcion', 'like', "%{$request->search}%")
            )
            ->when(
                $request->exists('selected'),
                fn (Builder $query) => $query->whereIn('id', $request->input('selected', [])),
                fn (Builder $query) => $query->limit(4)
            )
            ->get()
            ->map(function (Servicio $servicio) {
                return $servicio;
            });
    }

    public function metodo_pago(Request $request): Collection
    {
        return MetodoPago::query()
            ->select('id', 'descripcion')
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
            ->map(function (MetodoPago $metodo_pago) {
                return $metodo_pago;
            });
    }

    public function metodo_pago_uno(Request $request): Collection
    {
        return MetodoPago::query()
            ->select('id', 'descripcion')
            ->where('moneda', 'usd')
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
            ->map(function (MetodoPago $metodo_pago) {
                return $metodo_pago;
            });
    }

    public function metodo_pago_dos(Request $request): Collection
    {
        return MetodoPago::query()
            ->select('id', 'descripcion')
            ->where('moneda', 'bsd')
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
            ->map(function (MetodoPago $metodo_pago) {
                return $metodo_pago;
            });
    }


    public function categoria_producto(Request $request): Collection
    {
        return Categoria::query()
            ->select('id', 'descripcion')
            ->orderBy('descripcion')
            ->when(
                $request->search,
                fn (Builder $query) => $query
                    ->where('descripcion', 'like', "%{$request->search}%")
            )
            ->when(
                $request->exists('selected'),
                fn (Builder $query) => $query->whereIn('id', $request->input('selected', [])),
                fn (Builder $query) => $query->limit(5)
            )
            ->get()
            ->map(function (Categoria $categoria) {
                return $categoria;
            });
    }

    public function metodo_pago_ref(Request $request): Collection
    {
        return MetodoPago::query()
            ->select('id', 'descripcion')
            ->where('ref', '1')
            ->orderBy('descripcion')
            ->when(
                $request->search,
                fn (Builder $query) => $query
                    ->where('descripcion', 'like', "%{$request->search}%")
            )
            ->when(
                $request->exists('selected'),
                fn (Builder $query) => $query->whereIn('id', $request->input('selected', [])),
                fn (Builder $query) => $query->limit(5)
            )
            ->get()
            ->map(function (MetodoPago $metodo_pago) {
                return $metodo_pago;
            });
    }

    public function lista_productos(Request $request): Collection
    {
        return Producto::query()
            ->select('id', 'descripcion')
            ->orderBy('descripcion')
            ->when(
                $request->search,
                fn (Builder $query) => $query
                    ->where('descripcion', 'like', "%{$request->search}%")
            )
            ->when(
                $request->exists('selected'),
                fn (Builder $query) => $query->whereIn('id', $request->input('selected', [])),
                fn (Builder $query) => $query->limit(5)
            )
            ->get()
            ->map(function (Producto $producto) {
                return $producto;
            });
    }

    public function meses(Request $request): Collection
    {
        return Mes::query()
            ->select('id','mes','numero')
            ->orderBy('id', 'asc')
            ->when(
                $request->search,
                fn (Builder $query) => $query
                    ->where('mes', 'like', "%{$request->search}%")
            )
            ->when(
                $request->exists('selected'),
                fn (Builder $query) => $query->whereIn('id', $request->input('selected', [])),
                fn (Builder $query) => $query->limit(12)
            )
            ->get()
            ->map(function (Mes $mes) {
                $mes->mes;
                return $mes;
            });
    }

    public function metodo_pago_multiple(Request $request): Collection
    {
        return MetodoPago::query()
            ->select('id', 'descripcion')
            ->where('moneda', 'multiple')
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
            ->map(function (MetodoPago $metodo_pago) {
                return $metodo_pago;
            });
    }
}
