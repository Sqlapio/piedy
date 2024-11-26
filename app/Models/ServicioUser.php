<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ServicioUser extends Pivot
{
    /**
     * Define table
     */
    protected $table = 'servicio_users';

    public static function booted(): void
    {
        static::creating(function ($record) 
        {
            $record->descripcion =  Servicio::where('id', $record->servicio_id)->first()->descripcion;
        });
    }


}