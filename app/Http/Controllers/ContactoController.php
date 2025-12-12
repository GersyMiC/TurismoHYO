<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactoController extends Controller
{
    // Función para mostrar la vista de contacto
    public function index()
    {
        return view('contacto');
    }

    // Función para manejar el envío del formulario de contacto
    public function enviar(Request $request)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email',
            'mensaje' => 'required|string',
        ]);

        // Aquí puedes agregar la lógica para enviar un correo o guardar los datos en la base de datos
        // Enviar un correo (si tienes configuración de correo)
        // Mail::to('admin@example.com')->send(new ContactoMensaje($validated));

        // Retornar una respuesta de éxito
        return back()->with('success', 'Tu mensaje ha sido enviado exitosamente.');
    }
}
