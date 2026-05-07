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
            'Labrador' => ['grande', 'labrador', 3, 'casa_con_jardin', 1, 0, 4],
            'Beagle' => ['mediano', 'beagle', 2, 'cualquiera', 1, 0, 2],
            'Pastor Alemán' => ['grande', 'germanshepherd', 3, 'casa_con_jardin', 0, 1, 5],
            'Chihuahua' => ['pequeño', 'chihuahua', 1, 'piso', 1, 0, 1],
            'Golden Retriever' => ['grande', 'golden', 2, 'casa_sin_jardin', 1, 0, 3],
            'Poodle' => ['pequeño', 'poodle', 1, 'piso', 1, 0, 2],
            'Bulldog Francés' => ['pequeño', 'bulldog/french', 1, 'piso', 1, 0, 1],
            'Rottweiler' => ['grande', 'rottweiler', 2, 'casa_con_jardin', 0, 1, 4],
            'Husky Siberiano' => ['grande', 'husky', 3, 'casa_con_jardin', 0, 1, 5],
            'Dálmata' => ['grande', 'dalmatian', 3, 'casa_con_jardin', 1, 0, 4],
        ];

        $nombresMasc = [
            'Rocky', 'Max', 'Toby', 'Thor', 'Bruno', 'Zeus', 'Rex', 'Simba', 'Buddy', 'Duke', 'Goku', 'Axel', 'Milo', 'Leo', 'Oscar', 'Charlie', 'Kaiser', 'Shadow', 'Bobby', 'Rocco', 'Apollo', 'Loki', 'Nico', 'Ranger', 'Coco'
        ];

        $nombresHembra = [
            'Luna', 'Nala', 'Coco', 'Bella', 'Lola', 'Daisy', 'Mia', 'Kira', 'Nina', 'Laika', 'Sasha', 'Vera', 'Leia', 'Maya', 'Luna', 'Ruby', 'Molly', 'Lola', 'Zoe', 'Lila', 'Mila', 'Sasha', 'Penny', 'Daisy', 'Ella'
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

        $imagenesUrl = [];
        foreach ($razas as $raza => $info) {
            $breedSlug = $info[1];
            $response = Http::get("https://dog.ceo/api/breed/{$breedSlug}/images/random/3");
            if ($response->successful()) {
                foreach ($response->json()['message'] as $url) {
                    $imagenesUrl[$raza][] = $url;
                }
            }
        }

        $fallback = [];
        while (count($fallback) < 25) {
            $fallback[] = 'https://placedog.net/400/300?id=' . count($fallback);
        }

        $animales = [];
        $razasLista = array_keys($razas);

        for ($i = 0; $i < 25; $i++) {
            $raza = $razasLista[$i % count($razasLista)];
            [$tamano, $breedSlug, $nivel_actividad, $vivienda, $apto_ninos, $experiencia, $tiempo] = $razas[$raza];

            $genero = $i % 2 === 0 ? 'Macho' : 'Hembra';
            $nombre = $genero === 'Macho'
                ? $nombresMasc[$i % count($nombresMasc)]
                : $nombresHembra[$i % count($nombresHembra)];

            $imagen = null;
            if (!empty($imagenesUrl[$raza])) {
                $imagen = $imagenesUrl[$raza][$i % count($imagenesUrl[$raza])];
            }
            if (! $imagen) {
                $imagen = $fallback[$i % count($fallback)];
            }

            $animales[] = [
                'Nombre' => $nombre,
                'Raza' => $raza,
                'Genero' => $genero,
                'Edad' => $etapas[$i % count($etapas)],
                'Tamano' => $tamano,
                'Estado' => 'Disponible',
                'Descripcion' => $descripciones[$i % count($descripciones)],
                'Nivel_actividad' => $nivel_actividad,
                'Vivienda_recomendada' => $vivienda,
                'Apto_ninos' => $apto_ninos,
                'Experiencia_requerida' => $experiencia,
                'Tiempo_requerido' => $tiempo,
                'Imagen' => $imagen,
            ];
        }

        DB::table('Animal')->insert($animales);
    }
}