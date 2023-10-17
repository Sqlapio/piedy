<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Rango extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'rangos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rango',
        'comision_id',
    ];

    public function comision(): HasOne
    {
        return $this->hasOne(Comision::class);
    }
    
}
