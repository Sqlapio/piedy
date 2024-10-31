<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        return $this->belongsTo(User::class, 'id', 'rol_id');
    }

    /**
     * Get the user that owns the Rol
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class, 'id', 'rol_id');
    }
}
