<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    /**
     * Get the ficha that owns the Cliente
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ficha(): BelongsTo
    {
        return $this->belongsTo(FichaMedica::class, 'id', 'cliente_id');
    }

    /**
     * Get the membresia associated with the Cliente
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function membresia(): HasOne
    {
        return $this->hasOne(Membresia::class, 'id', 'cliente_id');
    }

    /**
     * Get the membresia associated with the Cliente
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function giftCards(): HasMany
    {
        return $this->hasMany(GiftCard::class, 'id', 'cliente_id');
    }
}
