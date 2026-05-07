<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * Controlador para gestionar la actualización de la contraseña del usuario.
 */
class PasswordController extends Controller
{
    /**
     * Actualiza la contraseña del usuario autenticado.
     * Valida la contraseña actual y la nueva antes de realizar el cambio en la base de datos.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'Contrasena' => Hash::make($validated['password']),
        ]);

        return back();
    }
}
