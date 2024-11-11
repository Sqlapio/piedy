<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sucursal extends Model
{
    use HasFactory;

    protected $table = 'sucursales';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'email',
        'ciudad',
        'estado',
        'pais',
    ];

    /**
     * Get the user that owns the Sucursal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'sucursal_id');
    }

    /**
     * Get all of the productos for the Sucursal
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'id', 'sucursal_id');
    }

    /**
     * Get all of the movimientos_inventarios for the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventarioSucursales(): HasMany
    {
        return $this->hasMany(InventarioSucursal::class, 'id', 'sucursal_id');
    }

    /**
     * Get the asignacionProducto that owns the Sucursal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function asignacionProducto(): BelongsTo
    {
        return $this->belongsTo(AsignarProducto::class, 'id', 'sucursal_id');
    }

    /**
     * Get all of the movimientos_inventarios for the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movimientoInventarios(): HasMany
    {
        return $this->hasMany(MovimientoInventario::class, 'id', 'sucursal_id');
    }

    /**
     * Get the metodoPrepago that owns the Sucursal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function metodoPrepago(): BelongsTo
    {
        return $this->belongsTo(MetodoPrepago::class);
    }

    /**
     * Get the Servicio that owns the Sucursal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class);
    }

}
