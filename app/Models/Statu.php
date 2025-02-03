<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Statu extends Model
{
    use HasFactory;

    protected $table = 'status';

    protected $fillable = [
        'descripcion',
    ];

    /**
     * Get the user that owns the Statu
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function detalleRequisicion(): BelongsTo
    {
        return $this->belongsTo(DetalleRequisicion::class, 'status_id', 'id');
    }

    /**
     * Get the user that owns the Statu
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function requisicion(): BelongsTo
    {
        return $this->belongsTo(DetalleRequisicion::class, 'status_id', 'id');
    }
}