<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoCajaChica extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'movimiento_caja_chicas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'gasto_id',
        'caja_chica_id',
        'saldo',
        'fecha',
        'responsable',
    ];
}
