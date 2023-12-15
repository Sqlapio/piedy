<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaServicioOnline extends Model
{
    use HasFactory;

    protected $connection = 'mysql_online';

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
        'referencia',
        'comision_empleado',
        'comision_gerente',
        'total_USD',
        'total_BSD',
        'responsable'
    ];
}
