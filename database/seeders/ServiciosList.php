<?php

namespace Database\Seeders;

use App\Models\Servicio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiciosList extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'cod_servicio' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Quiropedia basica',
                'creado_por' => 'admin@test.com',
                'costo' => '20.00',
                'duracion_max' => '60',
            ],
            [
                'cod_servicio' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Quiropedia de uñas encarnadas',
                'creado_por' => 'admin@test.com',
                'costo' => '20.00',
                'duracion_max' => '60',
            ],
            [
                'cod_servicio' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Masaje, Hidratacion y Esfoliacion de pies',
                'creado_por' => 'admin@test.com',
                'costo' => '30.00',
                'duracion_max' => '30',
            ],
            [
                'cod_servicio' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Terapia laser de hongos en uñas',
                'creado_por' => 'admin@test.com',
                'costo' => '40.00',
                'duracion_max' => '60',
            ],
            [
                'cod_servicio' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Manicure',
                'creado_por' => 'admin@test.com',
                'costo' => '20.00',
                'duracion_max' => '45',
            ],
            [
                'cod_servicio' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Pedicure',
                'creado_por' => 'admin@test.com',
                'costo' => '10.00',
                'duracion_max' => '120',
            ]

        ];

        Servicio::insert($data);
    }
}
