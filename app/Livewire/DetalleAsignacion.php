<?php

namespace App\Livewire;

use App\Http\Controllers\NotificacionesController;
use App\Http\Controllers\UtilsController;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Disponible;
use App\Models\Empleado;
use App\Models\Servicio;
use Filament\Notifications\Notification;
use Livewire\Component;
use App\Livewire\Citas;
use App\Models\DetalleAsignacion as ModelsDetalleAsignacion;
use App\Models\TasaBcv;
use App\Models\User;
use App\Models\Venta;
use App\Models\VentaProducto;
use App\Models\VentaServicio;
use Barryvdh\Debugbar\Facades\Debugbar;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Rule;
use Livewire\WithPagination;
use LivewireUI\Modal\ModalComponent;
use WireUi\Traits\Actions;


class DetalleAsignacion extends ModalComponent
{
    use Actions;

    use WithPagination;

    #[Rule('required', message: 'Campo obligatorio')]
    public $empleado_id;

    #[Rule('required', message: 'Campo obligatorio')]
    public $servicio_id;

    /**
     * ATRIBUTOS PARA L A EDICION DEL SERVICIO
     */
    public $es_empleado_id;
    public $es_cliente_id;
    public $es_servicio_id;

    public $descripcion;
    public $referencia;
    public $buscar;
    public $atr1 = 'hidden';
    public $atr2 = 'hidden';
    public $atr3 = '';
    public $atr4 = '';
    public $atr5 = '';
    public $atr6 = '';
    public $atr7 = '';
    public $atr8 = '';
    public $atr9 = 'hidden';
    public $atr10 = 'hidden';
    public $atr11 = '';
    public $grid = 'grid-cols-1';
    public $servicios = [];

    public Disponible $disponible;

    public function otro_tecnico()
    {
        $this->atr1 = '';
        $this->atr2 = '';

        /** Atributos para ocultar los servicios asignados */

        $this->atr3 = 'hidden';
        $this->atr4 = 'hidden';
        $this->atr5 = 'hidden';
        $this->atr6 = 'hidden';
        $this->atr7 = 'hidden';
        $this->atr8 = 'hidden';
    }

    public function editar_servicio()
    {
        $this->atr9 = '';
        $this->atr10 = '';

        /** Atributos para ocultar los servicios asignados */

        $this->atr3 = 'hidden';
        $this->atr4 = 'hidden';
        $this->atr5 = 'hidden';
        $this->atr6 = 'hidden';
        $this->atr7 = 'hidden';
        $this->atr8 = 'hidden';
        $this->atr11 = 'hidden';

    }

    public function guardar_editado($id)
    {
        $disponible =  Disponible::findOrFail($id);

        $cliente = Cliente::find($this->es_cliente_id);

        $empleado = User::find($this->es_empleado_id);

        $servicio = Servicio::find($this->es_servicio_id);

        DB::table('disponibles')
              ->where('id', $id)
              ->update([
                'cliente_id' => $cliente->id,
                'cliente' => $cliente->nombre.' '.$cliente->apellido,
                'empleado_id' => $empleado->id,
                'empleado' => $empleado->name,
                'cod_servicio' => $servicio->cod_servicio,
                'servicio_id' => $servicio->id,
                'servicio' => $servicio->descripcion,
                'costo' => $servicio->costo,
            ]);

        DB::table('detalle_asignacions')
              ->where('cod_asignacion', $disponible->cod_asignacion)
              ->update([
                'cod_servicio' => $servicio->cod_servicio,
                'empleado_id' => $empleado->id,
                'empleado' => $empleado->name,
                'servicio_id' => $servicio->id,
                'servicio' => $servicio->descripcion,
                'cliente_id' => $cliente->id,
                'cliente' => $cliente->nombre.' '.$cliente->apellido,
                'costo' => $servicio->costo,
            ]);

        $this->forceClose()->closeModal();

        $this->redirect('/cabinas');


    }

    public function otro_tecnico_asignar()
    {
        $this->validate();

        try {

            $data = Disponible::where('cod_asignacion', $this->disponible->cod_asignacion)->first();

            $existe = Disponible::where('empleado_id', $this->empleado_id)->where('status', 'activo')->first();

            $cliente = Cliente::where('id', $data->cliente_id)->first();
            $servicio = Servicio::where('id', $this->servicio_id)->first();
            $empleado = User::where('id', $this->empleado_id)->first();

            $disponible = new Disponible();
            $disponible->cod_asignacion     = 'Pca-'.random_int(11111111, 99999999);
            $disponible->cliente_id         = $data->cliente_id;
            $disponible->cliente            = $cliente->nombre . ' ' . $cliente->apellido;
            $disponible->empleado_id        = $this->empleado_id;
            $disponible->empleado           = $empleado->name;
            $disponible->area_trabajo       = $empleado->area_trabajo;
            $disponible->cod_servicio       = $servicio->cod_servicio;
            $disponible->servicio_id        = $this->servicio_id;
            $disponible->servicio           = $servicio->descripcion;
            $disponible->servicio_categoria = $servicio->categoria;
            $disponible->servicio_asignacion = $servicio->asignacion;
            $disponible->costo              = $servicio->costo;

            if($existe == null)
            {
                $disponible->save();

                Notification::make()
                    ->title('Cliente asignado con éxito')
                    ->icon('heroicon-o-shield-check')
                    ->iconColor('danger')
                    ->body('El cliente será atendido por: ' . $disponible->empleado)
                    ->send();

                $this->forceClose()->closeModal();

                /**
                 * Cargamos el servicio principal asigando
                 * en la tabla de detalle de asignacion
                 */
                $detalle_asignacion = new ModelsDetalleAsignacion();
                $detalle_asignacion->cod_asignacion     = $disponible->cod_asignacion;
                $detalle_asignacion->cod_servicio       = $disponible->cod_servicio;
                $detalle_asignacion->empleado_id        = $disponible->empleado_id;
                $detalle_asignacion->empleado           = $disponible->empleado;
                $detalle_asignacion->cliente_id         = $disponible->cliente_id;
                $detalle_asignacion->cliente            = $disponible->cliente;
                $detalle_asignacion->servicio_id        = $disponible->servicio_id;
                $detalle_asignacion->servicio           = $servicio->descripcion;
                $detalle_asignacion->servicio_categoria = $servicio->categoria;
                $detalle_asignacion->costo              = $disponible->costo;
                $detalle_asignacion->fecha              = date('d-m-Y');
                $detalle_asignacion->save();

                $this->redirect('/cabinas');

            }else{

                $this->notification([
                    'title'       => 'Acción no permitida!',
                    'description' => 'El tecnico debe cerrar el servicio anterior.',
                    'icon'        => 'error'
                ]);

            }

        } catch (\Throwable $th) {
            dd($th);
        }

    }

    public function eliminar_servicio($value)
    {
        $this->dialog()->confirm([
            'title'       => 'seguro deseas eliminar el servicio?',
            'description' => 'Esta operación por seguridad no podra ser reversada.',
            'acceptLabel' => 'Si, eliminar',
            'method'      => 'delete',
            'params'      => $value,
        ]);
    }

    public function delete($value)
    {
        try {
            $item = ModelsDetalleAsignacion::where('id', $value)->update(['status' => 3]);
        } catch (\Throwable $th) {
            dd($th);
        }

    }

    public function cerrar_servicio()
    {

        Debugbar::info($this->disponible->cod_asignacion);

            /**
             * Calculo del total de venta para ser guardado
             * en la tabla de ventas
             */
            $total = DB::table('detalle_asignacions')
                ->select(DB::raw('SUM(costo) as total'))
                ->where('cod_asignacion', $this->disponible->cod_asignacion)
                ->where('status', '1')
                ->first();

            /**
             * Cargo la venta en la tabla de ventas
             */

            $venta_servicio = new VentaServicio();
            $venta_servicio->cod_asignacion     = $this->disponible->cod_asignacion;
            $venta_servicio->cliente            = $this->disponible->cliente;
            $venta_servicio->cliente_id         = $this->disponible->cliente_id;
            $venta_servicio->empleado           = $this->disponible->empleado;
            $venta_servicio->empleado_id        = $this->disponible->empleado_id;
            $venta_servicio->fecha_venta        = date('d-m-Y');
            $venta_servicio->total_USD          = $total->total;
            $venta_servicio->save();

            Disponible::where('cod_asignacion', $this->disponible->cod_asignacion)
            ->update([
                    'costo' => $total->total,
                    'status' => 'por facturar'
                ]);

            /**Pregunto por el servicio en la tabla de detalle de asignacion usando el 'cod_asignacion' */
            /**1.- Consulta el servicio */
            $duracion = ModelsDetalleAsignacion::where('cod_asignacion', $this->disponible->cod_asignacion)->first();

            /**2.- Calculo la duracion del servicio, es decir, el tiempo que duro el tecnico con el cliente */
            $inicio = new DateTime($duracion->created_at);
            $final  = new DateTime($venta_servicio->created_at);
            $intervalo = date_diff($inicio, $final);
            $tiempo_final = $intervalo->format('%I');

            /**3.- Guardo la duracion del servicio en la tabla de ventas */
            VentaServicio::where('cod_asignacion', $this->disponible->cod_asignacion)->update([
                'duracion' => $tiempo_final
            ]);

            /**
             * Actualizamos en contador para el numero de visitas
             * del cliente
             */
            $visitas = Cliente::where('id', $this->disponible->cliente_id)->first();
            Cliente::where('id', $this->disponible->cliente_id)
                ->update([
                    'visitas' => $visitas->visitas + 1
                ]);

            $this->forceClose()->closeModal();

            Notification::make()
                ->title('Operación exitosa!!')
                ->icon('heroicon-o-shield-check')
                ->body('El servicio fue cerrado de forma correcta. Deberá realizar su facturacion a la brevedad posible.')
                ->send();

            $user = User::where('id', $venta_servicio->empleado_id)->first();
            $detalle = ModelsDetalleAsignacion::where('cod_asignacion', $venta_servicio->cod_asignacion)->get();
            $type = 'servicio';
            $mailData = [
                'codigo' => $venta_servicio->cod_asignacion ,
                'user_email' => $user->email,
                'user_fullname' => $venta_servicio->empleado,
                'cliente_fullname' => $venta_servicio->cliente,
                'fecha_venta' => $venta_servicio->fecha_venta,
                'detalle' => $detalle,
            ];

            NotificacionesController::notification($mailData, $type);

            $this->redirect('/cabinas');
    }

    public function carga_servicios_adicionales()
    {

        $tasa_bcv = TasaBcv::where('id', 1)->first()->tasa;

        $valores = [];

        for ($i=0; $i < count($this->servicios) ; $i++)
        {

            $data_servicios = Servicio::where('id', $this->servicios[$i])->first();
            $detalle_asignacion = new ModelsDetalleAsignacion();
            $detalle_asignacion->cod_asignacion     = $this->disponible->cod_asignacion;
            $detalle_asignacion->cod_servicio       = $this->disponible->cod_servicio;
            $detalle_asignacion->empleado_id        = $this->disponible->empleado_id;
            $detalle_asignacion->empleado           = $this->disponible->empleado;
            $detalle_asignacion->cliente_id         = $this->disponible->cliente_id;
            $detalle_asignacion->cliente            = $this->disponible->cliente;
            $detalle_asignacion->servicio_id        = $data_servicios->id;
            $detalle_asignacion->servicio           = $data_servicios->descripcion;
            $detalle_asignacion->servicio_categoria = $data_servicios->categoria;
            $detalle_asignacion->costo              = $data_servicios->costo;
            $detalle_asignacion->fecha              = date('d-m-Y');
            $detalle_asignacion->save();

            $this->reset(['atr', 'grid']);

        }

    }

    public function agregar_servicio()
    {
        session(['cod_asignacion' => $this->disponible->cod_asignacion]);

        $this->redirect('/agregar/servicios');
    }

    public function agregar_productos()
    {
        session(['cod_asignacion' => $this->disponible->cod_asignacion]);

        $this->redirect('/agregar/productos');
    }

    public function facturar_servicio()
    {
        session(['cod_asignacion' => $this->disponible->cod_asignacion]);

        $this->redirect('/caja');
    }

    public function anular_servicio()
    {
        $this->dialog()->confirm([

            'title'       => 'Notificación de sistema',
            'description' => 'Usted se dispone a realizar una anulación de servicio. Esta acción anulará el movimiento y no podra ser reversado.',
            'icon'        => 'warning',
            'accept'      => [
                'label'  => 'Si, anular servicio',
                'method' => 'anular',
                'params' => 'Saved',
            ],
            'reject' => [
                'label'  => 'No, cancelar',
                'method' => 'cancelar',

            ],

        ]);
    }

    public function anular()
    {
        try {

            /** Actualizo el estatus en la tabla disponible */
            Disponible::where('cod_asignacion', $this->disponible->cod_asignacion)->update([
                'status' => 'anulado'
            ]);

            /**
             * Actualizo el estatus en la tabla de detalle de asignacion para
             * anular los servicios cargados
             */
            $anulacion = ModelsDetalleAsignacion::where('cod_asignacion', $this->disponible->cod_asignacion)->get();
            foreach ($anulacion as $item) {
                ModelsDetalleAsignacion::where('cod_asignacion', $item->cod_asignacion)
                ->update([
                    'status' => '3'
                ]);
            }

            /** Actualizo la informacion en la tabla de ventas y coloco los calculos en cero(0) */
            VentaServicio::where('cod_asignacion', $this->disponible->cod_asignacion)
            ->update([
                'metodo_pago' => 'Anulado',
                'referencia' => 'Anulado',
                'total_USD' => 0.00,
                'comision_empleado' => 0.00,
                'comision_gerente' => 0.00,
                'responsable' => Auth::user()->name,
            ]);

            /** Notificacion para el usuario cuando su servicio fue anulado */
            $data = VentaServicio::where('cod_asignacion', $this->disponible->cod_asignacion)->first();
            $user = User::where('id', $data->empleado_id)->first();
            $type = 'servicio_anulado';
            $mailData = [
                    'codigo' => $data->cod_asignacion,
                    'user_email' => $user->email,
                    'user_fullname' => $user->name,
                    'cliente_fullname' => $data->cliente,
                    'fecha_venta' => $data->fecha_venta,
                ];

            NotificacionesController::notification($mailData, $type);

            Notification::make()
            ->title('Operación exitosa!!')
            ->icon('heroicon-o-shield-check')
            ->body('El servicio fue anulado de forma correcta.')
            ->send();

            $this->redirect('/cabinas');

        } catch (\Throwable $th) {
            dd($th);
        }

    }

    public function render()
    {
        $data = Disponible::where('cod_asignacion', $this->disponible->cod_asignacion)->first();

        $detalle = ModelsDetalleAsignacion::where('cod_asignacion', $this->disponible->cod_asignacion)
            ->where('status', '1')
            ->where('servicio_categoria', 'principal')
            ->get();

        $total_servicios = DB::table('detalle_asignacions')
            ->select(DB::raw('SUM(costo) as total'))
            ->where('cod_asignacion', $this->disponible->cod_asignacion)
            ->where('status', '1')
            ->first()
            ->total;

        /**Calculo el total de los productos */
        $total_productos = DB::table('venta_productos')
            ->select(DB::raw('SUM(total_venta) as total'))
            ->where('cod_asignacion', $this->disponible->cod_asignacion)
            ->where('status', '1')
            ->first()->total;

        $total_vista = $total_servicios + $total_productos;


        $servicios_adicionales = Servicio::Where('categoria', 'principal')
            ->Where('descripcion', 'like', "%{$this->buscar}%")
            ->orderBy('id', 'desc')
            ->simplePaginate(4);

        /**Seleccion los productos que voy a vender y los muestro en la lista de 'Productos Adicionales para la venta' */
        $lista_prod = VentaProducto::where('cod_asignacion', $this->disponible->cod_asignacion)->where('status', 1)->with('producto')->get();

        return view('livewire.detalle-asignacion', compact('data', 'detalle', 'total_vista', 'total_productos', 'servicios_adicionales', 'lista_prod'));
    }
}
