<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Gasto extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'gastos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'descripcion',
        'monto_usd',
        'monto_bsd',
        'forma_pago',
        'fecha_factura',
        'responsable',
        'numero_factura',
        'numero_factura_gasto',
        'sucursal_id'
    ];

    /**
     * Get the compra that owns the Proveedor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sucursal(): HasOne
    {
        return $this->hasOne(Sucursal::class, 'id', 'sucursal_id');
    }
}