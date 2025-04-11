<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetalleMovimientoInventarioSucursal extends Model
{
    use HasFactory;

    protected $table = 'detalle_movimiento_inventario_sucursals';

    protected $fillable = [
        'auditoria_inventario_id',
        'producto_id',
        'consumo',
        'cantidad',
        'fecha_movimiento',
        'costo',
        'total',
    ];

    /**
     * Get the user that owns the DetalleAuditoriaServicio
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function auditoriaInventario(): BelongsTo
    {
        return $this->belongsTo(AuditoriaInventario::class, 'foreign_key', 'other_key');
    }

    /**
     * Get the user associated with the DetalleAuditoriaProducto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function producto(): HasOne
    {
        return $this->hasOne(Producto::class, 'id', 'producto_id');
    }
}