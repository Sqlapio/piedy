<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Disponible extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'disponibles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cod_asignacion',
        'cliente_id',
        'empleado_id',
        'cod_prod_serv',
        'servicio_id',
        'costo',
        'status',
    ];



    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'empleado_id', 'id');
    }

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class, 'servicio_id', 'id');
    }

    /**
     * Get the primeraAsignacion associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function primeraAsignacion(): BelongsTo
    {
        return $this->belongsTo(User::class, 'empleado_id', 'id');
    }
}
