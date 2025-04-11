<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetalleMovimientoInventarioGeneral extends Model
{
    use HasFactory;

    protected $table = 'detalle_movimiento_inventario_generals';

    protected $fillable = [
        'auditoria_inventario_id',
        'producto_id',
        'cantidad',
        'fecha_movimiento',
        'costo',
        'total'
    ];

    /**
     * Get the user that owns the DetalleAuditoriaServicio
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function auditoriaInventario(): BelongsTo
    {
        return $this->belongsTo(AuditoriaInventario::class);
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