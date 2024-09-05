<?php

namespace App\Livewire;

use App\Models\CierreFinanciero as ModelsCierreFinanciero;
use App\Models\DetalleAsignacion;
use App\Models\GiftCard;
use App\Models\Membresia;
use App\Models\NominaGeneral;
use App\Models\Producto;
use App\Models\TasaBcv;
use App\Models\VentaProducto;
use App\Models\VentaServicio;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Component;
use WireUi\Traits\Actions;

class CierreFinanciero extends Component
{
    use Actions;

    #[Rule('required', message: 'Campo obligatorio')]
    public $costo_operativo;

    #[Rule('required', message: 'Campo obligatorio')]
    public $periodo;

    #[Rule('required', message: 'Campo obligatorio')]
    public $desde;

    #[Rule('required', message: 'Campo obligatorio')]
    public $hasta;

    public function notificacion_cierre()
    {
        $this->validate();

        $this->dialog()->confirm([

                'title'       => 'Notificación de sistema',
                'description' => 'Usted se dispone a realizar un cierre financiero para el periodo de nomina seleccionado. Esta acción totaliza los movimientos generados y almacenados a lo largo del mes en curso.',
                'icon'        => 'warning',
                'accept'      => [
                    'label'  => 'Si, realizar cierre',
                    'method' => 'ejecutar_cierre',
                    'params' => 'Saved',
                ],
                'reject' => [
                    'label'  => 'No, cancelar',
                    'method' => 'cancelar',

                ],

            ]);
    }

    public function cancelar()
    {
        $this->redirect('/c/f');
    }


    function ejecutar_cierre()
    {

        try {

            $tasa_bcv = TasaBcv::where('id', 1)->first()->tasa;

            $total_ingreso_bolivares     = VentaServicio::whereBetween('created_at', [$this->desde, $this->hasta])->sum('pago_bsd');
            $total_ingreso_bolivares_conversion = $total_ingreso_bolivares / $tasa_bcv;

            $total_ingreso_dolares       = VentaServicio::whereBetween('created_at', [$this->desde, $this->hasta])->sum('pago_usd');

            $total_servicios             = DetalleAsignacion::whereBetween('created_at', [$this->desde, $this->hasta])->count();

            $total_clientes_atendidos    = VentaServicio::whereBetween('created_at', [$this->desde, $this->hasta])->count('cliente');

            $total_membresias_vendidas   = Membresia::whereBetween('created_at', [$this->desde, $this->hasta])->sum('monto');

            $total_gif_card_vendidas_usd = GiftCard::whereBetween('created_at', [$this->desde, $this->hasta])->sum('pago_usd');

            $total_gif_card_vendidas_bsd = GiftCard::whereBetween('created_at', [$this->desde, $this->hasta])->sum('pago_bsd');
            $total_gif_card_vendidas_bsd_conversion = $total_gif_card_vendidas_bsd / $tasa_bcv;

            $total_productos_vendidos    = VentaProducto::whereBetween('created_at', [$this->desde, $this->hasta])->sum('total_venta');

            /**Comisiones de los empleados */
            $nomina_empleados  = NominaGeneral::where('cod_quincena', $this->periodo)->first();

            $total_comisiones_bolivares = $nomina_empleados->total_bolivares;
            $total_comisiones_bolivares_conversion = $total_comisiones_bolivares / $tasa_bcv;

            $total_comisiones_dolares    = $nomina_empleados->total_dolares;

            /**Indicador de Inventario */
            $inventario = Producto::where('existencia', '=', 0)->count();
            if($inventario > 0){
                $indicador_inventario = 1;
            }else{
                $indicador_inventario = 0;
            }

            /**Calculos Generales */

            $total_general_ventas   = $total_ingreso_bolivares_conversion + $total_ingreso_dolares + $total_gif_card_vendidas_bsd_conversion + $total_gif_card_vendidas_bsd + $total_productos_vendidos + $total_membresias_vendidas;

            $utilidad_real          = $total_general_ventas - $total_comisiones_bolivares_conversion - $total_comisiones_dolares - $this->costo_operativo;

            /**Cargamos la informacion en la base de datos */
            $cierre_financiero = new ModelsCierreFinanciero();
            $cierre_financiero->total_general_ventas        = $total_general_ventas;
            $cierre_financiero->total_ingreso_bolivares     = $total_ingreso_bolivares;
            $cierre_financiero->total_ingreso_dolares       = $total_ingreso_dolares;
            $cierre_financiero->total_servicios             = $total_servicios;
            $cierre_financiero->total_clientes_atendidos    = $total_clientes_atendidos;
            $cierre_financiero->total_membresias_vendidas   = $total_membresias_vendidas;
            $cierre_financiero->total_gif_card_vendidas     = $total_gif_card_vendidas_usd + $total_gif_card_vendidas_bsd_conversion;
            $cierre_financiero->total_productos_vendidos    = $total_productos_vendidos;
            $cierre_financiero->total_costos_operativos     = $this->costo_operativo;
            $cierre_financiero->total_general_comiciones    = $total_comisiones_bolivares_conversion + $total_comisiones_dolares;
            $cierre_financiero->total_comisiones_bolivares  = $total_comisiones_bolivares;
            $cierre_financiero->total_comisiones_dolares    = $total_comisiones_dolares;
            $cierre_financiero->indicador_inventario        = $indicador_inventario;
            $cierre_financiero->utilidad_real               = $utilidad_real;
            $cierre_financiero->tasa_bcv                    = $tasa_bcv;
            $cierre_financiero->fecha                       = now()->format('d-m-Y');
            $cierre_financiero->fecha_ini                   = $this->desde;
            $cierre_financiero->fecha_fin                   = $this->hasta;
            $cierre_financiero->codigo_quincena             = $this->periodo;
            $cierre_financiero->responsable                 = Auth::user()->name;
            $cierre_financiero->save();

            //code...
        } catch (\Throwable $th) {
            dd($th);
        }

    }

    public function render()
    {
        return view('livewire.cierre-financiero');
    }
}
