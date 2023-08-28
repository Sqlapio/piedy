<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductosList extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'cod_producto' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Crema esfoliante',
                'existencia' => '100',
                'creado_por' => 'admin@test.com',
                'precio' => '40.00'
            ],
            [
                'cod_producto' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Crema mentolada',
                'existencia' => '100',
                'creado_por' => 'admin@test.com',
                'precio' => '50.00'
            ],
            [
                'cod_producto' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Hidratante',
                'existencia' => '100',
                'creado_por' => 'admin@test.com',
                'precio' => '20.00'
            ],
            [
                'cod_producto' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Antimicotico',
                'existencia' => '100',
                'creado_por' => 'admin@test.com',
                'precio' => '20.00'
            ],
            [
                'cod_producto' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Cholas de fohami',
                'existencia' => '100',
                'creado_por' => 'admin@test.com',
                'precio' => '600.00'
            ],
            [
                'cod_producto' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Mascarilla esfoliante de pies',
                'existencia' => '100',
                'creado_por' => 'admin@test.com',
                'precio' => '40.00'
            ],
            [
                'cod_producto' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Pintura de uñas permanente',
                'existencia' => '100',
                'creado_por' => 'admin@test.com',
                'precio' => '80.00'
            ],
            [
                'cod_producto' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Pintura de uñas semi-permanente',
                'existencia' => '100',
                'creado_por' => 'admin@test.com',
                'precio' => '40.00'
            ],
            [
                'cod_producto' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Pintura de uñas en gel',
                'existencia' => '100',
                'creado_por' => 'admin@test.com',
                'precio' => '10.00'
            ]

        ];

        Producto::insert($data);
    }
}
