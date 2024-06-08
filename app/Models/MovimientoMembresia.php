<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoMembresia extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'movimiento_membresias';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'membresia_id',
        'user_id',
        'empleado',
        'descripcion',
        'cliente_id',
        'cliente',
        'cedula',
        'responsable',
    ];

    /**
     * Get the user that owns the MovimientoMembresia
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }



}
