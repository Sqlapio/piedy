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
        'precio_venta',
        'existencia',
        'fecha_carga',
        'unidad',
        'contenido_neto',
        'comision_venta_emp',
        'comision_venta_gte',
        'image',
        'status',
        'responsable',
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
    public function get_productos_asignados():HasMany
    {
        return $this->hasMany(AsignarProducto::class, 'id', 'producto_id');
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

    /**
     * Get all of the ventas for the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ventas(): HasMany
    {
        return $this->hasMany(VentaProducto::class, 'id', 'producto_id');
    }

}
