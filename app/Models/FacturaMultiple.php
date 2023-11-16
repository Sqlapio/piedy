<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaMultiple extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'factura_multiples';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cod_asignacion',
        'responsable',
        'metodo_pago',
        'referencia',
        'fecha_venta',
        'pago_usd',
        'pago_bsd',
        'total_usd',
    ];
}
