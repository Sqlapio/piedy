<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetalleAuditoriaServicio extends Model
{
    use HasFactory;

    protected $fillable = [
        'auditoria_inventario_id',
        'servicio',
        'cantidad',
    ];

    /**
     * Get the user that owns the DetalleAuditoriaServicio
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function auditoriaInventario(): BelongsTo
    {
        return $this->belongsTo(AuditoriaInventario::class, 'foreign_key', 'other_key');
    }
}