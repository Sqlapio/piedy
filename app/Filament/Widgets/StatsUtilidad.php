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


class StatsUtilidad extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 2;


    protected function getStats(): array
    {
        /**
         * CALCULO PARA LA ULITIDAD NETA:
         * -------------------------------
         * -------------------------------
         */
        $tasa   = TasaBcv::first()->tasa;
        $ventas = Venta::whereBetween('created_at',[now()->startOfDay(), now()->endOfDay()])->sum('total_venta');

        //Comisiones almacenadas por la venta de servicios
        $comisiones_serv_usd      = VentaServicio::whereBetween('created_at',[now()->startOfDay(), now()->endOfDay()])->sum('comision_dolares');
        $comisiones_serv_usd_gte  = VentaServicio::whereBetween('created_at',[now()->startOfDay(), now()->endOfDay()])->sum('comision_gerente');
        $comisiones_serv_bsd      = VentaServicio::whereBetween('created_at',[now()->startOfDay(), now()->endOfDay()])->sum('comision_bolivares');

        //Conversion de los bolivares a dolares
        $conver_comisiones_serv_bsd_usd = $comisiones_serv_bsd / $tasa;

        //Comisiones almacenadas por la venta de productos
        $comisiones_prod_emp_usd  = VentaProducto::whereBetween('created_at',[now()->startOfDay(), now()->endOfDay()])->sum('comision_empleado');
        $comisiones_prod_gte_usd  = VentaProducto::whereBetween('created_at',[now()->startOfDay(), now()->endOfDay()])->sum('comision_gerente');

        //Calculo de los gastos
        $gastos_usd = Gasto::whereBetween('created_at',[now()->startOfDay(), now()->endOfDay()])->sum('monto_usd');
        $gastos_bsd = Gasto::whereBetween('created_at',[now()->startOfDay(), now()->endOfDay()])->sum('monto_bsd');

        //Comisiones de gastos de bolivares a dolares
        $conver_gastos_bsd_usd = $gastos_bsd / $tasa;
        
        $utilidad_neta      = $ventas - $comisiones_serv_usd - $comisiones_serv_usd_gte - $conver_comisiones_serv_bsd_usd - $comisiones_prod_emp_usd - $comisiones_prod_gte_usd - $gastos_usd - $conver_gastos_bsd_usd;
        
        //----------------------------------------------------------------------------------------------------------------------------------
        /**
         * GRUPO 4:
         * -----------
         */
        return [
            Stat::make('UTILIDAD NETA', '$ '.number_format($utilidad_neta, 2, '.', ','))
                ->extraAttributes([
                    'class' => 'bg-[#D9C3C1]',
                ])
                //--------------------------------------------------------------------------------------
        ];
    }

    public function getColumns(): int
    {
        return 6;
    }
}