<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntradaInventario extends Model
{
    use HasFactory;

    protected $fillable = [
        'producto_id',
        'almacen_id',
        'cantidad',
        'responsable',
        'tipo_movimiento',
    ];

}