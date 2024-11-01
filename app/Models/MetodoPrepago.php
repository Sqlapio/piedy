<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MetodoPrepago extends Model
{
    use HasFactory;

    protected $table = 'metodo_prepagos';

    protected $fillable = [
        'descripcion',
        'moneda',
        'sucursal_id'
    ];

    /**
     * Get the sucursal associated with the MetodoPrepago
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sucursal(): HasOne
    {
        return $this->hasOne(Sucursal::class, 'id', 'sucursal_id');
    }
}
