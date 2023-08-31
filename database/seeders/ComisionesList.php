<?php

namespace Database\Seeders;

use App\Models\Comision;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComisionesList extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'cod_comision' => 'Pco-'.random_int(11111, 99999),
                'porcentaje' => '5.00',
            ],
            [
                'cod_comision' => 'Pco-'.random_int(11111, 99999),
                'porcentaje' => '10.00',
            ],
            [
                'cod_comision' => 'Pco-'.random_int(11111, 99999),
                'porcentaje' => '15.00',
            ],
            [
                'cod_comision' => 'Pco-'.random_int(11111, 99999),
                'porcentaje' => '20.00',
            ],

        ];

        Comision::insert($data);
    }
}
