<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TasaBcv extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'tasa_bcvs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tasa',
        'fecha'
    ];
}
