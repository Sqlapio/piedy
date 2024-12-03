<?php

namespace App\Filament\Widgets;

use App\Models\Cita;
use App\Models\Gasto;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\TasaBcv;
use App\Models\Producto;
use App\Models\Disponible;
use App\Models\Frecuencia;
use App\Models\VentaProducto;
use App\Models\VentaServicio;
use App\Models\DetalleAsignacion;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;


class StatsGeneral extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;


    protected function getStats(): array
    {
        if($this->filters['activar'] == false)
        {
            $rangeStartDate = now()->startOfDay();
            $rangeEndDate = now()->endOfDay();
            $rango = date('d-m-Y', strtotime($rangeStartDate)).' al '.date('d-m-Y', strtotime($rangeEndDate));

        }else{
            $rangeStartDate = $this->filters['startDate'].' 00:00:00.000';
            $rangeEndDate = $this->filters['endDate'].'. 23:59:59.000';
            $rango = date('d-m-Y', strtotime($rangeStartDate)).' al '.date('d-m-Y', strtotime($rangeEndDate));
        }

        /**
         * CALCULO PARA LA ULITIDAD NETA:
         * -------------------------------
         * -------------------------------
         */
        $tasa   = TasaBcv::first()->tasa;
        $ventas = Venta::whereBetween('created_at',[$rangeStartDate, $rangeEndDate])->sum('total_venta');

        //Comisiones almacenadas por la venta de servicios
        $comisiones_serv_usd      = VentaServicio::whereBetween('created_at',[$rangeStartDate, $rangeEndDate])->sum('comision_dolares');
        $comisiones_serv_usd_gte  = VentaServicio::whereBetween('created_at',[$rangeStartDate, $rangeEndDate])->sum('comision_gerente');
        $comisiones_serv_bsd      = VentaServicio::whereBetween('created_at',[$rangeStartDate, $rangeEndDate])->sum('comision_bolivares');

        //Conversion de los bolivares a dolares
        $conver_comisiones_serv_bsd_usd = $comisiones_serv_bsd / $tasa;

        //Comisiones almacenadas por la venta de productos
        $comisiones_prod_emp_usd  = VentaProducto::whereBetween('created_at',[$rangeStartDate, $rangeEndDate])->sum('comision_empleado');
        $comisiones_prod_gte_usd  = VentaProducto::whereBetween('created_at',[$rangeStartDate, $rangeEndDate])->sum('comision_gerente');

        //Calculo de los gastos
        $gastos_usd = Gasto::whereBetween('created_at',[$rangeStartDate, $rangeEndDate])->sum('monto_usd');
        $gastos_bsd = Gasto::whereBetween('created_at',[$rangeStartDate, $rangeEndDate])->sum('monto_bsd');

        //Comisiones de gastos de bolivares a dolares
        $conver_gastos_bsd_usd = $gastos_bsd / $tasa;
        
        $utilidad_neta      = $ventas - $comisiones_serv_usd - $comisiones_serv_usd_gte - $conver_comisiones_serv_bsd_usd - $comisiones_prod_emp_usd - $comisiones_prod_gte_usd - $gastos_usd - $conver_gastos_bsd_usd;
        
        //----------------------------------------------------------------------------------------------------------------------------------

        
        //Ingresos totales
        $ingresos_totales   = $ventas;

        //Total de servicios realizado, tabla detalle_asignations
        $total_servicio_realizados = DetalleAsignacion::whereBetween('created_at',[$rangeStartDate, $rangeEndDate])->count();

        
        /**
         * Calculo del ingreso en divisas
         * -----------------------------------------
         * -----------------------------------------
         */
        //servicios: este calculo incluye el efectivo usd y el zelle
        $serv_usd = VentaServicio::whereBetween('created_at',[$rangeStartDate, $rangeEndDate])->sum('pago_usd');

        //productos: este calculo incluye el efectivo usd y el zelle
        $prod_usd = VentaProducto::whereBetween('created_at',[$rangeStartDate, $rangeEndDate])->sum('montoUsd');

        //Total
        $ingresos_divisas = $serv_usd + $prod_usd;
        //------------------------------------------------------------------------------------------------------------------

        
        /**
         * Calculo del promedio venta servicios
         * -----------------------------------------
         * -----------------------------------------
         */
        //Numero de clientes
        $total_clientes = VentaServicio::whereBetween('created_at',[$rangeStartDate, $rangeEndDate])
        ->groupBy('cliente_id')
        ->count();

        //Promedio de ventas por cliente
        $promedio_venta_servicios = $ingresos_totales / 1;

        //------------------------------------------------------------------------------------------------------------------

        
        /**
         * TASA RETENCION DE CLINETES
         * -----------------------------------------
         * -----------------------------------------
         */
        $visitas    = Cliente::sum('visitas');
        $clientes   = Cliente::count();

        $promedio_visitas = $visitas / $clientes;

        $clientes_recurentes = Cliente::where('visitas','>=', $promedio_visitas)->count();

        $tasa_retencion_clientes = ($clientes_recurentes / $clientes) * 100;
        //------------------------------------------------------------------------------------------------------------------

        
        /**
         * % OCUPACION CITAS
         * -----------------------------------------
         * -----------------------------------------
         */
        //Espacios disponibles en la agenda = 44 espacios

        //Arreglo de horas
        $array_hrs = [
            '10:00 am',
            '11:00 am',
            '12:00 am',
            '01:00 pm',
            '02:00 pm',
            '04:00 pm',
            '06:00 pm',
            '07:00 pm',
            '08:00 pm',
            '09:00 pm',
        ];
    
        $nro_citas_agendadas = [];
    
        for ($i=0; $i < count($array_hrs); $i++) { 
            # code...
            $count = Cita::where('hora', $array_hrs[$i])->whereBetween('created_at',[$rangeStartDate, $rangeEndDate])->count();
            array_push($nro_citas_agendadas, $count);
        }
        
        $ocupacion_citas = (array_sum($nro_citas_agendadas) / 44) * 100;
        //------------------------------------------------------------------------------------------------------------------


        /**
         * CLIENTES NUEVOS
         * -----------------------------------------
         * -----------------------------------------
         */
        $clientes_nuevos = Cliente::whereBetween('created_at',[$rangeStartDate, $rangeEndDate])->count();
        //------------------------------------------------------------------------------------------------------------------


        /**
         * INGRESOS EN BOLIVARES
         * -----------------------------------------
         * -----------------------------------------
         */
        //Ingreso en bolivares por servicios
        $ingresos_serv_bsd = VentaServicio::whereBetween('created_at',[$rangeStartDate, $rangeEndDate])->sum('pago_bsd');

        //Ingreso en bolivares por productos
        $ingresos_prod_bsd = VentaProducto::whereBetween('created_at',[$rangeStartDate, $rangeEndDate])->sum('montoBsd');

        //Total
        $ingresos_bolivares = $ingresos_serv_bsd + $ingresos_prod_bsd;
        //------------------------------------------------------------------------------------------------------------------

        /**
         * SERVICIOS VIP
         * -----------------------------------------
         * -----------------------------------------
         */
        $servicios_vip = Disponible::whereBetween('servicio_id',[2,3,4,5,6,7,8])->count();
        //------------------------------------------------------------------------------------------------------------------
        

        /**
         * GASTOS TOTALES
         * -----------------------------------------
         * -----------------------------------------
         */
        //Calculo de los gastos registrados en la tabla de gastos
        $gastos_usd = Gasto::whereBetween('created_at',[$rangeStartDate, $rangeEndDate])->sum('monto_usd');
        $gastos_bsd = Gasto::whereBetween('created_at',[$rangeStartDate, $rangeEndDate])->sum('monto_bsd');

        //Calculo de los gastos registrados en la tabla de compras
        $gastos_cmp_usd = Gasto::whereBetween('created_at',[$rangeStartDate, $rangeEndDate])->sum('monto_usd');
        $gastos_cmp_bsd = Gasto::whereBetween('created_at',[$rangeStartDate, $rangeEndDate])->sum('monto_bsd');

        //Comisiones de gastos de bolivares a dolares
        $conver_gastos_bsd_usd = $gastos_bsd / $tasa;
        $conver_gastos_cmp_bsd = $gastos_cmp_bsd / $tasa;

        $gastos_totales = $gastos_usd + $conver_gastos_bsd_usd + $conver_gastos_cmp_bsd;
        //------------------------------------------------------------------------------------------------------------------

        
        /**
         * TASA DE AUSENCIA DE LOS CLIENTES
         * -----------------------------------------
         * -----------------------------------------
         */
        $visitas    = Cliente::sum('visitas');
        $clientes   = Cliente::count();

        $promedio_visitas = $visitas / $clientes;

        $clientes_recurentes = Cliente::where('visitas',1)->count();

        $tasa_aucencia_clientes = ($clientes_recurentes / $clientes) * 100;
        //------------------------------------------------------------------------------------------------------------------


        /**
         * PORCENTAJE DE SATISFACIION DEL CLIENTES
         * -----------------------------------------
         * -----------------------------------------
         */
        //cantidad de clientes que estan por sobre el promedio de visitas
        $valor = Cliente::where('visitas','>', $promedio_visitas)->count();

        $tasa_satisfaccion_clientes = ($valor / $clientes) * 100;
        //------------------------------------------------------------------------------------------------------------------


        /**
         * VENTA PRODUCTOS
         * -----------------------------------------
         * -----------------------------------------
         */
        //cantidad de clientes que estan por sobre el promedio de visitas
        $venta_productos = VentaProducto::whereBetween('created_at',[$rangeStartDate, $rangeEndDate])->sum('total_venta');
        //------------------------------------------------------------------------------------------------------------------


        /**
         * INVENTARIO PRODUCTOS
         * -----------------------------------------
         * -----------------------------------------
         */
        //cantidad de clientes que estan por sobre el promedio de visitas


        
        //------------------------------------------------------------------------------------------------------------------


        return [

            /**
             * GRUPO 1:
             * -----------
             */
            Stat::make('SERVICIOS', $total_servicio_realizados)
                // ->description($rango)
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->color('success')
                ->extraAttributes([
                    'class' => 'border-2 border-[#7B9EA6]',
                ]),

            Stat::make('SERVICIOS VIP', $servicios_vip)
                // ->description($rango)
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->color('success')
                ->extraAttributes([
                    'class' => 'border-2 border-[#7B9EA6]',
                ]),

            Stat::make('PROMEDIO VENTA SERVICIOS', '$ '.number_format($promedio_venta_servicios, 2, '.', ','))
                // ->description($rango)
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->color('success')
                ->extraAttributes([
                    'class' => 'border-2 border-[#7B9EA6]',
                ]),
                
            //--------------------------------------------------------------------------------------

            /**
             * GRUPO 2:
             * -----------
             */
            Stat::make('INGRESOS DIVISAS', '$ '.number_format($ingresos_divisas, 2, '.', ','))
                // ->description($rango)
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->color('success')
                ->extraAttributes([
                    'class' => 'border-2 border-[#7B9EA6]',
                ]),

            Stat::make('INGRESO EN BOLIVARES', 'Bs. '.number_format($ingresos_bolivares, 2, '.', ','))
                // ->description($rango)
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->color('success')
                ->extraAttributes([
                    'class' => 'border-2 border-[#7B9EA6]',
                ]),

            Stat::make('INGRESOS TOTALES', '$ '.number_format($ingresos_totales, 2, '.', ','))
                // ->description($rango)
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->color('success')
                ->extraAttributes([
                    'class' => 'border-2 border-[#7B9EA6]',
                ]),
                
            //--------------------------------------------------------------------------------------
            
            /**
             * GRUPO 3:
             * -----------
             */
            Stat::make('VENTA PRODUCTOS', '$ '.number_format($venta_productos, 2, '.', ','))
                // ->description($rango)
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->color('success')
                ->extraAttributes([
                    'class' => 'border-2 border-[#7B9EA6]',
                ]),

            Stat::make('COMISIONES', $total_servicio_realizados)
                // ->description($rango)
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->color('success')
                ->extraAttributes([
                    'class' => 'border-2 border-[#7B9EA6]',
                ]),

            Stat::make('GATOS TOTALES', $gastos_totales)
                // ->description($rango)
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->color('success')
                ->extraAttributes([
                    'class' => 'border-2 border-[#7B9EA6]',
                ]),
                
            //--------------------------------------------------------------------------------------

        ];
    }

    public function getColumns(): int
    {
        return 3;
    }
}