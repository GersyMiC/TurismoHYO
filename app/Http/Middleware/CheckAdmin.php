<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('uid')) {
            return redirect()->route('auth.login');
        }

        $user = \App\Models\Usuario::find(session('uid'));

        if (!$user || !$user->roles->contains('admin')) {
            return redirect()->route('home')->with('error', 'Acceso denegado. Solo administradores pueden acceder.');
        }

        return $next($request);
    }
}

