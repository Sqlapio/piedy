<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ManejoEfectivo extends Model
{
    use HasFactory;

    protected $table = 'manejo_efectivos';

    protected $fillable = [
        'moto',
        'sucursal_id',
        'responsable'
    ];

    /**
     * Get all of the comments for the ManejoEfectivo
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ManejoEfectivoDetalles(): HasMany
    {
        return $this->hasMany(ManejoEfectivoDetalle::class, 'manejo_efectivo_id', 'id');
    }
}