<?php

namespace App\Livewire;

use App\Http\Controllers\NotificacionesController;
use App\Http\Controllers\UtilsController;
use App\Models\Cliente;
use App\Models\DetalleAsignacion;
use App\Models\Disponible;
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

class AgregraServicios extends Component
{

    use Actions;

    use WithPagination;

    public $buscar;
    public $servicios = [];

    public function cerrar_servicio(Request $request)
    {

        $codigo = $request->session()->all();
        $data = Disponible::where('cod_asignacion', $codigo['cod_asignacion'])->first();

        /**
         * Calculo del total de venta para ser guardado
         * en la tabla de ventas
         */
        $total = DB::table('detalle_asignacions')
        ->select(DB::raw('SUM(costo) as total'))
        ->where('cod_asignacion', $codigo['cod_asignacion'])
            ->where('status', '1')
            ->first();

        /**
         * Cargo la venta en la tabla de ventas
         */

        $venta_servicio = new VentaServicio();
        $venta_servicio->cod_asignacion     = $codigo['cod_asignacion'];
        $venta_servicio->cliente            = $data->cliente;
        $venta_servicio->cliente_id         = $data->cliente_id;
        $venta_servicio->empleado           = $data->empleado;
        $venta_servicio->empleado_id        = $data->empleado_id;
        $venta_servicio->fecha_venta        = date('d-m-Y');
        $venta_servicio->total_USD          = $total->total;
        $venta_servicio->comision_empleado  = UtilsController::cal_comision_empleado($total->total);
        $venta_servicio->comision_gerente   = UtilsController::cal_comision_gerente($total->total);
        $venta_servicio->save();

        Disponible::where('cod_asignacion', $codigo['cod_asignacion'])
            ->update([
                'costo' => $total->total,
                'status' => 'por facturar'
            ]);

        /**
         * Actualizamos en contador para el numero de visitas
         * del cliente
         */
        $visitas = Cliente::where('id', $data->cliente_id)->first();
        Cliente::where('id', $data->cliente_id)
            ->update([
                'visitas' => $visitas->visitas + 1
            ]);


        Notification::make()
            ->title('Operación exitosa!!')
            ->icon('heroicon-o-shield-check')
            ->body('El servicio fue cerrado de forma correcta. Deberá realizar su facturacion a la brevedad posible.')
            ->send();

        $user = User::where('id', $data->empleado_id)->first();
        $detalle = DetalleAsignacion::where('cod_asignacion', $codigo['cod_asignacion'])->get();
        $type = 'servicio';
        $mailData = [
            'codigo' => $codigo['cod_asignacion'],
            'user_email' => $user->email,
            'user_fullname' => $venta_servicio->empleado,
            'cliente_fullname' => $venta_servicio->cliente,
            'fecha_venta' => $venta_servicio->fecha_venta,
            'detalle' => $detalle,
        ];

        NotificacionesController::notification($mailData, $type);

        $this->redirect('/cabinas');
    }

    public function carga_servicios_adicionales(Request $request)
    {
        if(count($this->servicios) == 0)
        {
            $this->dialog()->warning(
                $title = 'NOTIFICACION !!!',
                $description = 'Debes seleccionar al menos un(1) servício. Por favor vuelva a intentar'
            );
        }else{
            $codigo = $request->session()->all();

            $data = Disponible::where('cod_asignacion', $codigo['cod_asignacion'])->first();

            $tasa_bcv = TasaBcv::where('id', 1)->first()->tasa;

            for ($i=0; $i < count($this->servicios) ; $i++)
            {
                $data_servicios = Servicio::where('id', $this->servicios[$i])->first();
                $detalle_asignacion = new DetalleAsignacion();
                $detalle_asignacion->cod_asignacion     = $codigo['cod_asignacion'];
                $detalle_asignacion->cod_servicio       = $data->cod_servicio;
                $detalle_asignacion->empleado_id        = $data->empleado_id;
                $detalle_asignacion->empleado           = $data->empleado;
                $detalle_asignacion->cliente_id         = $data->cliente_id;
                $detalle_asignacion->cliente            = $data->cliente;
                $detalle_asignacion->servicio_id        = $data_servicios->id;
                $detalle_asignacion->servicio           = $data_servicios->descripcion;
                $detalle_asignacion->servicio_categoria = $data_servicios->categoria;
                $detalle_asignacion->costo              = $data_servicios->costo;
                $detalle_asignacion->fecha              = date('d-m-Y');
                $srv = DetalleAsignacion::where('cod_asignacion', $codigo['cod_asignacion'])->where('servicio_id', $data_servicios->id)->where('status', 1)->first();
                if(isset($srv->servicio_id))
                {
                    if($srv->servicio_id == $data_servicios->id){
                        $this->dialog()->error(
                            $title = 'Error !!!',
                            $description = 'Servicio duplicado. Estas intentando agregar un servicio que ya se encuentra asignado.'
                        );
                    }

                }else{
                    $detalle_asignacion->save();
                }

            }
            $this->servicios = [];
        }

    }

    public function cerrar()
    {
        $this->dialog()->confirm([

            'title'       => 'NOTIFICACIÓN !!!',
            'description' => 'Después de cerrado el servicio esta operación no podra ser reversada. Estás seguro?',
            'icon'        => 'question',
            'accept'      => [
                'label'  => 'Sí, cerrar servicio',
                'method' => 'cerrar_servicio',
                'params' => 'Saved',
            ]
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

    public function render(Request $request)
    {
        /**
         * El codigo es tomado de la variables de sesion
         * del usuario
         *
         * @param $codigo
         */
        $codigo = $request->session()->all();

        $data = Disponible::where('cod_asignacion', $codigo['cod_asignacion'])->first();

        $detalle = DetalleAsignacion::where('cod_asignacion', $codigo['cod_asignacion'])
            ->where('status', '1')
            ->where('servicio_categoria', 'principal')
            ->get();

        $total = DB::table('detalle_asignacions')
            ->select(DB::raw('SUM(costo) as total'))
            ->where('cod_asignacion', $codigo['cod_asignacion'])
            ->where('status', '1')
            ->first();

        $total_vista = $total->total;

        $servicios_adicionales = Servicio::Where('categoria', 'principal')
            ->Where('descripcion', 'like', "%{$this->buscar}%")
            ->orderBy('id', 'desc')
            ->simplePaginate(5);

        /** Logica para traer los servicios segun el tecnico */
        $tecnico = User::where('id', $data->empleado_id)->first();
        Debugbar::info($tecnico);
        return view('livewire.agregra-servicios', compact('data', 'detalle', 'total_vista', 'servicios_adicionales'));
    }
}
