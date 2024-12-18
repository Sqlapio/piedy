<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CierreDiario extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'cierre_diarios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [

        'total_ventas',
        'total_dolares_efectivo',
        'total_dolares_zelle',
        'total_bolivares',
        'saldo_caja_chica',
        'fecha',
        'responsable',
        'observaciones',
        'sucursal_id',
        'efectivo_usd_real',
        'recibido_por',
        'received_at',
        'observ_recepcion'
        
    ];

    /**
     * Get the Servicio that owns the Sucursal
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sucursal(): HasOne
    {
        return $this->hasOne(Sucursal::class, 'id', 'sucursal_id');
    }
}