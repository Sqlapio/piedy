<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'tipo_usuario',
        'area_trabajo',
        'tipo_servicio_id',
        'salario',
        'sucursal_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function clientes():HasMany
    {
        return $this->hasMany(Cliente::class, 'user_id');
    }

    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class, 'user_id');
    }

    /**
     * Get the user that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipo_servicio(): BelongsTo
    {
        return $this->belongsTo(TipoServicio::class, 'tipo_servicio_id', 'id');
    }

    /**
     * Get all of the comments for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function asignaciones(): HasMany
    {
        return $this->hasMany(AsignarProducto::class, 'id', 'user_id');
    }

    /**
     * Get all of the membresias for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function membresias(): HasMany
    {
        return $this->hasMany(MovimientoMembresia::class, 'id', 'user_id');
    }

    /**
     * Get all of the reportes for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reportes(): HasMany
    {
        return $this->hasMany(Reporte::class, 'id', 'user_id');
    }

    /**
     * Get the primeraAsignacion associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function primeraAsignacion(): HasOne
    {
        return $this->hasOne(Disponible::class, 'id', 'empleado_id');
    }

    /**
     * Get all of the comments for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ventasProductos(): HasMany
    {
        return $this->hasMany(VentaProducto::class, 'id', 'empleado_id');
    }

    /**
     * Get all of the comments for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function indicadores(): HasMany
    {
        return $this->hasMany(IndicadorVentaGerente::class, 'id', 'empleado_id');
    }

    /**
     * Get the user associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sucursal(): HasOne
    {
        return $this->hasOne(Sucursal::class, 'id', 'sucursal_id');
    }
}
