<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Comision extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'comisions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cod_comision',
        'aplicacion',
        'porcentaje',
        'status',
    ];

    public function producto():HasOne
    {
        return $this->hasOne(Producto::class, 'comision_id', 'id');
    }

    public function servicio():HasOne
    {
        return $this->hasOne(Servicio::class, 'comision_id', 'id');
    }

}
 