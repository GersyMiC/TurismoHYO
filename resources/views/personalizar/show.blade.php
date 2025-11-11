@extends('layouts.base')

@section('titulo', 'Resumen de personalización')

@section('contenido')
  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif

  <nav class="mb-2">
    <a href="{{ route('catalogo.index') }}" class="text-decoration-none">&larr; Volver al catálogo</a>
  </nav>

  <div class="card">
    <div class="card-body">
      <h2 class="h5 mb-1">{{ $p->paqueteBase->nombre }}</h2>
      <div class="small text-muted mb-2">
        {{ $p->paqueteBase->destino?->nombre }} •
        {{ $p->fecha_inicio ?? 's/f' }} → {{ $p->fecha_fin ?? 's/f' }} •
        Adultos: {{ $p->pax_adultos }} — Niños: {{ $p->pax_ninos }}
      </div>

      <hr>

      @php
        $sel = $p->selecciones_json ?? [];
        $des = $p->desglose_precios_json ?? [];
      @endphp

      <h6 class="mb-2">Desglose de precios</h6>
      <ul class="small">
        <li>Base por persona: S/ {{ number_format($des['base_por_persona'] ?? 0, 2) }}</li>
        <li>Pasajeros: {{ $des['pax_total'] ?? ($p->pax_adultos + $p->pax_ninos) }}</li>
        <li>Actividades: S/ {{ number_format($des['actividades'] ?? 0, 2) }}</li>
        <li>Alojamiento: S/ {{ number_format($des['alojamiento'] ?? 0, 2) }}</li>
        <li>Transporte: S/ {{ number_format($des['transporte'] ?? 0, 2) }}</li>
      </ul>

      <div class="h5">Total: S/ {{ number_format($p->precio_total, 2) }}</div>

      <hr>
      <div class="d-flex gap-2">
        <a href="{{ route('paquete.show', $p->paqueteBase->slug) }}" class="btn btn-outline-secondary">Volver al paquete</a>

        <form method="post" action="{{ route('carrito.agregar') }}">
            @csrf
            <input type="hidden" name="paquete_id" value="{{ $p->paqueteBase->id }}">
            <input type="hidden" name="personalizacion_id" value="{{ $p->id }}">
            <button class="btn btn-primary">Agregar al carrito</button>
        </form>

        <a href="{{ route('carrito.index') }}" class="btn btn-outline-primary">Ver carrito</a>
      </div>

    </div>
  </div>
@endsection
