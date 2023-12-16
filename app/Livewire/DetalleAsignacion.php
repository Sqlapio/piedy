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
use App\Models\VentaServicio;
use Barryvdh\Debugbar\Facades\Debugbar;
use Carbon\Carbon;
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
                 * Guardamos la asignacion directa en la
                 * tabla de citas para llevar el control
                 * de la entrada de los clientes
                 */
                $user = Auth::user();

                $cita = new Cita();
                $cita->cod_cita = 'Pci-'.random_int(11111, 99999);
                $cita->fecha = date('d-m-Y');
                $cita->hora = date('HH:i');
                $cita->cliente_id = $data->cliente_id;
                $cita->servicio_id = $this->servicio_id;
                $cita->status = 2;
                $cita->responsable = $user->id;
                $cita->save();

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
            $venta_servicio->comision_empleado  = UtilsController::cal_comision_empleado($total->total);
            $venta_servicio->comision_gerente   = UtilsController::cal_comision_gerente($total->total);
            $venta_servicio->save();

            Disponible::where('cod_asignacion', $this->disponible->cod_asignacion)
            ->update([
                    'costo' => $total->total,
                    'status' => 'por facturar'
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
        /** Actualizo el estatus en la tabla disponible */
        Disponible::where('cod_asignacion', $this->disponible->cod_asignacion)->update([
            'status' => 'anulado'
        ]);

        /**
         * Actualizo el estatus en la tabla de detalle de asignacion para
         * anular los servicios cargados
         */
        $anulacion = ModelsDetalleAsignacion::where('cod_asignacion', $this->disponible->cod_asignacion)->get();
        foreach($anulacion as $item){
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

        Notification::make()
                ->title('Operación exitosa!!')
                ->icon('heroicon-o-shield-check')
                ->body('El servicio fue anulado de forma correcta.')
                ->send();

        $this->redirect('/cabinas');

    }

    public function render()
    {
        $data = Disponible::where('cod_asignacion', $this->disponible->cod_asignacion)->first();

        $detalle = ModelsDetalleAsignacion::where('cod_asignacion', $this->disponible->cod_asignacion)
            ->where('status', '1')
            ->where('servicio_categoria', 'principal')
            ->get();

        $total = DB::table('detalle_asignacions')
            ->select(DB::raw('SUM(costo) as total'))
            ->where('cod_asignacion', $this->disponible->cod_asignacion)
            ->where('status', '1')
            ->first();

        $total_vista = $total->total;

        $servicios_adicionales = Servicio::Where('categoria', 'principal')
            ->Where('descripcion', 'like', "%{$this->buscar}%")
            ->orderBy('id', 'desc')
            ->simplePaginate(4);

        return view('livewire.detalle-asignacion', compact('data', 'detalle', 'total_vista', 'servicios_adicionales'));
    }
}
