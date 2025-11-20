<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Usuario;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // 1) Debe haber alguien logueado
        if (!session()->has('uid')) {
            return redirect()->route('auth.login')
                ->with('error', 'Debes iniciar sesión para acceder al panel de administración.');
        }

        // 2) (Opcional) Verificar que el usuario exista
        $user = Usuario::find(session('uid'));

        if (!$user) {
            session()->forget('uid');
            return redirect()->route('auth.login')
                ->with('error', 'Sesión no válida. Inicia sesión nuevamente.');
        }

        // 3) POR AHORA: dejamos pasar a cualquier usuario logueado
        // Más adelante aquí pondremos la validación real de admin

        return $next($request);
    }
}