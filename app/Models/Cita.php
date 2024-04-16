<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
    ];

    public function cliente(): BelongsTo
    {
        return $this->BelongsTo(Cliente::class, 'cliente_id', 'id');
    }

    public function get_empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class, 'servicio_id', 'id');
    }
}
