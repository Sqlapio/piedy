<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ponderacion extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'ponderacions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'num_estrellas',
        'valor',

    ];
}
