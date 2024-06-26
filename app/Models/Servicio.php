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
        'asignacion',
        'duracion_max', //este valor debe ser expresado en minutos
        'categoria',
        'tipo_servicio_id',
        'status',
    ];

    public function disponible(): BelongsTo
    {
        return $this->belongsTo(Disponible::class);
    }

    public function cita(): HasOne
    {
        return $this->hasOne(Cita::class);
    }

    /**
     * Get the tipo_servicio associated with the Servicio
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function tipo_servicio(): BelongsTo
    {
        return $this->belongsTo(TipoServicio::class, 'tipo_servicio_id', 'id');
    }

}
