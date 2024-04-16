<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CosumoMensual extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'cosumo_mensuals';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'empleado',
        'producto',
        'cantidad_total',
        'unidad',
        'total_servicios',
    ];
}
