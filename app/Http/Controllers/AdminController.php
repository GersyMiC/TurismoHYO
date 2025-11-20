<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\ReservaItem; // importante para Top destinos
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        // Fechas base
        $hoy       = Carbon::today();
        $inicioMes = $hoy->copy()->startOfMonth();
        $finMes    = $hoy->copy()->endOfMonth();

        // 1) KPIs generales
        $totalReservas = Reserva::count();
        $reservasHoy   = Reserva::whereDate('creado_en', $hoy)->count();
        $reservasMes   = Reserva::whereBetween('creado_en', [$inicioMes, $finMes])->count();

        // 2) KPIs de dinero (ajusta el estado según uses en tu sistema: 'confirmada', 'pagada', etc.)
        $ingresosTotales = Reserva::where('estado', 'pagado')->sum('total');

        $ingresosMes = Reserva::where('estado', 'pagado')
            ->whereBetween('creado_en', [$inicioMes, $finMes])
            ->sum('total');

        // 3) Reservas por estado (para tabla + gráfica)
        $reservasPorEstado = Reserva::select('estado', DB::raw('COUNT(*) as cantidad'))
            ->groupBy('estado')
            ->get();

        // 4) Top 5 clientes con más reservas
        $topClientes = Reserva::select('usuario_id', DB::raw('COUNT(*) as reservas'))
            ->groupBy('usuario_id')
            ->orderByDesc('reservas')
            ->with('usuario')   // asumiendo relación usuario() ya definida en Reserva
            ->limit(5)
            ->get();

        // 5) Reservas por mes (últimos 6 meses) -> para gráfica
        $desdeMes = Carbon::now()->subMonths(5)->startOfMonth();

        $reservasPorMes = Reserva::select(
                DB::raw("DATE_FORMAT(creado_en, '%Y-%m') as ym"),
                DB::raw('COUNT(*) as cantidad'),
                DB::raw('SUM(total) as total_mes')
            )
            ->where('creado_en', '>=', $desdeMes)
            ->groupBy('ym')
            ->orderBy('ym')
            ->get();

        $labelsMes       = $reservasPorMes->map(function ($r) {
            return Carbon::createFromFormat('Y-m', $r->ym)->format('m/Y');
        });
        $dataReservasMes = $reservasPorMes->pluck('cantidad');
        $dataIngresosMes = $reservasPorMes->pluck('total_mes');

        // 6) Top destinos (a través de ReservaItem)
        // Asumiendo que ReservaItem tiene FK 'paquete_turistico_id'
        // y relación 'paquete' -> PaqueteTuristico
        $topDestinos = ReservaItem::select('paquete_id', DB::raw('COUNT(*) as reservas'))
            ->groupBy('paquete_id')
            ->with(['paquete.destino'])
            ->orderByDesc('reservas')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalReservas',
            'reservasHoy',
            'reservasMes',
            'ingresosTotales',
            'ingresosMes',
            'reservasPorEstado',
            'topClientes',
            'labelsMes',
            'dataReservasMes',
            'dataIngresosMes',
            'topDestinos'
        ));
    }
}
