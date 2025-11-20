@extends('layouts.base')

@section('titulo', 'Mis reservas')

@section('contenido')
    <h2 class="mb-3">Mis reservas</h2>

    @if($reservas->isEmpty())
        <div class="alert alert-info">
            Aún no tienes reservas registradas.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Fecha inicio</th>
                        <th>Fecha fin</th>
                        <th>Pasajeros</th>
                        <th>Estado</th>
                        <th>Total (S/.)</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservas as $reserva)
                        <tr>
                            <td>{{ $reserva->codigo }}</td>
                            <td>
                                {{ $reserva->fecha_inicio ? $reserva->fecha_inicio->format('d/m/Y') : '-' }}
                            </td>
                            <td>
                                {{ $reserva->fecha_fin ? $reserva->fecha_fin->format('d/m/Y') : '-' }}
                            </td>
                            <td>{{ $reserva->cantidad_pasajeros }}</td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ ucfirst($reserva->estado) }}
                                </span>
                            </td>
                            <td>{{ number_format($reserva->total, 2) }}</td>
                            <td>
                                <a href="{{ route('mis_reservas.show', $reserva->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    Ver detalle
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
