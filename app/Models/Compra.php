<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Compra extends Model
{
use HasFactory;

/**
* The attributes that are mass assignable.
*
* @var array<int, string>
    */
    protected $fillable = [
    'cod_compra',
    'proveedor_id',
    'descripcion',
    'monto_usd',
    'monto_bsd',
    'forma_pago',
    'fecha_compra',
    'responsable',
    'numero_factura_compra',
    'observacion',
    'metodo_pago',
    'sucursal_id',
    'tasa_bcv',
    'iva',
    'total_compra_bsd',
    'conversion_a_usd',
    ];

    /**
    * Get all of the proveedores for the Compra
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    /**
    * Get the compra that owns the Proveedor
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function sucursal(): HasOne
    {
        return $this->hasOne(Sucursal::class, 'id', 'sucursal_id');
    }

    /**
     * Get the resumen that owns the Gasto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resumen_contables(): BelongsTo
    {
        return $this->belongsTo(ResumenContable::class, 'compra_id', 'id');
    }

}