<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientesList extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nombre' => 'Gustavo',
                'apellido' => 'Camacho',
                'cedula' => '16007868',
                'email' => 'gustavo@test.com',
                'telefono' => '04127018390',
                'direccion_corta' => 'Chacao, Altamira',
                
            ],
            [
                'nombre' => 'Jonny',
                'apellido' => 'Martinez',
                'cedula' => '15540759',
                'email' => 'jonny@test.com',
                'telefono' => '04125879632',
                'direccion_corta' => 'El paraiso',
            ],
            [
                'nombre' => 'Carlos',
                'apellido' => 'Cala',
                'cedula' => '15478963',
                'email' => 'carlos@test.com',
                'telefono' => '04245896328',
                'direccion_corta' => 'El valle',
            ],
            [
                'nombre' => 'Alberto',
                'apellido' => 'Perez',
                'cedula' => '10236589',
                'email' => 'alberto@test.com',
                'telefono' => '04168745236',
                'direccion_corta' => 'Los cortijos',
            ],

        ];

        Cliente::insert($data);
    }
}
