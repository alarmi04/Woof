<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;
use Inertia\Inertia;

/**
 * Controlador para gestionar las operaciones relacionadas con los Animales (perros).
 * Maneja el listado, filtrado y visualización de los animales en adopción.
 */
class AnimalController extends Controller
{
    /**
     * Muestra el listado de animales con opciones de filtrado.
     * 
     * @param \Illuminate\Http\Request $request Objeto con los parámetros de la petición (filtros).
     * @return \Inertia\Response Renderiza la vista 'Adopta' con los datos filtrados.
     */
    public function index(Request $request)
    {
        // Iniciamos la consulta base
        $consulta = Animal::query();

        // Filtrado por tamaño
        if ($request->has('tamano') && $request->tamano != 'todos') {
            $consulta->where('Tamano', 'LIKE', $request->tamano);
        }

        // Filtrado por edad
        if ($request->has('edad') && $request->edad != 'todos') {
            $consulta->where('Edad', 'LIKE', $request->edad);
        }

        // Filtrado por género
        if ($request->has('genero') && $request->genero != 'todos') {
            $consulta->where('Genero', 'LIKE', $request->genero);
        }

        // Ejecutamos la consulta y obtenemos los resultados
        $perros = $consulta->get();

        /**
         * Devolvemos la respuesta a través de Inertia, pasando los perros 
         * y los filtros aplicados para mantener el estado en el frontend.
         */
        return Inertia::render('Adopta', [
            'perros' => $perros,
            'filtros' => $request->only(['tamano', 'edad', 'genero']),
        ]);
    }
}
