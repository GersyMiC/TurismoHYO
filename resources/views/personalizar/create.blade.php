@extends('layouts.base')

@section('titulo', 'Personalizar: '.$paquete->nombre)

@section('contenido')
  <nav class="mb-2">
    <a href="{{ route('catalogo.index') }}" class="text-decoration-none">&larr; Volver al catálogo</a>
  </nav>

  <div class="row g-3">
    <div class="col-12 col-lg-8">
      <div class="card">
        <div class="card-body">
          <h2 class="h5 mb-1">{{ $paquete->nombre }}</h2>
          <div class="small text-muted mb-3">
            {{ $destino->nombre }} • {{ $paquete->dias }} días / {{ $paquete->noches }} noches • Desde S/ {{ number_format($paquete->precio_desde,2) }}
          </div>

          <form method="post" action="{{ route('personalizar.store') }}">
            @csrf
            <input type="hidden" name="paquete_id" value="{{ $paquete->id }}">

            <div class="row g-3">
              <div class="col-12 col-md-6">
                <label class="form-label">Fecha inicio</label>
                <input type="date" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio') }}">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Fecha fin</label>
                <input type="date" name="fecha_fin" class="form-control" value="{{ old('fecha_fin') }}">
              </div>

              <div class="col-6 col-md-3">
                <label class="form-label">Adultos</label>
                <input type="number" name="pax_adultos" class="form-control" min="1" value="{{ old('pax_adultos',1) }}">
              </div>
              <div class="col-6 col-md-3">
                <label class="form-label">Niños</label>
                <input type="number" name="pax_ninos" class="form-control" min="0" value="{{ old('pax_ninos',0) }}">
              </div>
            </div>

            <hr>

            <h6 class="mb-2">Actividades (opcionales)</h6>
            @if($actividades->count())
              <div class="row row-cols-1 row-cols-md-2 g-2">
                @foreach($actividades as $a)
                  <div class="col">
                    <label class="border rounded p-2 w-100 d-flex justify-content-between align-items-center">
                      <span>
                        <input class="form-check-input me-2" type="checkbox" name="actividades[]" value="{{ $a->id }}">
                        {{ $a->nombre }} <span class="text-muted small">({{ $a->tipo ?? 'actividad' }})</span>
                      </span>
                      <span class="small">S/ {{ number_format($a->precio_base,2) }}</span>
                    </label>
                  </div>
                @endforeach
              </div>
            @else
              <p class="text-muted small">No hay actividades registradas para este destino aún.</p>
            @endif

            <hr>

            <div class="row g-3">
              <div class="col-12 col-md-6">
                <label class="form-label">Alojamiento (opcional)</label>
                <select name="alojamiento_id" class="form-select">
                  <option value="">— Sin alojamiento —</option>
                  @foreach($alojamientos as $h)
                    <option value="{{ $h->id }}">
                      {{ $h->nombre }} — {{ $h->categoria }} — S/ {{ number_format($h->precio_noche,2) }}/noche
                    </option>
                  @endforeach
                </select>
                <div class="form-text">Se multiplicará por el número de noches y pasajeros.</div>
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label">Transporte (opcional)</label>
                <select name="transporte_id" class="form-select">
                  <option value="">— Sin transporte —</option>
                  @foreach($transportes as $t)
                    <option value="{{ $t->id }}">
                      {{ strtoupper($t->tipo) }} {{ $t->proveedor ? '— '.$t->proveedor : '' }} — S/ {{ number_format($t->precio_base,2) }}
                    </option>
                  @endforeach
                </select>
                <div class="form-text">Costo referencial por pasajero.</div>
              </div>
            </div>

            @if ($errors->any())
              <div class="alert alert-danger mt-3">
                <ul class="m-0">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <div class="d-flex gap-2 mt-3">
              <button class="btn btn-primary">Guardar personalización</button>
              <a href="{{ route('catalogo.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="p-3 border rounded">
        <h6 class="mb-2">Consejos</h6>
        <p class="small m-0">Si no defines fechas, se tomará {{ $paquete->noches }} noche(s) como referencia para el cálculo del alojamiento.</p>
      </div>
    </div>
  </div>
@endsection
