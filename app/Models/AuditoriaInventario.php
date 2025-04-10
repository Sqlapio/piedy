<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditoriaInventario extends Model
{
    use HasFactory;

    protected $table = 'auditoria_inventarios';

    protected $fillable = [
        'codigo_auditoria',
        'requisiciones',
        'desde',
        'hasta',
        'responsable',
    ];

    protected $casts = [
        'requisiciones' => 'json',
    ];

    /**
     * Get all of the comments for the AuditoriaInventario
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detalleServicios(): HasMany
    {
        return $this->hasMany(DetalleAuditoriaServicio::class, 'auditoria_inventario_id', 'id');
    }

    /**
     * Get all of the comments for the AuditoriaInventario
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detalleProductos(): HasMany
    {
        return $this->hasMany(DetalleAuditoriaProducto::class, 'auditoria_inventario_id', 'id');
    }

    /**
     * Get all of the comments for the AuditoriaInventario
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detalleMovimientosInventarioGeneral(): HasMany
    {
        return $this->hasMany(DetalleMovimientoInventarioGeneral::class, 'auditoria_inventario_id', 'id');
    }

    /**
     * Get all of the comments for the AuditoriaInventario
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detalleMovimientosInventarioSucursal(): HasMany
    {
        return $this->hasMany(DetalleMovimientoInventarioSucursal::class, 'auditoria_inventario_id', 'id');
    }

    

    
}