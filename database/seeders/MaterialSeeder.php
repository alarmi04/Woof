<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materiales = [
            ['Nombre' => 'Dinero', 'Descripcion' => 'Aporte monetario para el albergue.'],
            ['Nombre' => 'Juguetes', 'Descripcion' => 'Juguetes variados para perros. (Csantidad en unidades)'],
            ['Nombre' => 'Mantas', 'Descripcion' => 'Mantas para arropar a los perros. (Cantidad en unidades)'],
            ['Nombre' => 'Comida', 'Descripcion' => 'Comida para alimentar a los perros. (Cantidad en Kg.)'],
            ['Nombre' => 'Bebedero', 'Descripcion' => 'Bebederos para perros. (Cantidad en unidades)'],
        ];

        DB::table('Material')->insert($materiales);
    }


}
