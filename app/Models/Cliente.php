<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    ];

    public function citas():HasMany
    {
        return $this->hasMany(Cita::class, 'cliente_id');
    }

    public function ventas():HasMany
    {
        return $this->hasMany(Venta::class, 'cliente_id');
    }
}
