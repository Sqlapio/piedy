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
                'comision_id' => 1,
                'costo' => '20.00',
                'duracion_max' => '60',
            ],
            [
                'cod_servicio' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Quiropedia de uñas encarnadas',
                'comision_id' => 2,
                'costo' => '20.00',
                'duracion_max' => '60',
            ],
            [
                'cod_servicio' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Masaje, Hidratacion y Esfoliacion de pies',
                'comision_id' => 1,
                'costo' => '30.00',
                'duracion_max' => '30',
            ],
            [
                'cod_servicio' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Terapia laser de hongos en uñas',
                'comision_id' => 3,
                'costo' => '40.00',
                'duracion_max' => '60',
            ],
            [
                'cod_servicio' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Manicure',
                'comision_id' => 3,
                'costo' => '20.00',
                'duracion_max' => '45',
            ],
            [
                'cod_servicio' => 'Ps-'.random_int(11111, 99999),
                'descripcion' => 'Pedicure',
                'comision_id' => 1,
                'costo' => '10.00',
                'duracion_max' => '120',
            ]

        ];

        Servicio::insert($data);
    }
}
