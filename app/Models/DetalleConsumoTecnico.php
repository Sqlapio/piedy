<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleConsumoTecnico extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'producto_id',
        'promedio_servicios',
        'user_id',
        'cantidad',
        'fecha_entrega',
        'tipo_movimiento',
        'fecha_reposicion',
        'total_servicios_realizados',
        'responsable_id',
    ];

    
    
}