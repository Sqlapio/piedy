<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'responsable'
    ];

    public function detalle_asignacions():HasMany
    {
        return $this->hasMany(DetalleAsignacion::class, 'cod_asignacion', 'cod_asignacion');
    }

}
