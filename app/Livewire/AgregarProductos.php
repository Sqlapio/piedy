<?php

namespace App\Livewire;

use App\Http\Controllers\NotificacionesController;
use App\Http\Controllers\UtilsController;
use App\Models\Cliente;
use App\Models\DetalleAsignacion;
use App\Models\Disponible;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\TasaBcv;
use App\Models\User;
use App\Models\VentaServicio;
use Barryvdh\Debugbar\Facades\Debugbar;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\Actions;

class AgregarProductos extends Component
{
    use Actions;

    use WithPagination;

    public $buscar;
    public $productos_adicionales = [];
    public $total_productos = [];

    // public function mount()
    // {
    //     $productos = Producto::where('existencia', '>=', 1)->get();
    //     for ($i=0; $i < count($productos); $i++) {
    //         $this->productos_adicionales[$i] = '0';
    //     }
    // }

    public function carga_productos_adicionales(Request $request)
    {

        $codigo = $request->session()->all();

        foreach ($this->productos_adicionales as $key => $value)
        {
            // dd(Producto::where('id', 20)->first());
            $costo = Producto::where('id', $key)->first();
            $total = $costo->precio_venta * (intval($value));
            array_push($this->total_productos, $total);
        }

        dd($this->total_productos);
    }

    public function render(Request $request)
    {
        /**
         * El codigo es tomado de la variables de sesion
         * del usuario
         *
         * @param $codigo
         */
        $codigo = $request->session()->all();

        /**Obtengo la informacion de la primera asignacion y los datos del empleados y del cliente */
        $data = Disponible::where('cod_asignacion', $codigo['cod_asignacion'])->with('primeraAsignacion')->first();

        /**Obtengo todos los productos para vender */
        $productos = Producto::where('existencia', '>=', 1)->Paginate(5);

        $detalle_productos = DetalleAsignacion::where('cod_asignacion', $codigo['cod_asignacion'])
            ->where('status', '1')
            ->where('servicio_categoria', 'producto')
            ->get();

        /**Calculo el total de la vista */
        $total_vista = DB::table('detalle_asignacions')
            ->select(DB::raw('SUM(costo) as total'))
            ->where('cod_asignacion', $codigo['cod_asignacion'])
            ->where('status', '1')
            ->first()->total;

        return view('livewire.agregar-productos', compact('data', 'productos', 'detalle_productos', 'total_vista'));
    }
}
