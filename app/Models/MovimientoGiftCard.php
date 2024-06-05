<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoGiftCard extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'movimiento_gift_cards';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'gift_card_id',
        'codigo_seguridad',
        'cliente_id',
        'monto_pagado',
        'fecha_debito',
        'responsable',
    ];
}
