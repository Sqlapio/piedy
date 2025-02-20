<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MovimientoInventario extends Model
{
    use HasFactory;

    protected $table = 'movimiento_inventarios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'producto_id',
        'almacen_id',
        'venta_producto_id',
        'cod_asignacion',
        'cantidad',
        'contenido_neto',
        'unidad',
        'tipo_movimiento',
        'responsable',
    ];

    /**
     * Get the producto that owns the MovimientoInventario
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Get the ventaProducto associated with the MovimientoInventario
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ventaProducto(): HasOne
    {
        return $this->hasOne(User::class, 'foreign_key', 'local_key');
    }

    /**
     * Get the sucursal that owns the MovimientoInventario
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }

    /**
     * Get the almacen that owns the MovimientoInventario
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function almacen(): BelongsTo
    {
        return $this->belongsTo(Almacen::class);
    }
}