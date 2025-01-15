<?php

namespace App\Filament\Resources\DetalleEsGanPerResource\Pages;

use Filament\Actions;
use App\Models\TasaBcv;
use App\Models\PreNomina;
use App\Models\DetalleEsGanPer;
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
        // $data = DetalleEsGanPer::find($this->record->id);
        // dd($this->data['fecha_ini'], $this->data['fecha_fin'], $data);
        $cal_ing_usd = PreNomina::where('sucursal_id', $this->data['sucursal_id'])
            ->where('status', 2)
            ->whereBetween('created_at', [$this->data['fecha_ini'] . ' 07:00:00.000', $this->data['fecha_fin'] . ' 23:59:59.000'])
            ->sum('total_usd');

        $cal_ing_bsd = PreNomina::where('sucursal_id', $this->data['sucursal_id'])
        ->where('status', 2)
        ->whereBetween('created_at', [$this->data['fecha_ini'] . ' 07:00:00.000', $this->data['fecha_fin'] . ' 23:59:59.000'])
        ->sum('total_bsd');

        $total_ingresos = $cal_ing_usd + ($cal_ing_bsd / TasaBcv::where("fecha", date('d-m-Y'))->first()->tasa);

        dd($cal_ing_usd, $cal_ing_bsd, $total_ingresos);
    }
}