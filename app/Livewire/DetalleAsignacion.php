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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;
use LivewireUI\Modal\ModalComponent;
use WireUi\Traits\Actions;


class DetalleAsignacion extends ModalComponent
{
    use Actions;

    use WithPagination;

    public $descripcion;
    public $referencia;
    public $buscar;
    public $atr = 'hidden';
    public $grid = 'grid-cols-1';
    public $servicios = [];


    public Disponible $disponible;

    // public static function modalMaxWidth(): string
    // {
    //     return 'xl';
    // }

    public function visible()
    {
        $this->atr = '';
        $this->grid = 'grid-cols-2';
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
