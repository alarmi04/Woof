<?php

namespace App\Http\Controllers;

use App\Mail\ContactoMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ContactoController extends Controller
{
    public function index()
    {
        return Inertia::render('Contacto');
    }

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
