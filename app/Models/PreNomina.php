<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PreNomina extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'pre_nominas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'rol_id',
        'sucursal_id',
        'total_servicios',
        'total_productos',
        'comision_usd',
        'comision_bsd',
        'comision_prod',
        'propinas_usd',
        'propinas_bsd',

        'asignaciones_usd',
        'asignaciones_bsd',
        'deducciones_usd',
        'deducciones_bsd',

        'fecha_ini',
        'fecha_fin',
        'total_usd',
        'total_bsd',
        'status',

        'total_venta_sin_iva',
        'iva',
        'retencion_isrl',
        'total_pagar_bsd',

        'conversion_a_usd',
        'total_general_usd',

        'cod_nomina',
        'nomina_general_id'
    ];

    /**
     * Get the user associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Get the user associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rol(): HasOne
    {
        return $this->hasOne(Rol::class, 'id', 'rol_id');
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
     * Get the user that owns the PreNomina
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nominaGeneral(): BelongsTo
    {
        return $this->belongsTo(NominaGeneral::class, 'id', 'nomina_general_id');
    }
}