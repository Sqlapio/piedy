<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'promocions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cod_promocion',
        'descripcion',
        'image',
        'costo',
        'tipo',
        'status',
    ];
}
