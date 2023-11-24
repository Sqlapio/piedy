<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoServicio extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'tipo_servicios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'descripcion'
    ];

    /**
     * Get all of the servicios for the TipoServicio
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function servicios(): HasMany
    {
        return $this->hasMany(Servicio::class, 'tipo_servicio_id', 'id');
    }
}
