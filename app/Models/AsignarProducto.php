<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AsignarProducto extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'asignar_productos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'codigo_prod',
        'producto_id',
        'producto',
        'cantidad',
        'contenido_neto',
        'fecha_entrega',
        'empleado_id',
        'empleado',
        'responsable'
    ];

    public function get_producto():HasOne
    {
        return $this->hasOne(Producto::class, 'producto_id', 'id');
    }
}
