<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gasto extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'gastos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'descripcion',
        'monto_usd',
        'monto_bsd',
        'forma_pago',
        'fecha_factura',
        'responsable',
        'numero_factura',
        'numero_factura_gasto',
        'sucursal_id',
        'proveedor_id',
        'fecha',
        'metodo_pago',
        'observacion',
        'tasa_bcv',
        'almacen_id',
        'total_gasto_bsd',
        'iva',
        'conversion_a_usd',
        'nro_control'
    ];

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
     * Get the compra that owns the Proveedor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function almacen(): HasOne
    {
        return $this->hasOne(Almacen::class, 'id', 'almacen_id');
    }

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
     * Get the resumen that owns the Gasto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resumen_contables(): BelongsTo
    {
        return $this->belongsTo(ResumenContable::class, 'gasto_id', 'id');
    }
}