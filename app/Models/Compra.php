<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'descripcion',
        'monto_neto_usd',
        'monto_neto_bsd',
        'iva',
        'monto_bruto',
        'forma_pago',
        'fecha_factura',
        'responsable',
        'numero_factura',
        'numero_factura_gasto',
        'proveedor'
    ];
}
