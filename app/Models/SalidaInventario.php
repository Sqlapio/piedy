<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SalidaInventario extends Model
{
    use HasFactory;
    protected $fillable = [
        'producto_id',
        'almacen_id',
        'sucursal_id',
        'cantidad',
        'responsable',
        'tipo_movimiento',
    ];

    /**
     * Get the compra that owns the Proveedor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function producto(): HasOne
    {
        return $this->hasOne(Producto::class, 'id', 'producto_id');
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
     * Get the compra that owns the Proveedor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sucursal(): HasOne
    {
        return $this->hasOne(Sucursal::class, 'id', 'sucursal_id');
    }
}