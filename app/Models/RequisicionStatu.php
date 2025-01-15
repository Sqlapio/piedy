<?php

namespace App\Models;

use App\Models\Requisicion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequisicionStatu extends Model
{
    use HasFactory;

    protected $fillable = [
        'descripcion',
    ];
}
