<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Membresia extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'membresias';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cod_membresia',
        'pm',
        'cliente_id',
        'fecha_activacion',
        'fecha_exp',
        'monto',
        'barcode',
        'status',
        'responsable',
    ];

    /**
     * Get the cliente that owns the Membresia
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }

    /**
     * Get all of the movimientos_membresia for the Membresia
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movimientos_membresia(): HasMany
    {
        return $this->hasMany(MovimientoMembresia::class, 'id', 'membresia_id');
    }
}
