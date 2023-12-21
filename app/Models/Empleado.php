<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Empleado extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'empleados';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'area_trabajo',
        'tipo_servicio_id',
        'tipo_usuario',
        'password',
    ];

    // Un empleado puede tener múltiples comisiones
    public function comisiones():HasMany
    {
        return $this->hasMany(Comision::class, 'empleado_id');
    }

    // Un empleado puede haber prestado múltiples servicios
    public function serviciosPrestados():HasMany
    {
        return $this->hasMany(ServicioPrestado::class, 'empleado_id');
    }

    // Un empleado puede haber registrado múltiples ventas
    public function ventas():HasMany
    {
        return $this->hasMany(Venta::class, 'empleado_id');
    }

    public function disponible(): HasOne
    {
        return $this->hasOne(Disponible::class);
    }

    public function cita(): HasOne
    {
        return $this->hasOne(Cita::class);
    }
}
