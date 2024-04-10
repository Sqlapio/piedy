<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CierreGeneral extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'cierre_generals';

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
        'fecha',
        'responsable',
    ];
}
