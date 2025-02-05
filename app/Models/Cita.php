<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cita extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'citas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cod_cita',
        'cliente_id',
        'cliente',
        'correo',
        'fecha',
        'hora',
        'responsable',
        'status',
        'empleado_id',
        'sucursal_id',
        'servicio_id'
    ];

    /**
     * Get the user that owns the Cita
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'cliente_id');
    }

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'empleado_id', 'id');
    }

    /**
     * Get all of the servicios for the Cita
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class, 'servicio_id', 'id');
    }

    // /**
    //  * Get the user that owns the Cita
    //  *
    //  * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    //  */
    // public function user(): BelongsTo
    // {
    //     return $this->belongsTo(User::class, 'foreign_key', 'other_key');
    // }

}