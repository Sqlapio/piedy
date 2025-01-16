<?php

namespace App\Filament\Resources\DetalleEsGanPerResource\Pages;

use Filament\Actions;
use App\Models\TasaBcv;
use App\Models\PreNomina;
use App\Models\DetalleEsGanPer;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LogController;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\DetalleEsGanPerResource;

class CreateDetalleEsGanPer extends CreateRecord
{
    protected static string $resource = DetalleEsGanPerResource::class;

    //beforeCreated
    protected function beforeCreate(): void
    {
        $cal = PreNomina::where('sucursal_id', $this->data['sucursal_id'])
            ->where('status', 2)
            ->whereBetween('created_at', [$this->data['fecha_ini'] . ' 07:00:00.000', $this->data['fecha_fin'] . ' 23:59:59.000'])
            ->get();
        if (count($cal) <= 0) {
            $this->halt();
        }
    }

    //Aftercreated
    protected function afterCreate(): void
    {
        try {
            //Generamos los calulos necesarios para crear el asiento en la tabla de DetalleEsGanPer
            $cal_ing_usd = PreNomina::where('sucursal_id', $this->data['sucursal_id'])
                ->where('status', 2)
                ->whereBetween('created_at', [$this->data['fecha_ini'] . ' 07:00:00.000', $this->data['fecha_fin'] . ' 23:59:59.000'])
                ->sum('total_usd');

            $cal_ing_bsd = PreNomina::where('sucursal_id', $this->data['sucursal_id'])
                ->where('status', 2)
                ->whereBetween('created_at', [$this->data['fecha_ini'] . ' 07:00:00.000', $this->data['fecha_fin'] . ' 23:59:59.000'])
                ->sum('total_bsd');

            $total_ingresos = $cal_ing_usd + ($cal_ing_bsd / TasaBcv::where("fecha", date('d-m-Y'))->first()->tasa);

            //Comisiones
            $comision_usd = PreNomina::where('sucursal_id', $this->data['sucursal_id'])
                ->where('status', 2)
                ->whereBetween('created_at', [$this->data['fecha_ini'] . ' 07:00:00.000', $this->data['fecha_fin'] . ' 23:59:59.000'])
                ->sum('comision_usd');

            $comision_bsd = PreNomina::where('sucursal_id', $this->data['sucursal_id'])
                ->where('status', 2)
                ->whereBetween('created_at', [$this->data['fecha_ini'] . ' 07:00:00.000', $this->data['fecha_fin'] . ' 23:59:59.000'])
                ->sum('comision_bsd');

            $comision_prod = PreNomina::where('sucursal_id', $this->data['sucursal_id'])
                ->where('status', 2)
                ->whereBetween('created_at', [$this->data['fecha_ini'] . ' 07:00:00.000', $this->data['fecha_fin'] . ' 23:59:59.000'])
                ->sum('comision_prod');

            $total_comisiones = $comision_usd + ($comision_bsd / TasaBcv::where("fecha", date('d-m-Y'))->first()->tasa) + $comision_prod;

            //Creamos el asiento en la tabla de DetalleEsGanPer`

            $detalle = DetalleEsGanPer::all()->last();
            $detalle->sucursal_id = $this->data['sucursal_id'];
            $detalle->fecha_ini = $this->data['fecha_ini'];
            $detalle->fecha_fin = $this->data['fecha_fin'];
            $detalle->ingresos_usd = $cal_ing_usd;
            $detalle->ingresos_bsd = $cal_ing_bsd;
            $detalle->ingresos_totales_usd = $total_ingresos;
            $detalle->comisiones_empleados = $total_comisiones;
            $detalle->user_id = Auth::user()->id;
            $detalle->save();

            Notification::make()
                ->title('Notificacion')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->body('El asiento para el detalle del estado de ganancias y perdidas fue creado con exito')
                ->send();
            //code...
        } catch (\Throwable $th) {
            LogController::log(Auth::user()->id, 'excepcion-DetalleEsGanPerResource::CreateDetalleEsGanPer', $th->getMessage(), $response = null);
            Notification::make()
                ->title('Notificacion')
                ->icon('heroicon-o-shield-check')
                ->iconColor('danger')
                ->body($th->getMessage())
                ->send();
        }
        
    }
}