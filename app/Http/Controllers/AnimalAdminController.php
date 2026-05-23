<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

/**
 * Controlador para la administración de animales (perros).
 * Solo los usuarios con rol de administrador pueden acceder.
 */
class AnimalAdminController extends Controller
{
    /**
     * Verifica si el usuario autenticado es administrador.
     */
    private function verificarAdmin()
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }
    }

    /**
     * Mostrar la página de índice.
     */
    public function index(Request $request)
    {
        $this->verificarAdmin();
        $animales = Animal::all();
        
        return Inertia::render('Admin/AnimalManagement', [
            'animales' => $animales,
        ]);
    }

    /**
     * Mostrar el formulario para crear un nuevo recurso.
     */
    public function create(Request $request)
    {
        $this->verificarAdmin();
        return Inertia::render('Admin/AnimalForm', [
            'animal' => null,
            'accion' => 'crear',
        ]);
    }

    /**
     * Almacenar un nuevo recurso en la base de datos.
     */
    public function store(Request $request)
    {
        $this->verificarAdmin();
        $validado = $request->validate([
            'Nombre' => 'required|string|max:100',
            'Raza' => 'required|string|max:100',
            'Genero' => 'required|in:Macho,Hembra',
            'Edad' => 'required|in:joven,adulto,senior',
            'Tamano' => 'required|in:pequeño,mediano,grande',
            'Estado' => 'required|in:disponible,adoptado,pendiente',
            'Descripcion' => 'required|string',
            'Nivel_actividad' => 'required|integer|min:1|max:5',
            'Vivienda_recomendada' => 'required|in:piso,casa,casa_con_jardin',
            'Apto_ninos' => 'required|boolean',
            'Experiencia_requerida' => 'required|boolean',
            'Tiempo_requerido' => 'nullable|integer',
            'Imagen' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
        ]);

        // Procesar imagen si la hay
        if ($request->hasFile('Imagen')) {
            $validado['Imagen'] = $request->file('Imagen')->store('perros', 'public');
        }

        Animal::create($validado);

        return redirect()->route('admin.animales.index')->with('success', 'Perro creado exitosamente');
    }

    /**
     * Muestra el formulario para editar un animal.
     */
    public function edit(Animal $animal)
    {
        $this->verificarAdmin();
        return Inertia::render('Admin/AnimalForm', [
            'animal' => $animal,
            'accion' => 'editar',
        ]);
    }

    /**
     * Actualiza un animal en la base de datos.
     */
    public function update(Request $request, Animal $animal)
    {
        $this->verificarAdmin();
        
        // Si solo se actualiza el estado (desde botón Adoptado)
        if ($request->has('Estado') && count($request->all()) === 1) {
            $validado = $request->validate([
                'Estado' => 'required|in:disponible,adoptado,pendiente',
            ]);
        } else {
            // Validación completa para edición desde formulario
            $validado = $request->validate([
                'Nombre' => 'required|string|max:100',
                'Raza' => 'required|string|max:100',
                'Genero' => 'required|in:Macho,Hembra',
                'Edad' => 'required|in:joven,adulto,senior',
                'Tamano' => 'required|in:pequeño,mediano,grande',
                'Estado' => 'required|in:disponible,adoptado,pendiente',
                'Descripcion' => 'required|string',
                'Nivel_actividad' => 'required|integer|min:1|max:5',
                'Vivienda_recomendada' => 'required|in:piso,casa,casa_con_jardin',
                'Apto_ninos' => 'required|boolean',
                'Experiencia_requerida' => 'required|boolean',
                'Tiempo_requerido' => 'nullable|integer',
                'Imagen' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            ]);

            // Procesar imagen si la hay
            if ($request->hasFile('Imagen')) {
                // Eliminar imagen anterior si existe
                if ($animal->Imagen) {
                    Storage::disk('public')->delete($animal->Imagen);
                }
                $validado['Imagen'] = $request->file('Imagen')->store('perros', 'public');
            }
        }

        $animal->update($validado);

        return redirect()->route('admin.animales.index')->with('success', 'Perro actualizado exitosamente');
    }

    /**
     * Elimina un animal de la base de datos.
     */
    public function destroy(Animal $animal)
    {
        $this->verificarAdmin();
        $nombre = $animal->Nombre;
        $animal->delete();

        return redirect()->route('admin.animales.index')->with('success', "Perro $nombre eliminado exitosamente");
    }
}
