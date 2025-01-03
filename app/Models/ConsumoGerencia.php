<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConsumoGerencia extends Model
{
    use HasFactory;

    protected $table = 'consumo_gerencias';

    protected $fillable = [
        'producto_id',
        'contenido',
        'unidad',
        'cant_servicio',
        'total_uso',
    ];

    //HasOne producto
    public function producto(): HasOne
    {
        return $this->hasOne(Producto::class, 'id', 'producto_id');
    }
}