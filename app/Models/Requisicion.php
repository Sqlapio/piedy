<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Requisicion extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'sucursal_id',
        'fecha',
        'status',
        'user_id'
    ];

    /**
     * Get the user associated with the Requisicion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Get the user associated with the Requisicion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sucursal(): HasOne
    {
        return $this->hasOne(Sucursal::class, 'id', 'sucursal_id');
    }

    /**
     * Get the user associated with the Requisicion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function producto(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'producto_id');
    }

    /**
     * Get all of the comments for the Requisicion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detalleRequisicion(): HasMany
    {
        return $this->hasMany(DetalleRequisicion::class, 'requisicion_id', 'id');
    }

}