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
        'total_venta',
        'total_pago_usd',
        'total_pago_bsd',
        'total_gastos',
        'fecha',
        'responsable',
    ];
}
