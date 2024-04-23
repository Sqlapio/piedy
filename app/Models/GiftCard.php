<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCard extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'gift_cards';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cod_gift_card',
        'cliente_id',
        'monto',
        'fecha_emicion',
        'fecha_vence',
        'responsable',
        'status',
    ];
}
