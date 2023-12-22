<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GastoOnline extends Model
{
    use HasFactory;

    protected $connection = 'mysql_online';

    /**
     * Define table
     */
    protected $table = 'gastos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'descripcion',
        'monto',
        'forma_pago',
        'referencia',
        'fecha',
        'responsable',
    ];
}
