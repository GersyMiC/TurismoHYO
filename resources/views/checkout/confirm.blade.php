@extends('layouts.base')

@section('titulo', 'Reserva confirmada')

@section('contenido')
  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif

  <div class="card">
    <div class="card-body">
      <h2 class="h5 mb-2">¡Reserva creada!</h2>
      <div class="mb-2">Código de reserva: <span class="fw-bold">{{ $reserva->codigo }}</span></div>
      <div class="small text-muted mb-3">
        Estado: {{ strtoupper($reserva->estado) }} —
        Total: S/ {{ number_format($reserva->total,2) }}
      </div>

      <h6 class="mb-2">Titular / Contacto</h6>
      <ul class="small">
        <li>{{ $reserva->contacto_nombre }}</li>
        <li>{{ $reserva->contacto_email }}</li>
        <li>{{ $reserva->contacto_telefono ?? '—' }}</li>
      </ul>

      <h6 class="mb-2">Detalle</h6>
      <ul class="list-group mb-3">
        @foreach($reserva->items as $it)
          <li class="list-group-item d-flex justify-content-between">
            <div>
              <div class="fw-medium">{{ $it->paquete?->nombre }}</div>
              <div class="small text-muted">{{ $it->paquete?->destino?->nombre }}</div>
              <div class="small">Cant: {{ $it->cantidad }} — Unit: S/ {{ number_format($it->precio_unitario,2) }}</div>
            </div>
            <div class="fw-bold">S/ {{ number_format($it->precio_total,2) }}</div>
          </li>
        @endforeach
      </ul>

      <div class="row">
        <div class="col-12 col-md-6">
          <ul class="list-unstyled small">
            <li>Subtotal: S/ {{ number_format($reserva->subtotal,2) }}</li>
            <li>Descuento: S/ {{ number_format($reserva->descuento,2) }}</li>
            <li class="fw-bold">Total: S/ {{ number_format($reserva->total,2) }}</li>
          </ul>
        </div>
        <div class="col-12 col-md-6">
          <h6 class="mb-2">Pagos</h6>
          <ul class="small">
            @foreach($reserva->pagos as $p)
              <li>{{ strtoupper($p->pasarela) }} — {{ strtoupper($p->estado) }} — S/ {{ number_format($p->monto,2) }}</li>
            @endforeach
          </ul>
        </div>
      </div>

      <div class="d-flex gap-2">
        <a href="{{ route('catalogo.index') }}" class="btn btn-outline-primary">Seguir explorando</a>
        <a href="{{ route('home') }}" class="btn btn-primary">Volver al inicio</a>
      </div>
    </div>
  </div>
@endsection
