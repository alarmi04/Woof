<?php
/**
 * ==============================================================================
 * FICHERO: app/Http/Controllers/ContactoController.php
 * AUTOR: Alberto
 * FECHA CREACIÓN: 2026-04-25
 * FUNCIÓN: Controlador público que gestiona la carga de la página de contacto
 *          y el procesamiento/envío de emails al albergue.
 * ==============================================================================
 */

namespace App\Http\Controllers;

use App\Mail\ContactoMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ContactoController extends Controller
{
    /**
     * MÉTODO: index
     * AUTOR: Alberto
     * FECHA CREACIÓN: 2026-04-25
     * FUNCIÓN: Carga la vista reactiva de contacto mediante Inertia.
     */
    public function index()
    {
        return Inertia::render('Contacto');
    }

    /**
     * MÉTODO: send
     * AUTOR: Alberto
     * FECHA CREACIÓN: 2026-04-25
     * FUNCIÓN: Valida la petición del formulario de contacto y envía un correo electrónico.
     */
    public function send(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'asunto' => 'required|string|max:255',
            'mensaje' => 'required|string',
        ]);

        // Enviar el correo al administrador (puedes cambiar esta dirección)
        Mail::to('woofadopta@gmail.com')->send(new ContactoMail($data));

        return back()->with('success', '¡Mensaje enviado correctamente! Nos pondremos en contacto contigo pronto.');
    }
}
