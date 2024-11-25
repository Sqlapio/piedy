<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Almacen extends Model
{
    use HasFactory;

    protected $table = 'almacens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
    ];

    /**
     * Get the inventario that owns the Almacen
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inventario(): HasOne
    {
        return $this->hasOne(Inventario::class, 'id', 'alamcen_id');
    }

    /**
     * Get the entradaInventario that owns the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entradaInventario(): BelongsTo
    {
        return $this->belongsTo(EntradaInventario::class, 'almacen_id', 'id');
    }

    /**
     * Get the entradaInventario that owns the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function salidaInventario(): BelongsTo
    {
        return $this->belongsTo(SalidaInventario::class, 'almacen_id', 'id');
    }
}