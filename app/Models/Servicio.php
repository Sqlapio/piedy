<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Servicio extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'servicios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cod_servicio',
        'descripcion',
        'costo',
        'duracion_max', //este valor debe ser expresado en minutos
        'status',
    ];

    // public function comision():BelongsTo
    // {
    //     return $this->belongsTo(Comision::class, 'comision_id', 'id');
    // }

    public function disponible(): HasOne
    {
        return $this->hasOne(Disponible::class, 'id', 'servicio_id');
    }

    public function cita(): HasOne
    {
        return $this->hasOne(Cita::class);
    }

}
