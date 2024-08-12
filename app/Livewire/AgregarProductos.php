<?php

namespace App\Livewire;

use App\Http\Controllers\NotificacionesController;
use App\Http\Controllers\UtilsController;
use App\Models\Cliente;
use App\Models\DetalleAsignacion;
use App\Models\Disponible;
use App\Models\Producto;

use App\Models\User;
use App\Models\VentaProducto;
use App\Models\VentaServicio;
use Barryvdh\Debugbar\Facades\Debugbar;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function notificacion()
    {

        $this->dialog()->confirm([

                'title'       => 'Notificación de Venta',
                'description' => 'Esta seguro que desea asignar esta venta de productos?',
                'icon'        => 'warning',
                'accept'      => [
                    'label'  => 'Si, asignar venta',
                    'method' => 'carga_productos_adicionales',
                    'params' => 'Saved',
                ],
                'reject' => [
                    'label'  => 'No, cancelar',
                    'method' => 'cancelar',

                ],

            ]);
    }

    public function notificacion_delete($id)
    {

        $this->dialog()->confirm([

                'title'       => 'Notificación de Sistema',
                'description' => 'Esta seguro que desea eliminar el producto seleccionado?. Esta acción anula la venta del producto y la cantidad es devuelta a exitencia.',
                'icon'        => 'warning',
                'accept'      => [
                    'label'  => 'Si, eliminar producto',
                    'method' => 'delete',
                    'params' => $id,
                ],
                'reject' => [
                    'label'  => 'No, cancelar',
                    'method' => 'cancelar',

                ],

            ]);
    }

    public function delete(Request $request, $id)
    {
        $codigo = $request->session()->all();

        $producto = VentaProducto::where('id', $id)->update([
            'status' => 2
        ]);

        $producto_id = VentaProducto::where('id', $id)->first();

        $update_existencia = Producto::where('id', $producto_id->producto_id)->first();
        $update_existencia->update([
            'existencia' => $update_existencia->existencia + $producto_id->cantidad
        ]);

        Notification::make()
        ->title('El producto fue eliminado con éxito')
        ->success()
        ->send();

        $this->reset();

    }

    public function cancelar()
    {
        Notification::make()
        ->title('La venta fue cancelada con exito')
        ->success()
        ->send();

        $this->reset();
    }

    public function carga_productos_adicionales(Request $request)
    {
        try {

            $codigo = $request->session()->all();

            $data = Disponible::where('cod_asignacion', $codigo['cod_asignacion'])->first();

            foreach ($this->productos_adicionales as $key => $value)
            {
                if($value < 0)
                {
                    throw new Exception("No se aceptan valores negativos", 401);
                }
                // dd(Producto::where('id', 20)->first());
                $info_prod = Producto::where('id', $key)->first();

                $total = $info_prod->precio_venta * (intval($value));

                /**Guardo la transaccion en la tabla de venta productos */
                $venta = new VentaProducto();
                $venta->cod_asignacion      = $codigo['cod_asignacion'];
                $venta->empleado_id         = $data->empleado_id;
                $venta->rol                 = User::where('id', $data->empleado_id)->first()->area_trabajo;
                $venta->producto_id         = $info_prod->id;
                $venta->costo_producto      = $info_prod->precio_venta;
                $venta->comision_empleado   = UtilsController::comiEmple_ventaProducto($total);
                $venta->comision_gerente    = UtilsController::comiGerente_ventaProducto($total);
                $venta->fecha_venta         = now()->format('d-m-Y');
                $venta->cantidad            = (intval($value));
                $venta->total_venta         = $total;
                $venta->responsable         = Auth::user()->name;
                $venta->save();

                /**Realizo el descuento en la existencia en la tabla de prodcutos */
                $info_prod->existencia -= (intval($value));
                $info_prod->save();

            }

            Notification::make()
            ->title('La venta fue asignada con exito')
            ->success()
            ->send();

            $this->reset();

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

    public function cerrar_venta()
    {
        $this->redirect('/cabinas');
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

        /**Calculo el total de la vista */
        $total_vista = DB::table('venta_productos')
            ->select(DB::raw('SUM(total_venta) as total'))
            ->where('cod_asignacion', $codigo['cod_asignacion'])
            ->where('status', '1')
            ->first()->total;

        /**Seleccion los productos que voy a vender y los muestro en la lista de 'Productos Adicionales vendidos' */
        $lista_prod = VentaProducto::where('cod_asignacion', $codigo['cod_asignacion'])->where('status', 1)->with('producto')->get();

        return view('livewire.agregar-productos', compact('data', 'productos', 'total_vista', 'lista_prod'));
    }
}
