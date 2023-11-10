<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DetalleAsignacion extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'detalle_asignacions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cod_asignacion',
        'cod_servicio',
        'empleado_id',
        'empleado',
        'servicio_id',
        'servicio',
        'cliente_id',
        'cliente',
        'costo',
        'fecha',
        'status',
    ];

    public function get_detalle_asiganciones(): HasMany
    {
        return $this->hasMany(Cliente::class, 'cliente_id', 'id');
    }
}
