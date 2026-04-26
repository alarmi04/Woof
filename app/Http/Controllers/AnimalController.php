<?php

namespace App\Http\Controllers;
use App\Models\Animal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnimalController extends Controller
{
    public function index(Request $request)
    {
        $query = Animal::where('Estado', 'Disponible');

        if ($request->tamano !== "todos") {
            $query->where('Tamano', $request->tamano);
        }

        if ($request->genero !== "todos") {
            $query->where('Genero', $request->genero);
        }

        if ($request->edad !== "todos") {
            $query->where('Edad', $request->edad);
        }

        $perros = $query->get();

        return Inertia::render('Perros/Index', [
            'perros' => $perros,
            'filtros' => $request->only(['tamano', 'edad', 'genero'])
        ]);
    }
}
