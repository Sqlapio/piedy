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
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements FilamentUser
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

    /**
     * Restriccion para acceso al panel administrativo
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return str_ends_with($this->email, '@piedyadmin.com') && $this->hasVerifiedEmail();
    }


    public function clientes():HasMany
    {
        return $this->hasMany(Cliente::class, 'user_id');
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

    /**
     * Get all of the logs for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany(Log::class, 'id', 'user_id');
    }

    public function disponible(): HasOne
    {
        return $this->hasOne(Disponible::class, 'id', 'empleado_id');
    }

    /**
     * Get the rol associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rol(): HasOne
    {
        return $this->hasOne(Rol::class, 'rol_id', 'id');
    }

    /**
     * The servicios that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function servicios(): BelongsToMany
    {
        return $this->belongsToMany(Servicio::class, 'servicio_users')
        ->using(ServicioUser::class) 
        ->withPivot(['descripcion']);
    }

    /**
     * Get all of the comments for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class, 'empleado_id', 'id');
    }

    /**
     * Get all of the disponible for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function disponibles(): HasMany
    {
        return $this->hasMany(Disponible::class, 'empleado_id', 'id');
    }

    /**
     * Get all of the disponible for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detalleAsignaciones(): HasMany
    {
        return $this->hasMany(DetalleAsignacion::class, 'empleado_id', 'id');
    }

}