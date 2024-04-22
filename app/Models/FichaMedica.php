<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FichaMedica extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'ficha_medicas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cliente_id',
        /** Antecedentes Medicos */
        'am_p1',
        'am_p2',
        'am_p3',
        'am_p4',
        /** Historial Podologico */
        'hp_p1',
        'hp_p2',
        /** Estilo de Vida */
        'ev_p1',
        'ev_p2',
        'ev_p3',
        'comentario_adicional',
    ];

    /**
     * Get the cliente associated with the FichaMedica
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class, 'cliente_id', 'id');
    }

}
