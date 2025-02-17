<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Venta extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'ventas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cod_asignacion',
        'total_venta',
        'fecha',
        'responsable',
        'forma_pago',
        'metodo_pago_dolares',
        'metodo_pago_bolivares',
        'pago_usd',
        'pago_bsd',
        'tasa_bcv',
        'sucursal_id'
    ];

    // Una Venta pertenece a un Cliente
    public function cliente():BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }

    // Una Venta es realizada por un Empleado
    public function empleado():BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'empleado_id', 'id');
    }

    public function producto():BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'id');
    }

    public function comision():BelongsTo
    {
        return $this->belongsTo(Comision::class, 'comision_id', 'id');
    }

    /**
     * Get the user associated with the Venta
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sucursal(): HasOne
    {
        return $this->hasOne(Sucursal::class, 'id', 'sucursal_id');
    }

    public function ventaServicio(): HasOne
    {
        return $this->hasOne(VentaServicio::class, 'cod_asignacion', 'cod_asignacion');
    }
}