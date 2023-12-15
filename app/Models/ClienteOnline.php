<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteOnline extends Model
{
    use HasFactory;


    protected $connection = 'mysql_online';

    /**
     * Define table
     */
    protected $table = 'clientes';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nombre',
        'apellido',
        'cedula',
        'email',
        'telefono',
        'direccion_corta',
        'visitas'

    ];
}
