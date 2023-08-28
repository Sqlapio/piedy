<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'creado_por',
        'descripcion',
        'costo',
        'duracion_max', //este valor debe ser expresado en minutos
        'status',
    ];

    // Un servicio puede haber sido prestado múltiples veces
    public function serviciosPrestados():HasMany
    {
        return $this->hasMany(ServicioPrestado::class, 'servicio_id');
    }
}
