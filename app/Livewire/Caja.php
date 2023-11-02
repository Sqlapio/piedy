<?php

namespace App\Livewire;

use App\Http\Controllers\UtilsController;
use App\Models\DetalleAsignacion;
use App\Models\VentaServicio;
use Barryvdh\Debugbar\Facades\Debugbar;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Rule;
use WireUi\Traits\Actions;
use Livewire\Component;

class Caja extends Component
{

    use Actions;


    public $dolares;
    public $bolivares;

    #[Rule('required')]
    public $referencia;

    public $descripcion;
    public $usd_hidden = 'hidden';
    public $bsd_hidden = 'hidden';

    protected $messages = [

        'dolares'     => 'Campo requerido',

    ];

    public function event()
    {
        if($this->descripcion == 'Pago móvil'){
            $this->usd_hidden = '';
            $this->bsd_hidden = '';
        }

        if($this->descripcion == 'Punto de venta'){
            $this->usd_hidden = '';
            $this->bsd_hidden = '';
        }

        if($this->descripcion == ''){
            $this->reset(['usd_hidden', 'bsd_hidden']);
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
            $item = DetalleAsignacion::where('id', $value)->update(['status' => 3]);
        } catch (\Throwable $th) {
            dd($th);
        }
        
    }

    public function facturar_servicio()
    {
        
        $this->validate();

        $item = VentaServicio::all()->last();

        $total = DB::table('detalle_asignacions')
            ->select(DB::raw('SUM(costo) as total'))
            ->where('cod_asignacion', $item->cod_asignacion)
            ->where('status', '2')
            ->first();

        $total_vista = $total->total;

        if(isset($this->dolares) && isset($this->bolivares))
        {
            $pago_usd = $this->dolares;
            $pago_bsd = $this->bolivares;

            $facturar = DB::table('venta_servicios')
            ->where('cod_asignacion', $item->cod_asignacion)
                ->update([
                    'metodo_pago' => $this->descripcion,
                    'referencia' => $this->referencia,
                    'total_USD' => $total_vista,
                    'pago_usd' => $pago_usd,
                    'pago_bsd' => $pago_bsd,
                    'comision_empleado' => UtilsController::cal_comision_empleado($total_vista),
                    'comision_gerente' => UtilsController::cal_comision_gerente($total_vista),
                ]);

            Notification::make()
                ->title('La factura fue cerrada con exito')
                ->success()
                ->send();

            $this->redirect('/citas');

        }else{

            $facturar = DB::table('venta_servicios')
                ->where('cod_asignacion', $item->cod_asignacion)
                ->update([
                    'metodo_pago' => $this->descripcion,
                    'referencia' => $this->referencia,
                    'pago_usd' => $total_vista,
                    'comision_empleado' => UtilsController::cal_comision_empleado($total_vista),
                    'comision_gerente' => UtilsController::cal_comision_gerente($total_vista),
                ]);

            Notification::make()
                ->title('La factura fue cerrada con exito')
                ->success()
                ->send();

            $this->redirect('/citas');
        }

    }

    public function render()
    {
        $this->event();

        $data = VentaServicio::all()->last();

        $detalle = DetalleAsignacion::where('cod_asignacion', $data->cod_asignacion)
        ->where('status', '2')
        ->get();

        $total = DB::table('detalle_asignacions')
            ->select(DB::raw('SUM(costo) as total'))
            ->where('cod_asignacion', $data->cod_asignacion)
            ->where('status', '2')
            ->first();

        $total_vista = $total->total;

        return view('livewire.caja', compact('data', 'detalle', 'total_vista'));
    }
}
