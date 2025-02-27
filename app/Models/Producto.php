<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Producto extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'productos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cod_producto',
        'categoria_id',
        'descripcion',
        'precio_venta',
        'unidad',
        'contenido_neto',
        'image',
        'status',
        'responsable',
        'uso',
        'costo',
        'tipo_empaquetado',
        'total_unidades',
        'marca',
        'min',
        'max',
        'existencia_min_sucursal',
        'cant_consu_serv',
        'consumido_por',
        'uso_promedio_serv',
        'cant_desc_auto'
        
        
        
    ];

    public function comision():BelongsTo
    {
        return $this->belongsTo(Comision::class, 'comision_id', 'id');
    }

    public function categoria():BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id', 'id');
    }

    /**
     * Get all of the ventas for the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ventas(): HasMany
    {
        return $this->hasMany(VentaProducto::class, 'id', 'producto_id');
    }

    /**
     * Get the user that owns the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id', 'id');
    }

    /**
     * Get the inventario that owns the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inventario(): BelongsTo
    {
        return $this->belongsTo(Inventario::class, 'id', 'producto_id');
    }

    /**
     * Get all of the movimientos_inventarios for the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movimientoInventarios(): HasMany
    {
        return $this->hasMany(MovimientoInventario::class, 'id', 'producto_id');
    }

    /**
     * Get all of the movimientos_inventarios for the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventarioSucursales(): HasMany
    {
        return $this->hasMany(InventarioSucursal::class, 'id', 'producto_id');
    }

    /**
     * Get all of the movimientos_inventarios for the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recepcionInventarios(): HasMany
    {
        return $this->hasMany(RecepcionInventario::class, 'id', 'producto_id');
    }

    /**
     * Get the detalleAsignacion associated with the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function detalleAsignacion(): HasOne
    {
        return $this->hasOne(DetalleAsignacion::class, 'id', 'producto_id');
    }

    /**
     * Get the entradaInventario that owns the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entradaInventario(): BelongsTo
    {
        return $this->belongsTo(EntradaInventario::class, 'producto_id', 'id');
    }

    /**
     * Get the entradaInventario that owns the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function salidaInventario(): BelongsTo
    {
        return $this->belongsTo(SalidaInventario::class, 'producto_id', 'id');
    }

    /**
     * Get all of the almacenes for the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function almacenes(): HasMany
    {
        return $this->hasMany(Almacen::class);
    }

    public function asignarProducto():BelongsTo
    {
        return $this->belongsTo(AsignarProducto::class, 'producto_id', 'id');
    }

    /**
     * Get the inventario that owns the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function requisicion(): BelongsTo
    {
        return $this->belongsTo(Requisicion::class, 'requisicion_id', 'id');
    }

    /**
     * Get all of the comments for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function asignaciones(): HasMany
    {
        return $this->hasMany(AsignarProducto::class, 'user_id', 'id');
    }

    /**
     * Get the user associated with the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function auditoria(): HasOne
    {
        return $this->hasOne(Auditoria::class, 'id', 'producto_id');
    }

    /**
     * The servicios that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function servicios(): BelongsToMany
    {
        return $this->belongsToMany(Servicio::class, 'producto_servicios')
        ->using(ProductoServicio::class)
            ->withPivot(['descripcion']);
    }

    //Relacion de UNO a MUCHOS con la tabla de movimiento_inventario_sucrsals
    public function movimientoInventarioSucursales(): HasMany
    {
        return $this->hasMany(MovimientoInventarioSucursal::class, 'producto_id', 'id');
    }



}