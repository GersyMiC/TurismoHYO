<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class AuthSimpleController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $user = Usuario::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->contrasena_hash)) {
            return back()->withErrors(['email' => 'Credenciales inválidas'])->withInput();
        }

        if ($user->estado !== 'activo') {
            return back()->withErrors(['email' => 'Tu cuenta no está activa'])->withInput();
        }

        // Iniciar sesión (simple por sesión)
        session(['uid' => $user->id]);

        // Redirección suave: si venía de checkout, regresa
        return redirect()->intended(route('home'))->with('ok', 'Bienvenido, '.$user->nombre_completo.'!');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'nombre_completo' => 'required|string|max:120',
            'email'           => 'required|email|max:160|unique:usuarios,email',
            'telefono'        => 'nullable|string|max:30',
            'password'        => 'required|string|min:6|confirmed',
        ]);

        // Se crea el usuario con los datos del formulario
        $user = Usuario::create([
            'nombre_completo'   => $data['nombre_completo'],
            'email'             => $data['email'],
            'telefono'          => $data['telefono'] ?? null,
            'contrasena_hash'   => Hash::make($data['password']),
            'estado'            => 'activo',
            'creado_en'         => now(),
            'actualizado_en'    => now(),
        ]);

        // Asignamos el rol "cliente" al usuario nuevo por defecto
        $roleCliente = \App\Models\Rol::where('nombre', 'cliente')->first();
        if ($roleCliente) {
            $user->roles()->attach($roleCliente->id);
        }

        // Si el usuario es un administrador, podemos asignar roles adicionales
        // Solo el admin puede crear un admin o agente (ejemplo de restricción)
        if (session()->has('uid')) { // Si hay sesión activa (probablemente admin)
            $adminRole = \App\Models\Rol::where('nombre', 'admin')->first();
            if ($adminRole) {
                $user->roles()->attach($adminRole->id); // Asignar el rol de admin si el usuario lo es
            }
        }

        session(['uid' => $user->id]);

        return redirect()->route('home')->with('ok', 'Registro exitoso. ¡Bienvenido!');
    }


    public function logout()
    {
        session()->forget('uid');
        return redirect()->route('home')->with('ok', 'Sesión cerrada.');
    }
}
