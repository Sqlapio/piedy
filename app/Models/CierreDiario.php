<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'total_dolares_efectivo',
        'total_dolares_zelle',
        'total_bolivares',
        'total_gastos',
        'venta_neta_dolares',
        'venta_neta_bolivares',
        'fecha',
        'responsable',
        'observaciones',
    ];
}
