<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VentaProducto extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'venta_productos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cod_asignacion',
        'rol',
        'producto_id',
        'empleado_id',
        'comision_empleado',
        'comision_gerente',
        'fecha_venta',
        'costo_producto',
        'total_venta',
        'cantidad',
        'responsable'
    ];

    /**
     * Get the user that owns the VentaProducto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function empleado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'empleado_id', 'id');
    }

    /**
     * Get the user that owns the VentaProducto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'id');
    }

}
