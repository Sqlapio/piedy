<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndicadorVentaGerente extends Model
{
    use HasFactory;
    /**
     * Define table
     */
    protected $table = 'indicador_venta_gerentes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'empleado_id',
        'gift_card_vendidas',
        'membresias_vendidas',
        'servicios_vip_vendidos',
        'dias_trabajados',
        'fecha_ini',
        'fecha_fin',
        'fecha',
        'codigo_quincena',
        'numero_quincena',
        'mes',
        'responsable',
    ];

    /**
     * Get the user that owns the IndicadorVentaGerente
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'empleado_id', 'id');
    }
}
