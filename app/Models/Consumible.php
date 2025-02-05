<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Consumible extends Model
{
    use HasFactory;

    protected $table = 'consumibles';

    protected $fillable = [
        'producto_id',
        'contenido_neto',
        'unidad',
        'can_srv',
        'uso',
        'tipo_uso',
    ];

    /**
     * Get the user associated with the Consumible
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function producto(): HasOne
    {
        return $this->hasOne(Producto::class, 'id', 'producto_id');
    }
}