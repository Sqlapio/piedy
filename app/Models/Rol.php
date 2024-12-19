<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Rol extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'descripcion',
    ];


    /**
     * Get the user that owns the Rol
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rol_id', 'id');
    }

    /**
     * Get all of the servicios for the TipoServicio
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function servicios(): HasMany
    {
        return $this->hasMany(Servicio::class);
    }

    /**
     * Get the preNomina that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function preNomina(): BelongsTo
    {
        return $this->belongsTo(PreNomina::class, 'rol_id', 'id');
    }
}