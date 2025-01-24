<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DetalleRequisicion extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'requisicion_id',
        'sucursal_id',
        'producto_id',
        'cantidad',
        'uso',
        'status',
        'costo',
        'existencia',
        'almacen_id',
        'observacion'
    ];

    /**
     * Get the user associated with the Requisicion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sucursal(): HasOne
    {
        return $this->hasOne(Sucursal::class, 'id', 'sucursal_id');
    }

    /**
     * Get the user associated with the Requisicion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function producto(): HasOne
    {
        return $this->hasOne(Producto::class, 'id', 'producto_id');
    }

    /**
     * Get the user that owns the DetalleRequisicion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function requisicion(): BelongsTo
    {
        return $this->belongsTo(Requisicion::class, 'id', 'requisicion_id');
    }

    /**
     * Get the user associated with the Requisicion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function almacen(): HasOne
    {
        return $this->hasOne(Almacen::class, 'id', 'almacen_id');
    }
}