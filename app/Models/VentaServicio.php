<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VentaServicio extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'venta_servicios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cod_asignacion',
        'cliente',
        'cliente_id',
        'empleado',
        'empleado_id',
        'fecha_venta',
        'metodo_pago',
        'metodo_pago_dos',
        'referencia',
        'comision_empleado',
        'comision_gerente',
        'comision_dolares',
        'comision_bolivares',
        'total_USD',
        'pago_usd',
        'pago_bsd',
        'propina_usd',
        'propina_bsd',
        'referencia_propina',
        'responsable',
        'impuesto_usd',
        'base_imponible_usd',
        'base_imponible_bsd',
        'iva_usd',
        'iva_bsd',
    ];

    public function detalle_asignaciones():HasMany
    {
        return $this->hasMany(DetalleAsignacion::class, 'cod_asignacion', 'cod_asignacion');
    }

    /**
     * Get the membresia associated with the Cliente
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class, 'id', 'cliente_id');
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

    /**
     * Get the user that owns the VentaServicio
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function empleado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'empleado_id', 'id');
    }

}