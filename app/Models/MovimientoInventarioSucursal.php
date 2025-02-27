<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoInventarioSucursal extends Model
{
    use HasFactory;

    protected $table = 'movimiento_inventario_sucursals';
    
    protected $fillable = [
        'sucursal_id',
        'producto_id',
        'cantidad',
        'tipo_movimiento',
        'consumo',
        'responsable',
    ];

    //Relacion inversa de UNO a MUCHOS con la tabla de productos
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'id');
    }

    //relacion Uno a Uno con la tabla sucursals
    public function sucursal(): HasOne
    {
        return $this->hasOne(Sucursal::class, 'id', 'sucursal_id');
    }
}