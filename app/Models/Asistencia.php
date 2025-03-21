<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = "asistencias";

    protected $fillable = [
        'empleado_id',
        'entrada',
        'salida'
    ];

    public function empleado():BelongsTo
    {
        return $this->belongsTo(User::class, 'empleado_id', 'id');
    }  
}