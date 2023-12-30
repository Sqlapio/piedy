<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cliente extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'clientes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nombre',
        'apellido',
        'cedula',
        'email',
        'telefono',
        'direccion_corta',
        'visitas'

    ];

    public function get_citas(): HasMany
    {
        return $this->hasMany(Cita::class, 'cliente_id', 'id');
    }

    public function ventas():HasMany
    {
        return $this->hasMany(Venta::class, 'cliente_id');
    }

    public function get_detalle_asignaciones(): HasMany
    {
        return $this->hasMany(DetalleAsignacion::class, 'id', 'cliente_id');
    }

    public function get_disponibles(): HasMany
    {
        return $this->hasMany(Disponible::class, 'cliente_id', 'id');
    }

    /**
     * Get the user associated with the Frecuencia
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function frecuencia(): HasOne
    {
        return $this->hasOne(Frecuencia::class, 'id', 'cliente_id');
    }
}
