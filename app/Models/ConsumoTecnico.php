<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ConsumoTecnico extends Model
{
    use HasFactory;
    
    protected $table = 'consumo_tecnicos';
    
    protected $fillable = [
        'producto_id',
        'contenido',
        'unidad',
        'cant_servicio',
        'total_uso',
    ];

    /**
     * Get the user associated with the ConsumoTecnico
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function producto(): HasOne
    {
        return $this->hasOne(Producto::class, 'id', 'producto_id');
    }


}