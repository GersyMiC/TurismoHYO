<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;

class MisReservasController extends Controller
{
    public function index()
    {
        if (!session()->has('uid')) {
            return redirect()->route('auth.login')
                ->with('error', 'Debes iniciar sesión para ver tus reservas.');
        }

        $usuarioId = session('uid');

        // Reservas del usuario, más recientes primero
        $reservas = Reserva::where('usuario_id', $usuarioId)
            ->orderByDesc('creado_en')
            ->get()
            ->map(function ($reserva) {
                // cantidad de pasajeros a partir de pasajeros_json (array casteado)
                $reserva->cantidad_pasajeros = is_array($reserva->pasajeros_json)
                    ? count($reserva->pasajeros_json)
                    : 0;

                return $reserva;
            });

        return view('mis_reservas.index', compact('reservas'));
    }

    public function show($id)
    {
        if (!session()->has('uid')) {
            return redirect()->route('auth.login')
                ->with('error', 'Debes iniciar sesión para ver tus reservas.');
        }

        $usuarioId = session('uid');

        // Traer una reserva del usuario con items y pagos
        $reserva = Reserva::with(['items', 'pagos'])
            ->where('usuario_id', $usuarioId)
            ->findOrFail($id);

        return view('mis_reservas.show', compact('reserva'));
    }
}
