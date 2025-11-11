@extends('layouts.base')

@section('titulo', $paquete->nombre.' | Turismo HYO')

@section('contenido')
  <nav class="mb-2">
    <a href="{{ route('catalogo.index') }}" class="text-decoration-none">&larr; Volver al catálogo</a>
  </nav>

  <div class="row g-3">
    <div class="col-12 col-lg-8">
      <div class="card">
        <img src="https://picsum.photos/seed/{{ $paquete->slug }}-big/1200/500" class="card-img-top" alt="{{ $paquete->nombre }}">
        <div class="card-body">
          <h2 class="h4 mb-1">{{ $paquete->nombre }}</h2>
          <div class="small text-muted mb-3">
            {{ $paquete->destino?->nombre }} • {{ $paquete->dias }} días / {{ $paquete->noches }} noches
          </div>
          <p class="mb-3">{{ $paquete->descripcion ?? $paquete->resumen }}</p>

          @if($paquete->actividades->count())
            <h6 class="mt-3">Actividades incluidas / sugeridas</h6>
            <ul class="small">
              @foreach($paquete->actividades as $a)
                <li>{{ $a->nombre }} <span class="text-muted">({{ $a->tipo ?? 'actividad' }})</span></li>
              @endforeach
            </ul>
          @endif

          @if($paquete->alojamientos->count())
            <h6 class="mt-3">Alojamientos asociados</h6>
            <ul class="small">
              @foreach($paquete->alojamientos as $h)
                <li>{{ $h->nombre }} — {{ $h->categoria }}</li>
              @endforeach
            </ul>
          @endif
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card">
        <div class="card-body">
          <div class="small text-muted">Precio desde</div>
          <div class="display-6 fw-bold">S/ {{ number_format($paquete->precio_desde, 2) }}</div>
          <p class="small text-muted mb-3">Precio referencial. El total puede variar según personalización.</p>


          <div class="d-grid gap-2">
            <a href="{{ route('personalizar.create', $paquete->slug) }}" class="btn btn-primary btn-lg">
                Personalizar este paquete
            </a>

            <form method="post" action="{{ route('carrito.agregar') }}">
                @csrf
                <input type="hidden" name="paquete_id" value="{{ $paquete->id }}">
                <input type="hidden" name="cantidad" value="1">
                <button class="btn btn-outline-secondary">Agregar base (1 persona)</button>
            </form>
          </div>


          <hr>
          <div class="small">
            <div>Incluye: guía local, soporte 24/7, coordinación logística.</div>
            <div>No incluye: gastos personales, propinas, extras no indicados.</div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('aside')
  <div class="p-3 border rounded">
    <h6 class="mb-2">Destino</h6>
    <p class="small m-0">{{ $paquete->destino?->nombre }} — Perú</p>
  </div>
@endsection
