<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reporte extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'reportes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'cod_reporte',
        'descripcion',
        'fecha_ini',
        'fecha_fin',
        'tipo',
        'responsable',
        'sucursal_id',
        'cod_nomina',
        'nomina_general_id'
    ];

    /**
     * Get the user that owns the Reporte
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sucursal(): HasOne
    {
        return $this->hasOne(Sucursal::class, 'id', 'sucursal_id');
    }

    /**
     * Get the user that owns the Reporte
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
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