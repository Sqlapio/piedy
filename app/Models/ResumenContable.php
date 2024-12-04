<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResumenContable extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'compra_id',
        'gasto_id',
        'sucursal_id',
        'codigo',
        'tipo',
        'monto_usd',
        'monto_bsd',
        'tasa_bcv',
        'conversion',
        'total_operacion',
        'responsable',
        'fecha',
    ];
    
    /**
     * Get the compra associated with the ResumenContable
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function compra(): HasOne
    {
        return $this->hasOne(Compra::class, 'id', 'compra_id');
    }

    /**
     * Get the gasto associated with the ResumenContable
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sucursal(): HasOne
    {
        return $this->hasOne(Sucursal::class, 'id', 'sucursal_id');
    }
}