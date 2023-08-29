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
                'comision_id' => 1,
                'precio' => '40.00'
            ],
            [
                'cod_producto' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Crema mentolada',
                'existencia' => '100',
                'comision_id' => 3,
                'precio' => '50.00'
            ],
            [
                'cod_producto' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Hidratante',
                'existencia' => '100',
                'comision_id' => 2,
                'precio' => '20.00'
            ],
            [
                'cod_producto' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Antimicotico',
                'existencia' => '100',
                'comision_id' => 2,
                'precio' => '20.00'
            ],
            [
                'cod_producto' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Cholas de fohami',
                'existencia' => '100',
                'comision_id' => 1,
                'precio' => '600.00'
            ],
            [
                'cod_producto' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Mascarilla esfoliante de pies',
                'existencia' => '100',
                'comision_id' => 3,
                'precio' => '40.00'
            ],
            [
                'cod_producto' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Pintura de uñas permanente',
                'existencia' => '100',
                'comision_id' => 1,
                'precio' => '80.00'
            ],
            [
                'cod_producto' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Pintura de uñas semi-permanente',
                'existencia' => '100',
                'comision_id' => 1,
                'precio' => '40.00'
            ],
            [
                'cod_producto' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Pintura de uñas en gel',
                'existencia' => '100',
                'comision_id' => 1,
                'precio' => '10.00'
            ]

        ];

        Producto::insert($data);
    }
}
