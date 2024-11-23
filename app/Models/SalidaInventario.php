<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalidaInventario extends Model
{
    use HasFactory;
    protected $fillable = [
        'producto_id',
        'almacen_id',
        'sucursal_id',
        'cantidad',
        'responsable',
        'tipo_movimiento',
    ];
}