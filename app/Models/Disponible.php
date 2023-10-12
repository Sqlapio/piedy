<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Disponible extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'disponibles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cliente_id',
        'empleado_id',
        'servicio_id',
        'costo',
        'duracion',
        'cabina',
        'status',
    ];

    

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
    
    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class);
    }
}
