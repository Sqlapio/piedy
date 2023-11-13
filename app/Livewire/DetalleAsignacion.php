<?php

namespace App\Livewire;

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
use App\Models\Venta;
use App\Models\VentaServicio;
use Barryvdh\Debugbar\Facades\Debugbar;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use LivewireUI\Modal\ModalComponent;
use WireUi\Traits\Actions;

class DetalleAsignacion extends ModalComponent
{
    use Actions;
    public $descripcion;
    public $referencia;

    public Disponible $disponible;

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

        if ($this->disponible->status == 'activo')
        {

            $this->forceClose()->closeModal();

            $this->dialog()->error(
                $title = 'Error !!!',
                $description = 'El servicio se encuentra activo.'
            );

        } else {
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

            $this->redirect('/cabinas');
        }
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

        //debug
        Debugbar::info($data);
        Debugbar::info($detalle);
        Debugbar::info($total_vista);

        return view('livewire.detalle-asignacion', compact('data', 'detalle', 'total_vista'));
    }
}
