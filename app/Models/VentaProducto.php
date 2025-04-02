<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'responsable',
        'sucursal_id',
        'impuesto_igft',
        'base_imponible_usd',
        'base_imponible_bsd',
        'iva_usd',
        'iva_bsd',
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

    /**
     * Get the sucursal associated with the VentaProducto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sucursal(): HasOne
    {
        return $this->hasOne(Sucursal::class, 'id', 'sucursal_id');
    }

    //Uno a Uno clientes
    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class, 'id', 'cliente_id');
    }
}