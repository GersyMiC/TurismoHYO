@extends('layouts.base')

@section('titulo', 'Gestión de Reservas')

@section('contenido')
    <h2 class="mb-4">Gestión de Reservas</h2>

    <!-- Formulario de filtros -->
    <form method="GET" action="{{ route('admin.reservas') }}" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <input type="text" name="cliente" class="form-control" placeholder="Buscar cliente" value="{{ request('cliente') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
            </div>
            <div class="col-md-3">
                <select name="destino" class="form-select">
                    <option value="">Seleccionar destino</option>
                    @foreach($destinos as $destino)
                        <option value="{{ $destino }}" {{ request('destino') == $destino ? 'selected' : '' }}>
                            {{ $destino }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12 mt-2">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </div>
    </form>

    <!-- Tabla de reservas -->
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Cliente</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Destino</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservas as $reserva)
                    <tr>
                        <td>{{ $reserva->codigo }}</td>
                        <td>{{ $reserva->contacto_nombre }}</td>
                        <td>{{ \Carbon\Carbon::parse($reserva->fecha_inicio)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($reserva->fecha_fin)->format('d/m/Y') }}</td>
                        <td>
                            @foreach($reserva->items as $item)
                                {{ $item->paquete->destino->nombre }}<br>
                            @endforeach
                        </td>
                        <td>
                            <span class="badge bg-{{ $reserva->estado == 'pagado' ? 'success' : ($reserva->estado == 'cancelado' ? 'danger' : 'secondary') }}">
                                {{ ucfirst($reserva->estado) }}
                            </span>
                        </td>
                        <td>
                            <!-- Formulario para cambiar el estado de la reserva -->
                            <form method="POST" action="{{ route('admin.reservas.update', $reserva->id) }}">
                                @csrf
                                @method('PUT')
                                <select name="estado" class="form-select" onchange="this.form.submit()">
                                    <option value="pendiente" {{ $reserva->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="pagado" {{ $reserva->estado == 'pagado' ? 'selected' : '' }}>Pagado</option>
                                    <option value="cancelado" {{ $reserva->estado == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                    <option value="reembolsado" {{ $reserva->estado == 'reembolsado' ? 'selected' : '' }}>Reembolsado</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
