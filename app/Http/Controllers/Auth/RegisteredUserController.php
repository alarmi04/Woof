<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'                 => 'required|string|max:255',
            'email'                => 'required|string|lowercase|email|max:255|unique:'.Usuario::class.',Correo',
            'password'             => ['required', 'confirmed', Rules\Password::defaults()],
            'tipo_vivienda'        => 'nullable|string|in:piso,casa_sin_jardin,casa_con_jardin',
            'numero_hijos'         => 'nullable|integer|min:0|max:20',
            'nivel_actividad'      => 'nullable|integer|in:1,2,3',
            'experiencia_mascotas' => 'nullable|integer|in:0,1,2',
            'tiempo_disponible'    => 'nullable|integer|min:1|max:24',
        ]);

        $user = Usuario::create([
            'Nombre'               => $request->name,
            'Correo'               => $request->email,
            'Contrasena'           => Hash::make($request->password),
            'Tipo_vivienda'        => $request->tipo_vivienda,
            'Numero_hijos'         => $request->numero_hijos ?? 0,
            'Nivel_actividad'      => $request->nivel_actividad,
            'Experiencia_mascotas' => $request->experiencia_mascotas,
            'Tiempo_disponible'    => $request->tiempo_disponible,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('profile.edit'));
    }
}
