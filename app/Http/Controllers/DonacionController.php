<?php
/**
 * ==============================================================================
 * FICHERO: app/Http/Controllers/DonacionController.php
 * AUTOR: Alberto
 * FECHA CREACIÓN: 2026-05-07
 * FUNCIÓN: Controlador que gestiona el registro de aportaciones materiales
 *          y la visualización agregada del ranking dinámico de donaciones.
 * ==============================================================================
 */

namespace App\Http\Controllers;

use App\Models\Donacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

/**
 * Controlador para gestionar las donaciones y el ranking de donantes.
 */
class DonacionController extends Controller
{
    /**
     * MÉTODO: index
     * AUTOR: Alberto
     * FECHA CREACIÓN: 2026-05-07
     * FUNCIÓN: Muestra la página de donaciones con el ranking dinámico actual
     *          y la lista de materiales requeridos.
     * 
     * @return \Inertia\Response
     */
    public function index()
    {
        /**
         * Obtenemos el ranking de donaciones.
         * Sumamos las cantidades por usuario registrado.
         * Las donaciones anónimas se tratan como individuales (no se acumulan entre sí).
         */
        $usuariosRanking = Donacion::select(
                'Usuario.Nombre as donante',
                DB::raw('SUM(Donacion.Cantidad) as total'),
                'Usuario.Foto',
                'Tipo_donacion as tipo'
            )
            ->join('Usuario', 'Donacion.idUsuario', '=', 'Usuario.idUsuario')
            ->groupBy('Usuario.idUsuario', 'Usuario.Nombre', 'Usuario.Foto', 'Tipo_donacion');

        $anonimosRanking = Donacion::select(
                DB::raw('"Anónimo" as donante'),
                'Cantidad as total',
                DB::raw('NULL as Foto'),
                'Tipo_donacion as tipo'
            )
            ->whereNull('idUsuario');

        $ranking = $usuariosRanking->unionAll($anonimosRanking)
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        $materiales = \App\Models\Material::all();

        return Inertia::render('Donaciones', [
            'ranking' => $ranking,
            'materiales' => $materiales
        ]);
    }

    /**
     * MÉTODO: store
     * AUTOR: Alberto
     * FECHA CREACIÓN: 2026-05-07
     * FUNCIÓN: Valida y persiste una nueva donación de material o monetaria.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'cantidad' => 'required|numeric|min:1',
            'idMaterial' => 'required|exists:Material,idMaterial',
            'observaciones' => 'nullable|string|max:255',
        ]);

        $material = \App\Models\Material::find($request->idMaterial);

        Donacion::create([
            'idUsuario' => Auth::check() ? Auth::user()->idUsuario : null,
            'idMaterial' => $request->idMaterial,
            'Cantidad' => $request->cantidad,
            'Observaciones' => $request->observaciones,
            'Tipo_donacion' => $material->Nombre, // Usamos el nombre del material como tipo
            'Fecha' => now(),
        ]);

        return back()->with('success', '¡Gracias por tu donación! Tu generosidad nos ayuda mucho.');
    }
}
