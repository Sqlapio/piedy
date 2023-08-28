<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'cliente_id',
        'empleado_id',
        'producto_id',
        'comision_id',
        'fecha',
        'total'
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
}


