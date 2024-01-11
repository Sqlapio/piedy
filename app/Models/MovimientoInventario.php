<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoInventario extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'movimiento_inventarios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'accion',
        'producto_id',
        'user_id',
        'entradas',
        'salidas',
        'responsable'
    ];
}
