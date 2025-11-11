<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paquete;
use App\Models\Destino;
use App\Models\Actividad;
use App\Models\Alojamiento;
use App\Models\Transporte;
use App\Models\Personalizacion;
use App\Models\Usuario;
use Carbon\Carbon;

class PersonalizarController extends Controller
{
    // Formulario
    public function create(string $slug)
    {
        $paquete  = Paquete::with('destino')->where('slug', $slug)->where('activo', true)->firstOrFail();
        $destino  = $paquete->destino;

        // Ofrecemos opciones del mismo destino
        $actividades = Actividad::where('destino_id', $destino->id)->where('activo', true)->orderBy('nombre')->get();
        $alojamientos = Alojamiento::where('destino_id', $destino->id)->where('activo', true)->orderBy('categoria')->get();
        $transportes = Transporte::where('hasta_destino_id', $destino->id)->where('activo', true)->orderBy('tipo')->get();

        return view('personalizar.create', compact('paquete','destino','actividades','alojamientos','transportes'));
    }

    // Guarda la personalización y calcula precio
    public function store(Request $request)
    {
        $data = $request->validate([
            'paquete_id'       => 'required|exists:paquetes,id',
            'fecha_inicio'     => 'nullable|date',
            'fecha_fin'        => 'nullable|date|after_or_equal:fecha_inicio',
            'pax_adultos'      => 'required|integer|min:1',
            'pax_ninos'        => 'required|integer|min:0',
            'actividades'      => 'array',
            'actividades.*'    => 'integer|exists:actividades,id',
            'alojamiento_id'   => 'nullable|integer|exists:alojamientos,id',
            'transporte_id'    => 'nullable|integer|exists:transportes,id',
        ]);

        $paquete = Paquete::with('destino')->findOrFail($data['paquete_id']);

        // Cálculo de noches
        $noches_calculadas = 0;
        if (!empty($data['fecha_inicio']) && !empty($data['fecha_fin'])) {
            $fi = Carbon::parse($data['fecha_inicio']);
            $ff = Carbon::parse($data['fecha_fin']);
            $noches_calculadas = max(0, $fi->diffInDays($ff));
        }
        // Si no hay fechas, usa las noches referenciales del paquete
        if ($noches_calculadas === 0 && $paquete->noches > 0) {
            $noches_calculadas = $paquete->noches;
        }

        $pax_total = (int)$data['pax_adultos'] + (int)$data['pax_ninos'];

        // Cargar selecciones
        $actividadesSel = collect($data['actividades'] ?? [])->unique()->values();
        $alojamiento = !empty($data['alojamiento_id']) ? Alojamiento::find($data['alojamiento_id']) : null;
        $transporte  = !empty($data['transporte_id'])  ? Transporte::find($data['transporte_id'])  : null;

        // Precios
        $base = (float)$paquete->precio_desde * $pax_total;

        $costoActividades = 0.0;
        if ($actividadesSel->count()) {
            $costoActividades = Actividad::whereIn('id', $actividadesSel)->sum('precio_base');
            $costoActividades = (float)$costoActividades * $pax_total;
        }

        $costoAlojamiento = 0.0;
        if ($alojamiento && $noches_calculadas > 0) {
            $costoAlojamiento = (float)$alojamiento->precio_noche * $noches_calculadas * $pax_total;
        }

        $costoTransporte = 0.0;
        if ($transporte) {
            $costoTransporte = (float)$transporte->precio_base * $pax_total;
        }

        $total = round($base + $costoActividades + $costoAlojamiento + $costoTransporte, 2);

        // Selecciones para guardar
        $selecciones = [
            'actividades'    => $actividadesSel->all(),
            'alojamiento_id' => $alojamiento?->id,
            'transporte_id'  => $transporte?->id,
            'noches'         => $noches_calculadas,
        ];

        $desglose = [
            'base_por_persona' => (float)$paquete->precio_desde,
            'pax_total'        => $pax_total,
            'base_total'       => round($base, 2),
            'actividades'      => round($costoActividades, 2),
            'alojamiento'      => round($costoAlojamiento, 2),
            'transporte'       => round($costoTransporte, 2),
            'total'            => $total,
        ];

        // Usuario (sin auth): toma el primer usuario disponible
        $usuarioId = Usuario::value('id');
        if (!$usuarioId) {
            abort(500, 'No hay usuarios disponibles para asociar la personalización.');
        }

        $personalizacion = Personalizacion::create([
            'usuario_id'            => $usuarioId,
            'paquete_base_id'       => $paquete->id,
            'fecha_inicio'          => $data['fecha_inicio'] ?? null,
            'fecha_fin'             => $data['fecha_fin'] ?? null,
            'pax_adultos'           => $data['pax_adultos'],
            'pax_ninos'             => $data['pax_ninos'],
            'selecciones_json'      => $selecciones,
            'desglose_precios_json' => $desglose,
            'precio_total'          => $total,
            'creado_en'             => now(),
            'actualizado_en'        => now(),
        ]);

        return redirect()->route('personalizacion.show', $personalizacion->id)
                         ->with('ok', 'Personalización guardada.');
    }

    // Resumen
    public function show(int $id)
    {
        $p = Personalizacion::with('paqueteBase.destino')->findOrFail($id);
        return view('personalizar.show', compact('p'));
    }
}

