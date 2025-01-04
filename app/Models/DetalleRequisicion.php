<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'status'
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

    // public function requisicion(): HasOne
    // {
    //     return $this->hasOne(Requisicion::class, 'id', 'requisicion_id');
    // }
}