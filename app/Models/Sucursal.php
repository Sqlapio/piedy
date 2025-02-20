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
     * Get all of the movimientos_inventarios for the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recepcionInventarios(): HasMany
    {
        return $this->hasMany(RecepcionInventario::class, 'id', 'sucursal_id');
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

    /**
     * Get the Servicio that owns the Sucursal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cierreDiario(): BelongsTo
    {
        return $this->belongsTo(cierreDiario::class, 'id', 'sucursal_id');
    }

    /**
     * Get the Servicio that owns the Sucursal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function compra(): BelongsTo
    {
        return $this->belongsTo(Compra::class, 'id', 'sucursal_id');
    }

    /**
     * Get the Servicio that owns the Sucursal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gasto(): BelongsTo
    {
        return $this->belongsTo(Gasto::class, 'id', 'sucursal_id');
    }

    /**
     * Get the entradaInventario that owns the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function salidaInventario(): BelongsTo
    {
        return $this->belongsTo(SalidaInventario::class, 'sucursal_id', 'id');
    }

    /**
     * Get the entradaInventario that owns the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resumenContable(): BelongsTo
    {
        return $this->belongsTo(ResumenContable::class, 'id', 'sucursal_id');
    }

    /**
     * Get the entradaInventario that owns the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class, 'sucursal_id', 'id');
    }

    /**
     * Get the user that owns the Sucursal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ventaProducto(): BelongsTo
    {
        return $this->belongsTo(VentaProducto::class, 'sucursal_id', 'id');
    }

    /**
     * Get the user that owns the Sucursal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ventaServicio(): BelongsTo
    {
        return $this->belongsTo(VentaServicio::class, 'sucursal_id', 'id');
    }

    /**
     * Get the preNomina that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function preNomina(): BelongsTo
    {
        return $this->belongsTo(PreNomina::class, 'sucursal_id', 'id');
    }

    /**
     * Get the preNomina that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reporte(): BelongsTo
    {
        return $this->belongsTo(Reporte::class, 'sucursal_id', 'id');
    }

    // //RELACION UNO A UNO CON LA TABLA DE AUDITORIAS
    // public function auditoria(): BelongsTo
    // {
    //     return $this->belongsTo(Auditoria::class, 'sucursal_id', 'id');
    // }

}