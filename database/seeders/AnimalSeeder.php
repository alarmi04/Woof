<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AnimalSeeder extends Seeder
{
    public function run()
    {
        // Tamano, Nivel_actividad (1=bajo,2=medio,3=alto), Vivienda, Apto_ninos, Experiencia_requerida, Tiempo_requerido
        $razas = [
            'Labrador' => ['grande', 3, 'casa_con_jardin', 1, 0, 4],
            'Beagle' => ['mediano', 2, 'cualquiera', 1, 0, 2],
            'Pastor Alemán' => ['grande', 3, 'casa_con_jardin', 0, 1, 5],
            'Chihuahua' => ['pequeno', 1, 'piso', 1, 0, 1],
            'Golden Retriever' => ['grande', 2, 'casa_sin_jardin', 1, 0, 3],
            'Poodle' => ['pequeno', 1, 'piso', 1, 0, 2],
            'Bulldog Francés' => ['pequeno', 1, 'piso', 1, 0, 1],
            'Rottweiler' => ['grande', 2, 'casa_con_jardin', 0, 1, 4],
            'Husky Siberiano' => ['grande', 3, 'casa_con_jardin', 0, 1, 5],
            'Dálmata' => ['grande', 3, 'casa_con_jardin', 1, 0, 4],
        ];
        $nombres = [
            'Rocky',
            'Luna',
            'Max',
            'Nala',
            'Toby',
            'Coco',
            'Bella',
            'Thor',
            'Lola',
            'Bruno',
            'Daisy',
            'Zeus',
            'Mia',
            'Rex',
            'Kira',
            'Simba',
            'Nina',
            'Buddy',
            'Laika',
            'Duke',
            'Sasha',
            'Goku',
            'Vera',
            'Axel',
            'Leia',
        ];

        $descripciones = [
            'Muy juguetón y cariñoso, le encanta el agua.',
            'Tranquilo y obediente, ideal para familias.',
            'Inteligente y protector, necesita espacio.',
            'Pequeño y valiente, perfecto para piso.',
            'Sociable y muy activo, le encantan los niños.',
            'Muy inteligente y fácil de entrenar.',
            'Tranquilo y muy cariñoso, perfecto para piso.',
            'Leal y protector, necesita dueño experimentado.',
            'Enérgico y aventurero, necesita mucho ejercicio.',
            'Alegre y curioso, se lleva bien con todos.',
        ];

        $etapas = ['joven', 'adulto', 'senior'];

        $razasApi = [
            'labrador',
            'beagle',
            'germanshepherd',
            'chihuahua',
            'golden',
            'poodle',
            'bulldog',
            'rottweiler',
            'husky',
            'dalmatian'
        ];

        // Obtener imágenes reales de Dog CEO API
        $imagenesUrl = [];
        foreach ($razasApi as $raza) {
            $response = Http::get("https://dog.ceo/api/breed/{$raza}/images/random/3");
            if ($response->successful()) {
                foreach ($response->json()['message'] as $url) {
                    $imagenesUrl[] = $url;
                }
            }
        }

        while (count($imagenesUrl) < 25) {
            $imagenesUrl[] = 'https://placedog.net/400/300?id=' . count($imagenesUrl);
        }

        $animales = [];
        $razasLista = array_keys($razas);

        for ($i = 0; $i < 25; $i++) {
            $raza = $razasLista[$i % count($razasLista)];
            [$tamano, $nivel_actividad, $vivienda, $apto_ninos, $experiencia, $tiempo] = $razas[$raza];

            $etapa = $etapas[$i % count($etapas)];

            $animales[] = [
                'Nombre' => $nombres[$i],
                'Raza' => $raza,
                'Genero' => $i % 2 === 0 ? 'Macho' : 'Hembra',
                'Edad' => $etapas[$i % count($etapas)],
                'Tamano' => $tamano,
                'Estado' => 'disponible',
                'Descripcion' => $descripciones[$i % count($descripciones)],
                'Nivel_actividad' => $nivel_actividad,
                'Vivienda_recomendada' => $vivienda,
                'Apto_ninos' => $apto_ninos,
                'Experiencia_requerida' => $experiencia,
                'Tiempo_requerido' => $tiempo,
                'Imagen' => $imagenesUrl[$i] ?? 'https://placedog.net/400/300?id=' . $i,
            ];
        }

        DB::table('Animal')->insert($animales);
    }
}