<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovGiftCard extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'mov_gift_cards';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cod_gift_card',
        'cliente_id',
        'monto',
        'fecha',
        'metodo_pago',
        'referencia',
        'responsable'

    ];
}
