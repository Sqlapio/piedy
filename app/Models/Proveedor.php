<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proveedor extends Model
{
    use HasFactory;

    protected $fillable = [
        'rif',
        'nombre',
    ];

    /**
     * Get the compra that owns the Proveedor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function compras(): HasMany
    {
        return $this->hasMany(Compra::class);
    }

    /**
     * Get the gastos that owns the Proveedor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gastos(): HasMany
    {
        return $this->hasMany(Gasto::class);
    }
}