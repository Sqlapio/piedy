<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductoServicio extends Pivot
{
    /**
     * Define table
     */
    protected $table = 'producto_servicios';

    public static function booted(): void
    {
        static::creating(function ($record) {
            $record->descripcion =  Servicio::where('id', $record->servicio_id)->first()->descripcion;
        });
    }
}