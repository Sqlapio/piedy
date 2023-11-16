<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'productos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cod_producto',
        'categoria_id',
        'descripcion',
        'proveedor',
        'precio_venta',
        'existencia',
        'fecha_carga',
        'comision_id',
        'image',
        'status',
    ];

    public function comision():BelongsTo
    {
        return $this->belongsTo(Comision::class, 'comision_id', 'id');
    }

    public function categoria():BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id', 'id');
    }

    /**
     * Relacion Uno a Muchos
     *
     * Un producto tiene Muchos movimiento de entrada
     */
    public function get_entradas():HasMany
    {
        return $this->hasMany(MovimientoEntrada::class, 'producto_id', 'id');
    }

    /**
     * Relacion Uno a Muchos
     *
     * Un producto tiene Muchos movimiento de salida
     */
    public function get_salidas():HasMany
    {
        return $this->hasMany(MovimientoSalida::class, 'producto_id', 'id');
    }

}
