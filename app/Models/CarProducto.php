<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'cod_prod_serv',
        'cod_prod',
        'cod_pre_seleccion',
        'precio_venta',
        'cantidad',
        'total_compra_usd',
        'total_compra_bsd',
        'cod_asignacion',
        'tipo'

    ];

    /**
     * Get the user associated with the CarProducto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function producto(): HasOne
    {
        return $this->hasOne(Producto::class, 'cod_producto', 'cod_prod');
    }
}
