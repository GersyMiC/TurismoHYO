<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\ReservaItem;  // Asegúrate de importar este modelo
use App\Models\Paquete;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    public function index(Request $request)
    {
        // Fechas base
        $hoy       = Carbon::today();
        $inicioMes = $hoy->copy()->startOfMonth();
        $finMes    = $hoy->copy()->endOfMonth();

        // 1) KPIs generales
        $totalReservas = Reserva::count();
        $reservasHoy   = Reserva::whereDate('creado_en', $hoy)->count();
        $reservasMes   = Reserva::whereBetween('creado_en', [$inicioMes, $finMes])->count();

        // 2) KPIs de dinero
        $ingresosTotales = Reserva::where('estado', 'pagado')->sum('total');
        $ingresosMes     = Reserva::where('estado', 'pagado')
            ->whereBetween('creado_en', [$inicioMes, $finMes])
            ->sum('total');

        // 3) Reservas por estado
        $reservasPorEstado = Reserva::select('estado', DB::raw('COUNT(*) as cantidad'))
            ->groupBy('estado')
            ->get();

        // 4) Top 5 clientes con más reservas
        $topClientes = Reserva::select('usuario_id', DB::raw('COUNT(*) as reservas'))
            ->groupBy('usuario_id')
            ->orderByDesc('reservas')
            ->with('usuario')
            ->limit(5)
            ->get();

        // 5) Reservas por mes (últimos 6 meses) para el gráfico de barras de reservas por mes
        $reservasPorMes = Reserva::select(
                DB::raw("DATE_FORMAT(creado_en, '%Y-%m') as ym"),
                DB::raw('COUNT(*) as cantidad'),
                DB::raw('SUM(total) as total_mes')
            )
            ->where('creado_en', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('ym')
            ->orderBy('ym')
            ->get();

        $labelsMes       = $reservasPorMes->map(function ($r) {
            return Carbon::createFromFormat('Y-m', $r->ym)->format('m/Y');
        });
        $dataReservasMes = $reservasPorMes->pluck('cantidad');
        $dataIngresosMes = $reservasPorMes->pluck('total_mes');

        // 6) Gráfico de reservas o ingresos por año (con selección de año)
        // Obtener el año seleccionado desde la solicitud o el actual
        $anio = $request->input('anio', Carbon::now()->year);

        // Obtener los datos para el año seleccionado
        $reservasPorAnio = Reserva::select(
                DB::raw("DATE_FORMAT(creado_en, '%m') as mes"),
                DB::raw('COUNT(*) as cantidad'),
                DB::raw('SUM(total) as total_mes')
            )
            ->whereYear('creado_en', $anio)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $labelsAnio       = $reservasPorAnio->map(function ($r) {
            return Carbon::createFromFormat('m', $r->mes)->format('F');
        });
        $dataReservasAnio = $reservasPorAnio->pluck('cantidad');
        $dataIngresosAnio = $reservasPorAnio->pluck('total_mes');

        // 7) Top destinos
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
            'labelsAnio',
            'dataReservasAnio',
            'dataIngresosAnio',
            'topDestinos'
        ));
    }

    public function reservas(Request $request)
    {
        // Filtros
        $cliente = $request->input('cliente');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
        $destino = $request->input('destino');

        // Construir la consulta para las reservas
        $query = Reserva::query();

        // Filtrar por cliente (usuario)
        if ($cliente) {
            $query->whereHas('usuario', function ($q) use ($cliente) {
                $q->where('nombre_completo', 'like', '%' . $cliente . '%'); // Cambiar 'nombre' por 'nombre_completo'
            });
        }

        // Filtrar por contacto_nombre también
        if ($cliente) {
            $query->orWhere('contacto_nombre', 'like', '%' . $cliente . '%');
        }

        // Filtrar por código de reserva
        if ($cliente) {
            $query->orWhere('codigo', 'like', '%' . $cliente . '%');
        }

        // Filtrar por fechas
        if ($fechaInicio) {
            $query->where('fecha_inicio', '>=', $fechaInicio);
        }

        if ($fechaFin) {
            $query->where('fecha_fin', '<=', $fechaFin);
        }

        // Filtrar por destino (relacionado con los paquetes de reserva)
        if ($destino) {
            $query->whereHas('items', function ($q) use ($destino) {
                $q->whereHas('paquete', function ($q2) use ($destino) {
                    //$q2->where('destino', 'like', '%' . $destino . '%');
                    $q2->where('destino_id', '=', $destino);
                });
            });
        }

        // Obtener las reservas filtradas
        $reservas = $query->get();

        // Obtener los destinos disponibles para el filtro
        //$destinos = Paquete::with('destino')->get()->pluck('destino.nombre')->unique();
        $destinos = Paquete::join('destinos', 'paquetes.destino_id', '=', 'destinos.id')
                   ->distinct()
                   ->pluck('destinos.nombre', 'destinos.id');

        return view('admin.reservas', compact('reservas', 'destinos'));
    }

    public function updateReservaEstado(Request $request, $id)
    {
        $reserva = Reserva::findOrFail($id);
        $reserva->estado = $request->input('estado');
        $reserva->save();

        return redirect()->route('admin.reservas')->with('success', 'Estado de reserva actualizado.');
    }


}
