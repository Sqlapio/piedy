<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CierreDiario extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'cierre_diarios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [

        'total_pagos_ef_usd',
        'total_pagos_ef_bsd',
        'total_pagos_ze',
        'total_pagos_pm',
        'total_pagos_tr',
        'total_pagos_pv',
        'total_pago_usd',
        'total_pago_bsd',
        'total_gastos_pago_usd',
        'total_gastos_pago_bsd',
        'venta_neta_usd',
        'venta_neta_bsd',
        'fecha',
        'responsable',
        'observaciones',
    ];
}
