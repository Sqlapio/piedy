<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'cliente',
        'empleado_id',
        'empleado',
        'servicio_id',
        'servicio',
        'costo',
        'duracion',
        'cubiculo_mesa',
        'status',
    ];

    

    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class, 'cliente_id', 'id');
    }
    
    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    public function servicios(): HasMany
    {
        return $this->hasMany(Servicio::class, 'id', 'servicio_id');
    }
}
