@extends('layouts.base')

@section('titulo', 'Mi carrito')

@section('contenido')
  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif

  <h2 class="h4 mb-3">Mi carrito</h2>

  @if($carrito->items->isEmpty())
    <div class="alert alert-info">Tu carrito está vacío.</div>
    <a href="{{ route('catalogo.index') }}" class="btn btn-primary">Ir al catálogo</a>
  @else
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>Paquete</th>
            <th>Destino</th>
            <th class="text-center">Personalización</th>
            <th class="text-center">Cantidad</th>
            <th class="text-end">Precio unitario (S/)</th>
            <th class="text-end">Subtotal (S/)</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach($carrito->items as $it)
            @php
              $nombre = $it->personalizacion_id
                ? $it->personalizacion->paqueteBase->nombre
                : $it->paquete->nombre;

              $destino = $it->personalizacion_id
                ? $it->personalizacion->paqueteBase->destino?->nombre
                : $it->paquete->destino?->nombre;

              $esPers = (bool)$it->personalizacion_id;
            @endphp

            <tr>
              <td class="fw-medium">{{ $nombre }}</td>
              <td>{{ $destino }}</td>
              <td class="text-center">
                @if($esPers)
                  <span class="badge text-bg-primary">Sí</span>
                @else
                  <span class="badge text-bg-secondary">No</span>
                @endif
              </td>
              <td class="text-center">
                <form method="post" action="{{ route('carrito.actualizar', $it->id) }}" class="d-inline">
                  @csrf
                  <div class="input-group input-group-sm" style="max-width: 120px; margin: 0 auto;">
                    <input type="number" name="cantidad" class="form-control"
                           min="0"
                           value="{{ $esPers ? 1 : $it->cantidad }}"
                           {{ $esPers ? 'readonly' : '' }}>
                    <button class="btn btn-outline-secondary" {{ $esPers ? 'disabled' : '' }}>OK</button>
                  </div>
                  <div class="form-text">0 = eliminar</div>
                </form>
              </td>
              <td class="text-end">{{ number_format($it->precio_unitario, 2) }}</td>
              <td class="text-end fw-bold">{{ number_format($it->precio_total, 2) }}</td>
              <td class="text-end">
                <form method="post" action="{{ route('carrito.eliminar', $it->id) }}" class="d-inline"
                      onsubmit="return confirm('¿Eliminar este item?')">
                  @csrf
                  <button class="btn btn-sm btn-outline-danger">Eliminar</button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr>
            <th colspan="5" class="text-end">Total</th>
            <th class="text-end h5">{{ number_format($total, 2) }}</th>
            <th></th>
          </tr>
        </tfoot>
      </table>
    </div>

    <div class="d-flex gap-2">
      <form method="post" action="{{ route('carrito.vaciar') }}" onsubmit="return confirm('¿Vaciar carrito?')">
        @csrf
        <button class="btn btn-outline-secondary">Vaciar carrito</button>
      </form>
      <a href="{{ route('catalogo.index') }}" class="btn btn-outline-primary">Seguir comprando</a>
      <a href="{{ route('checkout.index') }}" class="btn btn-primary">Continuar a checkout</a>
    </div>
  @endif
@endsection
