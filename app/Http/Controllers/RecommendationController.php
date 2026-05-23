<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * Controlador para gestionar las recomendaciones personalizadas de animales
 * utilizando un motor de inteligencia artificial en Python.
 */
class RecommendationController extends Controller
{
    /**
     * Muestra la página de perros recomendados para el usuario actual.
     * Realiza una validación del perfil y consulta al script de Python para obtener los mejores matches.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $usuario = Auth::user();
        
        // Si no hay usuario, mostramos la vista sin datos (el frontend manejará el muro de registro)
        if (!$usuario) {
            return Inertia::render('PerrosRecomendados', [
                'perros' => [],
                'perfilIncompleto' => false
            ]);
        }

        /**
         * Verificamos si el usuario ha completado los campos necesarios para que la IA funcione correctamente.
         */
        $perfilIncompleto = empty($usuario->Tipo_vivienda) || 
                           is_null($usuario->Nivel_actividad) || 
                           is_null($usuario->Experiencia_mascotas) || 
                           is_null($usuario->Tiempo_disponible);

        if ($perfilIncompleto) {
            return Inertia::render('PerrosRecomendados', [
                'perros' => [],
                'perfilIncompleto' => true
            ]);
        }

        /**
         * Obtenemos todos los animales de la base de datos que NO estén adoptados para enviarlos al recomendador de Python.
         */
        $animales = Animal::where('Estado', '!=', 'adoptado')->get();

        // Estructura de datos para enviar al script externo
        $datosParaPython = [
            'usuario' => $usuario->toArray(),
            'perros' => $animales->toArray()
        ];

        /**
         * Ejecutamos el script de Python enviando los datos por la entrada estándar (stdin).
         */
        $proceso = new Process(['python', base_path('python_logic/recomendar.py')]);
        $proceso->setInput(json_encode($datosParaPython));
        $proceso->run();

        // Control de errores en la ejecución del script
        if (!$proceso->isSuccessful()) {
            \Illuminate\Support\Facades\Log::error('Error en el script de Python: ' . $proceso->getErrorOutput());
            return Inertia::render('PerrosRecomendados', [
                'perros' => [],
                'error' => 'No se pudo generar las recomendaciones en este momento.'
            ]);
        }

        /**
         * Procesamos la salida de Python.
         * Se espera un JSON con objetos que contienen el 'id' del animal y la 'reason' (razón del match).
         */
        $resultadosPython = json_decode($proceso->getOutput(), true);
        $idsRecomendados = array_column($resultadosPython, 'id');
        $razonesMatch = array_column($resultadosPython, 'reason', 'id');

        /**
         * Recuperamos los modelos de los animales y les inyectamos la razón del match.
         * Mantenemos el orden de relevancia devuelto por la IA.
         */
        $perrosRecomendados = Animal::whereIn('idAnimal', $idsRecomendados)
            ->where('Estado', '!=', 'adoptado')
            ->get()
            ->map(function($animal) use ($razonesMatch) {
                $animal->match_reason = $razonesMatch[$animal->idAnimal] ?? '';
                return $animal;
            })
            ->sortBy(function($animal) use ($idsRecomendados) {
                return array_search($animal->idAnimal, $idsRecomendados);
            })->values();

        return Inertia::render('PerrosRecomendados', [
            'perros' => $perrosRecomendados
        ]);
    }
}
