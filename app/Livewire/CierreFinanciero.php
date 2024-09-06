<?php

namespace App\Livewire;

use App\Models\CierreFinanciero as ModelsCierreFinanciero;
use App\Models\DetalleAsignacion;
use App\Models\GiftCard;
use App\Models\IndicadorVentaGerente;
use App\Models\Membresia;
use App\Models\MovimientoMembresia;
use App\Models\NominaGeneral;
use App\Models\PeriodoNomina;
use App\Models\Producto;
use App\Models\TasaBcv;
use App\Models\User;
use App\Models\VentaProducto;
use App\Models\VentaServicio;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Rule;
use Livewire\Component;
use WireUi\Traits\Actions;

class CierreFinanciero extends Component
{
    use Actions;

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
            $quincena = PeriodoNomina::where('cod_quincena', $this->periodo)->first();

            if($quincena->numero_quincena == 2 && $this->costo_operativo == '')
            {
                throw new Exception("Esta realizando el calculo para el cierre financiero, debe cargar el costo operativo", 401);
            }


            $servicios_ingreso_bolivares                = VentaServicio::whereBetween('created_at', [$this->desde, $this->hasta])->sum('pago_bsd');
            $servicios_ingreso_bolivares_conversion     = $servicios_ingreso_bolivares / $tasa_bcv;
            $servicios_ingreso_dolares                  = VentaServicio::whereBetween('created_at', [$this->desde, $this->hasta])->sum('pago_usd');
            $total_servicios                            = DetalleAsignacion::whereBetween('created_at', [$this->desde, $this->hasta])->count();
            $total_clientes_atendidos                   = VentaServicio::whereBetween('created_at', [$this->desde, $this->hasta])->count('cliente');
            $total_membresias_vendidas_usd              = Membresia::whereBetween('created_at', [$this->desde, $this->hasta])->sum('pago_usd');
            $membresias_vendidas_bsd                    = Membresia::whereBetween('created_at', [$this->desde, $this->hasta])->sum('pago_bsd');
            $total_membresias_vendidas_bsd_conversion   = $membresias_vendidas_bsd / $tasa_bcv;
            $total_gif_card_vendidas_usd                = GiftCard::whereBetween('created_at', [$this->desde, $this->hasta])->sum('pago_usd');
            $total_gif_card_vendidas_bsd                = GiftCard::whereBetween('created_at', [$this->desde, $this->hasta])->sum('pago_bsd');
            $total_gif_card_vendidas_bsd_conversion     = $total_gif_card_vendidas_bsd / $tasa_bcv;
            $total_productos_vendidos                   = VentaProducto::whereBetween('created_at', [$this->desde, $this->hasta])->sum('total_venta');

            /**Comisiones de los empleados */
            $nomina_empleados  = NominaGeneral::where('cod_quincena', $this->periodo)->first();
            $total_comisiones_bolivares = $nomina_empleados->total_bolivares;
            $total_comisiones_bolivares_conversion = $total_comisiones_bolivares / $tasa_bcv;
            $total_comisiones_dolares    = $nomina_empleados->total_dolares;

            /**Indicador de Inventario */
            $inventario = Producto::whereBetween('updated_at', [$this->desde, $this->hasta])->where('existencia', '=', 0)->count();
            if($inventario > 0){
                $indicador_inventario = 1;
            }else{
                $indicador_inventario = 0;
            }

            /**Calculos Generales */

            $total_general_ventas   = $servicios_ingreso_bolivares_conversion
            + $servicios_ingreso_dolares
            + $total_gif_card_vendidas_bsd_conversion
            + $total_gif_card_vendidas_usd
            + $total_productos_vendidos
            + $total_membresias_vendidas_usd
            + $total_membresias_vendidas_bsd_conversion;

            if($quincena->numero_quincena == 2){
                $utilidad_real = $total_general_ventas - $total_comisiones_bolivares_conversion - $total_comisiones_dolares - $this->costo_operativo;
            }else{
                $utilidad_real = $total_general_ventas - $total_comisiones_bolivares_conversion - $total_comisiones_dolares;

            }

            /**Cargamos la informacion en la base de datos
             * LLenamos la tabla de cierre financiero
            */
            $cierre_financiero = new ModelsCierreFinanciero();
            $cierre_financiero->total_general_ventas        = $total_general_ventas;
            $cierre_financiero->total_ingreso_bolivares     = $servicios_ingreso_bolivares + $total_gif_card_vendidas_bsd + $membresias_vendidas_bsd ;
            $cierre_financiero->total_ingreso_dolares       = $servicios_ingreso_dolares + $total_gif_card_vendidas_usd + $total_membresias_vendidas_usd;
            $cierre_financiero->total_servicios             = $total_servicios;
            $cierre_financiero->total_clientes_atendidos    = $total_clientes_atendidos;
            $cierre_financiero->total_membresias_vendidas   = $total_membresias_vendidas_usd + $total_membresias_vendidas_bsd_conversion;
            $cierre_financiero->total_gif_card_vendidas     = $total_gif_card_vendidas_usd + $total_gif_card_vendidas_bsd_conversion;
            $cierre_financiero->total_productos_vendidos    = $total_productos_vendidos;
            $cierre_financiero->total_costos_operativos     = ($this->costo_operativo != '') ? $this->costo_operativo : 0.00;
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
            $cierre_financiero->numero_quincena             = $quincena->numero_quincena;
            $cierre_financiero->mes                         = $quincena->mes;
            $cierre_financiero->responsable                 = Auth::user()->name;
            $cierre_financiero->save();

            /**LLenamos la tabla de indicadores de gerente */
            $gerentes = User::where('tipo_servicio_id', 3)->where('status', 1)->get();
            foreach($gerentes as $item)
            {
                $gift_card_vendidas     = GiftCard::whereBetween('created_at', [$this->desde, $this->hasta])->where('empleado_id', $item->id)->count();
                $membresias_vendidas    = MovimientoMembresia::whereBetween('created_at', [$this->desde, $this->hasta])->where('empleado_id', $item->id)->count();
                $servicios_vip_vendidos = VentaServicio::where('responsable_id', $item->id)->where('comision_gerente', '!=', 'NULL')->whereBetween('created_at', [$this->desde, $this->hasta])->count();
                $productos_vendidos     = VentaProducto::where('empleado_id', $item->id)->sum('cantidad');
                $dias_trabajados        = DB::table('venta_servicios')
                ->select('responsable_id', 'fecha_venta')
                ->where('responsable_id', 38)
                ->whereBetween('created_at',['2024-08-01 00:00:00', '2024-08-15 23:59:59'])
                ->groupBy('fecha_venta', 'responsable_id')
                ->get();

                $indicador = new IndicadorVentaGerente();
                $indicador->empleado_id = $item->id;
                $indicador->gift_card_vendidas = $gift_card_vendidas;
                $indicador->membresias_vendidas = $membresias_vendidas;
                $indicador->servicios_vip_vendidos = $servicios_vip_vendidos;
                $indicador->productos_vendidos = $productos_vendidos;
                $indicador->dias_trabajados = count($dias_trabajados);
                $indicador->fecha_ini = $this->desde;
                $indicador->fecha_fin = $this->hasta;
                $indicador->fecha = now()->format('d-m-Y');
                $indicador->codigo_quincena = $this->periodo;
                $indicador->numero_quincena = $cierre_financiero->numero_quincena;
                $indicador->mes = $cierre_financiero->mes;
                $indicador->responsable = Auth::user()->name;
                $indicador->save();

            }

            $this->reset();

            Notification::make()
            ->title('NOTIFICACIÓN')
            ->icon('heroicon-o-document-text')
            ->iconColor('info')
            ->color('info')
            ->body('El cierre financiero fue ejecutado de forma correcta.')
            ->send();

            //code...
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

    public function render()
    {
        return view('livewire.cierre-financiero');
    }
}
