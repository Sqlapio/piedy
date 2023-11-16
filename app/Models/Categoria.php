<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Categoria extends Model
{
    use HasFactory;

    /**
     * Define table
     */
    protected $table = 'categorias';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'descripcion',
    ];

    public function producto():HasOne
    {
        return $this->hasOne(Producto::class, 'categoria_id', 'id');
    }
}
