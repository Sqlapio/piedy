<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MovimientoSalida extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'movimiento_salidas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'producto_id',
        'categoria_id',
        'cantidad_saliente',
        'fecha',
        'responsable'
    ];

    /**
     * Get all of the comments for the MovimientoSalida
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function get_producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'id');
    }
}
