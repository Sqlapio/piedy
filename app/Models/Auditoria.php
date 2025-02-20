<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Auditoria extends Model
{
    use HasFactory;

    protected $table = 'auditorias';

    protected $fillable = [
        'cod_auditoria',
        'fecha_ini',
        'fecha_fin',
        'sucursal_id',
        'producto_id',
        'contenido_neto',
        'unidad',
        'cantidad_solicitada',
        'gasto_total_usd',
        'consumo_por_servicios',
        'servicios_realizados',
        'existencia_sucursal',
        'existencia_central',
        'responsable',
        'observaciones',
    ];

    /**
     * Get the user that owns the Auditoria
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'id');
    }

    //RELACION UNO A UNO CON LA TABLA DE SUCURSALS
    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id', 'id');
    }
}