<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'codigo_seguridad',
        'pgc',
        'cliente_id',
        'cliente',
        'monto',
        'fecha_emicion',
        'fecha_vence',
        'metodo_pago',
        'pago_usd',
        'pago_bsd',
        'referencia',
        'responsable',
        'barcode',
        'status',
    ];

    /**
     * Get all of the movimientos for the GiftCard
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoGiftCard::class, 'id', 'gift_card_id');
    }

    /**
     * Get the cliente that owns the GiftCard
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }


}
