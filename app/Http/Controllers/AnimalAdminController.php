<?php
/**
 * ==============================================================================
 * FICHERO: app/Http/Controllers/AnimalAdminController.php
 * AUTOR: Alberto
 * FECHA CREACIÓN: 2026-05-21
 * FUNCIÓN: Controlador de administración (CRUD) para gestionar de forma segura
 *          las fichas de animales en adopción (solo accesible por administradores).
 * ==============================================================================
 */

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
     * MÉTODO: verificarAdmin
     * AUTOR: Alberto
     * FECHA CREACIÓN: 2026-05-21
     * FUNCIÓN: Verifica si el usuario autenticado tiene el rol de administrador,
     *          de lo contrario lanza una excepción HTTP 403.
     */
    private function verificarAdmin()
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }
    }

    /**
     * MÉTODO: index
     * AUTOR: Alberto
     * FECHA CREACIÓN: 2026-05-21
     * FUNCIÓN: Muestra la lista de todos los animales registrados.
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
     * MÉTODO: create
     * AUTOR: Alberto
     * FECHA CREACIÓN: 2026-05-21
     * FUNCIÓN: Muestra el formulario para registrar un nuevo perro.
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
     * MÉTODO: store
     * AUTOR: Alberto
     * FECHA CREACIÓN: 2026-05-21
     * FUNCIÓN: Valida la petición de creación y almacena la ficha con la imagen.
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
            } else {
                // Si no se subió una nueva imagen, mantener la existente
                unset($validado['Imagen']);
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
