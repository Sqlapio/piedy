<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabina extends Model
{
    use HasFactory;

     /**
     * Define table
     */
    protected $table = 'cabinas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cod_cabina',
        'cod_cita',
        'cliente_id',
        'empleado_id',
        'servicio_id',
        'status'
    ];

    public function get_cliente(): BelongsTo
    {
        return $this->BelongsTo(Cliente::class, 'cliente_id');
    }
    
    public function get_empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function get_servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

}
