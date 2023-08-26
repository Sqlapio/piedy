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
                'existencia' => '15',
                'creado_por' => 'administrador'
            ],
            [
                'cod_producto' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Crema mentolada',
                'existencia' => '15',
                'creado_por' => 'administrador'
            ],
            [
                'cod_producto' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Hidratante',
                'existencia' => '15',
                'creado_por' => 'administrador'
            ],
            [
                'cod_producto' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Antimicotico',
                'existencia' => '15',
                'creado_por' => 'administrador'
            ],
            [
                'cod_producto' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Cholas de fohami',
                'existencia' => '15',
                'creado_por' => 'administrador'
            ],
            [
                'cod_producto' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Mascarilla esfoliante de pies',
                'existencia' => '15',
                'creado_por' => 'administrador'
            ],
            [
                'cod_producto' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Pintura de uñas permanente',
                'existencia' => '15',
                'creado_por' => 'administrador'
            ],
            [
                'cod_producto' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Pintura de uñas semi-permanente',
                'existencia' => '15',
                'creado_por' => 'administrador'
            ],
            [
                'cod_producto' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Pintura de uñas en gel',
                'existencia' => '15',
                'creado_por' => 'administrador'
            ]

        ];

        Producto::insert($data);
    }
}
