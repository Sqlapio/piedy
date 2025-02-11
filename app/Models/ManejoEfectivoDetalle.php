<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ManejoEfectivoDetalle extends Model
{
    use HasFactory;

    protected $table = 'manejo_efectivo_detalles';

    protected $fillable = [
        'manejo_efectivo_id',
        'monto',
        'deduccion',
        'fecha',
        'observacion',
        'total',
    ];

    /**
     * Get the user that owns the ManejoEfectivoDetalle
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ManejoEfectivo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manejo_efectivo_id', 'id');
    }
}