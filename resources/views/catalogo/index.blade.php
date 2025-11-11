@extends('layouts.base')

@section('titulo', 'Catálogo | Turismo HYO')

@section('contenido')
  <h2 class="h4 mb-3">Catálogo de paquetes</h2>

  {{-- Filtros --}}
  <form method="get" class="row g-2 mb-3">
    <div class="col-12 col-md-4">
      <label class="form-label">Buscar</label>
      <input type="text" name="q" value="{{ $filtros['q'] ?? '' }}" class="form-control" placeholder="Nombre, descripción...">
    </div>

    <div class="col-12 col-md-3">
      <label class="form-label">Destino</label>
      <select name="destino" class="form-select">
        <option value="">Todos</option>
        @foreach($destinos as $d)
          <option value="{{ $d->slug }}" @selected(($filtros['destino'] ?? '') === $d->slug)>{{ $d->nombre }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-6 col-md-2">
      <label class="form-label">Precio min (S/)</label>
      <input type="number" name="precio_min" value="{{ $filtros['precio_min'] ?? '' }}" class="form-control" min="0">
    </div>

    <div class="col-6 col-md-2">
      <label class="form-label">Precio max (S/)</label>
      <input type="number" name="precio_max" value="{{ $filtros['precio_max'] ?? '' }}" class="form-control" min="0">
    </div>

    <div class="col-6 col-md-1">
      <label class="form-label">Días</label>
      <input type="number" name="dias" value="{{ $filtros['dias'] ?? '' }}" class="form-control" min="1">
    </div>

    <div class="col-12 d-flex gap-2">
      <button class="btn btn-primary">Filtrar</button>
      <a href="{{ route('catalogo.index') }}" class="btn btn-outline-secondary">Limpiar</a>
    </div>
  </form>

  {{-- Grid de paquetes --}}
  @if($paquetes->count())
    <div class="row g-3">
      @foreach($paquetes as $p)
        <div class="col-12 col-md-6 col-xl-4">
          <div class="card h-100">
            <img src="https://picsum.photos/seed/{{ $p->slug }}/600/360" class="card-img-top" alt="{{ $p->nombre }}">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title mb-1">{{ $p->nombre }}</h5>
              <div class="small text-muted mb-2">{{ $p->destino?->nombre }} • {{ $p->dias }}d/{{ $p->noches }}n</div>
              <p class="card-text small flex-grow-1">{{ $p->resumen }}</p>
              <div class="d-flex justify-content-between align-items-center mt-2">
                <span class="fw-bold">Desde S/ {{ number_format($p->precio_desde, 2) }}</span>
                <a href="{{ route('paquete.show', $p->slug) }}" class="btn btn-sm btn-outline-primary">Ver detalle</a>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="mt-3">
      {{ $paquetes->links() }}
    </div>
  @else
    <div class="alert alert-info">No se encontraron paquetes con los filtros seleccionados.</div>
  @endif
@endsection

@section('aside')
  <div class="p-3 border rounded">
    <h6 class="mb-2">Consejos</h6>
    <p class="small mb-1">Usa el filtro por destino para ver resultados más precisos.</p>
    <p class="small m-0">¿No encuentras lo que buscas? Luego agregaremos “Personalizar”.</p>
  </div>
@endsection
