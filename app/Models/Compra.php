<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Compra extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cod_compra',
        'proveedor_id',
        'descripcion',
        'monto_usd',
        'monto_bsd',
        'forma_pago',
        'fecha_compra',
        'responsable',
        'numero_factura_compra',
        'observacion',
        'metodo_pago'
    ];

    /**
     * Get all of the proveedores for the Compra
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function proveedores(): HasMany
    {
        return $this->hasMany(Proveedor::class);
    }

    /**
     * Get all of the proveedores for the Compra
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function iva(): HasOne
    {
        return $this->hasOne(Iva::class, 'id', 'iva_id');
    }
}