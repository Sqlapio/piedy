<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Iva extends Model
{
    use HasFactory;

    protected $fillable = [
        'iva',
    ];

    /**
     * Get the compra that owns the Iva
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function compra(): BelongsTo
    {
        return $this->belongsTo(Compra::class);
    }
}
