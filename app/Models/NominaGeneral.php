<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'cod_quincena',
        'total_bolivares',
        'total_dolares',
        'total_general',
    ];
}
