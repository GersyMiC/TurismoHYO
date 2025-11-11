<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paquete;
use App\Models\Destino;

class CatalogoController extends Controller
{
    public function index(Request $request)
    {
        // Filtros
        $filtros = [
            'q'          => $request->string('q')->toString(),
            'destino'    => $request->string('destino')->toString(),
            'precio_min' => $request->integer('precio_min'),
            'precio_max' => $request->integer('precio_max'),
            'dias'       => $request->integer('dias'),
        ];

        $query = Paquete::query()->with('destino')->where('activo', true);

        if ($filtros['q']) {
            $q = '%'.$filtros['q'].'%';
            $query->where(function($w) use ($q){
                $w->where('nombre', 'like', $q)
                  ->orWhere('resumen', 'like', $q)
                  ->orWhere('descripcion', 'like', $q);
            });
        }

        if ($filtros['destino']) {
            $query->whereHas('destino', function($d) use ($filtros){
                $d->where('slug', $filtros['destino']);
            });
        }

        if ($filtros['precio_min']) $query->where('precio_desde', '>=', $filtros['precio_min']);
        if ($filtros['precio_max']) $query->where('precio_desde', '<=', $filtros['precio_max']);
        if ($filtros['dias'])       $query->where('dias', $filtros['dias']);

        $paquetes = $query
            ->orderByDesc('destacado')
            ->orderByDesc('creado_en')
            ->paginate(9)
            ->withQueryString();

        $destinos = Destino::where('activo', true)->orderBy('nombre')->get();

        return view('catalogo.index', compact('paquetes', 'destinos', 'filtros'));
    }

    public function show(string $slug)
    {
        $paquete = Paquete::with(['destino','actividades','alojamientos','transportes'])
                    ->where('slug', $slug)
                    ->where('activo', true)
                    ->firstOrFail();

        return view('catalogo.show', compact('paquete'));
    }
}
