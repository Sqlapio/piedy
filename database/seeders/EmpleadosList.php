<?php

namespace Database\Seeders;

use App\Models\Empleado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmpleadosList extends Seeder
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
                'email' => 'gus@test.com',
                'telefono' => '04128218390',
                'direccion_corta' => 'Chacao, Altamira',
                'tipo_empleado' => 'regular',
                'Fecha_Contratación' => '10-10-2022',
                
            ],
            [
                'nombre' => 'Carlos',
                'apellido' => 'Hidalgo',
                'cedula' => '16777868',
                'email' => 'car@test.com',
                'telefono' => '04127018390',
                'direccion_corta' => 'Chacao, Altamira',
                'tipo_empleado' => 'regular',
                'Fecha_Contratación' => '10-08-2022',
            ],
            [
                'nombre' => 'Maria',
                'apellido' => 'Garcia',
                'cedula' => '15247868',
                'email' => 'mary@test.com',
                'telefono' => '04127047390',
                'direccion_corta' => 'Chacao, Altamira',
                'tipo_empleado' => 'regular',
                'Fecha_Contratación' => '10-12-2022',
            ],

        ];

        Empleado::insert($data);
    }
}
