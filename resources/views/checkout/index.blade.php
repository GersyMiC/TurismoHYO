@extends('layouts.base')

@section('titulo', 'Checkout')

@section('contenido')
  <h2 class="h4 mb-3">Finalizar compra</h2>

  <div class="row g-3">
    <div class="col-12 col-lg-8">
      <div class="card">
        <div class="card-body">
          <h6 class="mb-2">Resumen de carrito</h6>
          <ul class="list-group mb-3">
            @foreach($carrito->items as $it)
              @php
                $nombre = $it->personalizacion_id
                  ? $it->personalizacion->paqueteBase->nombre
                  : $it->paquete->nombre;
                $dest   = $it->personalizacion_id
                  ? $it->personalizacion->paqueteBase->destino?->nombre
                  : $it->paquete->destino?->nombre;
              @endphp
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                  <div class="fw-medium">{{ $nombre }}</div>
                  <div class="small text-muted">{{ $dest }}</div>
                  <div class="small">
                    Cant: {{ $it->personalizacion_id ? 1 : $it->cantidad }} —
                    Unit: S/ {{ number_format($it->precio_unitario,2) }}
                  </div>
                </div>
                <div class="fw-bold">S/ {{ number_format($it->precio_total,2) }}</div>
              </li>
            @endforeach
            <li class="list-group-item d-flex justify-content-between">
              <div class="fw-bold">Total</div>
              <div class="fw-bold">S/ {{ number_format($total, 2) }}</div>
            </li>
          </ul>

          <form method="post" action="{{ route('checkout.store') }}">
            @csrf

            <div class="row g-2">
              <div class="col-12 col-md-6">
                <label class="form-label">Nombre de contacto</label>
                <input type="text" name="contacto_nombre" class="form-control" required>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Correo de contacto</label>
                <input type="email" name="contacto_email" class="form-control" required>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Teléfono</label>
                <input type="text" name="contacto_telefono" class="form-control">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Cupón (opcional)</label>
                <input type="text" name="codigo_cupon" class="form-control" placeholder="Ej: BIENVENIDO10">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Método de pago</label>
                <select name="metodo_pago" class="form-select" required>
                  <option value="efectivo">Efectivo (simulado)</option>
                  <option value="tarjeta">Tarjeta (simulado)</option>
                </select>
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

            <div class="form-check mt-3">
              <input class="form-check-input" type="checkbox" name="acepto_terminos" id="t" required>
              <label for="t" class="form-check-label small">
                Acepto términos y condiciones y la política de privacidad.
              </label>
            </div>

            <div class="d-flex gap-2 mt-3">
              <a href="{{ route('carrito.index') }}" class="btn btn-outline-secondary">Volver al carrito</a>
              <button class="btn btn-primary">Confirmar reserva</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="p-3 border rounded">
        <h6 class="mb-2">Seguridad</h6>
        <p class="small m-0">Este checkout es de prueba. Más adelante integraremos pasarela de pago real.</p>
      </div>
    </div>
  </div>
@endsection
