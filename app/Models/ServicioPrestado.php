<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServicioPrestado extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'servicio_prestados';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'servicio_id',
        'empleado_id',
        'fecha',
    ];

    public function servicio():BelongsTo
    {
        return $this->belongsTo(Servicio::class, 'servicio_id', 'id');
    }

    public function empleado():BelongsTo
    {
        return $this->belongsTo(Servicio::class, 'empleado_id', 'id');
    }
}
