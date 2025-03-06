<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetalleAsignacion extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'detalle_asignacions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cod_asignacion',
        'cod_prod_serv',
        'empleado_id',
        'servicio_id',
        'cliente_id',
        'producto_id',
        'costo',
        'fecha',
        'status',
        'sucursal_id',
        'tipo'
    ];

    public function ventaServicios(): BelongsTo
    {
        return $this->belongsTo(VentaServicio::class, 'cod_asignacion', 'cod_asignacion');
    }

    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class, 'id', 'cliente_id');
    }

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'empleado_id', 'id');
    }

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class, 'servicio_id', 'id');
    }

    /**
     * Get the user that owns the Disponible
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'empleado_id', 'id');
    }

    /**
     * Get the user that owns the Disponible
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function disponible(): BelongsTo
    {
        return $this->belongsTo(Disponible::class, 'cod_asignacion', 'cod_asignacion');
    }

    /**
     * Get the user that owns the Disponible
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function detalleServicio(): BelongsTo
    {
        return $this->belongsTo(DetalleAsignacion::class, 'servicio_id', 'id');
    }

    public function sucursal(): HasOne
    {
        return $this->hasOne(Sucursal::class, 'id', 'sucursal_id');
    }

    public function producto(): HasOne
    {
        return $this->hasOne(Producto::class, 'id', 'producto_id');
    }
}