<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaProducto extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'venta_productos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'producto_id',
        'empleado_id',
        'comision_id',
        'fecha_venta',
        'total'
    ];

}
