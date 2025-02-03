<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NominaGeneral extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'nomina_generals';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fecha',
        'quincena',
        'cod_nomina',
        'total_bolivares',
        'total_dolares',
        'total_general',
        'tasa_bcv',
        'status_id',
        'fecha_ini',
        'fecha_fin',
        'responsable',
        'sucursal_id',
        'conversion_usd'
    ];
    
    //Relacion 1-M con la tabla de prenomina
    public function preNominas(): HasMany
    {
        return $this->hasMany(PreNomina::class, 'nomina_general_id', 'id');
    }

    //Relacion 1-M con la tabla de reportes
    public function reportes(): HasMany
    {
        return $this->hasMany(Reporte::class, 'nomina_general_id', 'id');
    }

    /**
     * Get the user that owns the Statu
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(): HasOne
    {
        return $this->hasOne(Statu::class, 'id', 'status_id');
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