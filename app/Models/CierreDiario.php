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
