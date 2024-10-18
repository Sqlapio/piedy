<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarProducto extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'car_productos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cod_producto',
        'cod_pre_seleccion',
        'precio_venta',
        'cantidad',
        'total_compra_usd',
        'total_compra_bsd',

    ];
}
