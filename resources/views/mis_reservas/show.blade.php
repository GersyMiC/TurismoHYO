@extends('layouts.base')

@section('titulo', 'Detalle de reserva')

@section('contenido')
    <h2 class="mb-3">
        Detalle de la reserva #{{ $reserva->codigo }}
        <span class="badge bg-secondary">{{ ucfirst($reserva->estado) }}</span>
    </h2>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Datos de contacto</div>
                <div class="card-body">
                    <p class="mb-1"><strong>Nombre:</strong> {{ $reserva->contacto_nombre }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $reserva->contacto_email }}</p>
                    <p class="mb-0"><strong>Teléfono:</strong> {{ $reserva->contacto_telefono }}</p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">Información del viaje</div>
                <div class="card-body">
                    <p class="mb-1">
                        <strong>Fecha de inicio:</strong>
                        {{ \Carbon\Carbon::parse($reserva->fecha_inicio)->format('d/m/Y') }}
                    </p>
                    <p class="mb-1">
                        <strong>Fecha de fin:</strong>
                        {{ \Carbon\Carbon::parse($reserva->fecha_fin)->format('d/m/Y') }}
                    </p>
                    <p class="mb-0">
                        <strong>Cantidad de pasajeros:</strong>
                        {{ count($reserva->pasajeros_json ?? []) }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Resumen de pago</div>
                <div class="card-body">
                    <p class="mb-1"><strong>Subtotal:</strong> S/. {{ number_format($reserva->subtotal, 2) }}</p>
                    <p class="mb-1"><strong>Descuento:</strong> S/. {{ number_format($reserva->descuento, 2) }}</p>
                    <p class="mb-0"><strong>Total:</strong> S/. {{ number_format($reserva->total, 2) }}</p>
                </div>
            </div>

            
        </div>
    </div>

    <a href="{{ route('mis_reservas.index') }}" class="btn btn-outline-secondary mt-3">
        Volver a mis reservas
    </a>
@endsection
