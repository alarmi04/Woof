<?php

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
     * Muestra la página de donaciones con el ranking actual.
     * 
     * @return \Inertia\Response
     */
    public function index()
    {
        /**
         * Obtenemos el ranking de donaciones.
         * Sumamos las cantidades por usuario y traemos el nombre del usuario.
         * Si el usuario es NULL, lo agrupamos como 'Anónimo'.
         */
        $ranking = Donacion::select(
                DB::raw('COALESCE(Usuario.Nombre, "Anónimo") as donante'),
                DB::raw('SUM(Cantidad) as total')
            )
            ->leftJoin('Usuario', 'Donacion.idUsuario', '=', 'Usuario.idUsuario')
            ->groupBy('donante')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        return Inertia::render('Donaciones', [
            'ranking' => $ranking
        ]);
    }

    /**
     * Procesa y guarda una nueva donación.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'cantidad' => 'required|numeric|min:1',
            'mensaje' => 'nullable|string|max:255',
        ]);

        Donacion::create([
            'idUsuario' => Auth::check() ? Auth::user()->idUsuario : null,
            'Cantidad' => $request->cantidad,
            'Mensaje' => $request->mensaje,
            'Fecha' => now(),
        ]);

        return back()->with('success', '¡Gracias por tu donación! Tu generosidad nos ayuda mucho.');
    }
}
