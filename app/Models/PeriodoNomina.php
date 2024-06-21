<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodoNomina extends Model
{
    use HasFactory;
    /**
     * Define table
     */
    protected $table = 'periodo_nominas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fecha_ini',
        'fecha_fin',
        'cod_quincena',
        'status',
        'tasa_bcv',
        'total_dolares',
        'total_bolivares',
        'total_general',
    ];
}
