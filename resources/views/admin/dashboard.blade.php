@extends('layouts.base')

@section('titulo', 'Panel de Administración')

@section('contenido')
    <h2 class="mb-4">Panel de Administración – KPIs</h2>

    {{-- Cards de resumen rápido --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-subtitle text-muted">Reservas totales</h6>
                    <h3 class="mt-2 mb-0">{{ $totalReservas }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-subtitle text-muted">Reservas de hoy</h6>
                    <h3 class="mt-2 mb-0">{{ $reservasHoy }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-subtitle text-muted">Reservas del mes</h6>
                    <h3 class="mt-2 mb-0">{{ $reservasMes }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-subtitle text-muted">Ingresos del mes (S/.)</h6>
                    <h3 class="mt-2 mb-0">
                        {{ number_format($ingresosMes, 2) }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Gráfico: Reservas por estado --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Gráfico: Reservas por estado</h5>
                    <canvas id="chartReservasEstado" height="160"></canvas>
                </div>
            </div>
        </div>

        {{-- Gráfico: Reservas e ingresos por mes --}}
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Gráfico: Reservas / Ingresos por mes</h5>
                    <canvas id="chartReservasMes" height="160"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Gráfico: Reservas e ingresos por año --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Gráfico: Reservas e ingresos por año</h5>
                    <form method="GET" action="{{ route('admin.dashboard') }}">
                        <select name="anio" class="form-select" onchange="this.form.submit()">
                            @foreach(range(2023, Carbon\Carbon::now()->year) as $year)
                                <option value="{{ $year }}" {{ $year == request('anio', Carbon\Carbon::now()->year) ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                    <canvas id="chartReservasAnio" height="160"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Top destinos --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Top destinos más reservados</h5>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Destino</th>
                            <th>Paquete</th>
                            <th>Reservas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topDestinos as $fila)
                            @php
                                $paquete = $fila->paquete;
                                $destino = optional($paquete)->destino;
                            @endphp
                            <tr>
                                <td>{{ $destino->nombre ?? 'Destino no definido' }}</td>
                                <td>{{ $paquete->nombre ?? 'Paquete sin nombre' }}</td>
                                <td>{{ $fila->reservas }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // ---------- Gráfico: Reservas por estado ----------
        const reservasEstadoLabels = @json($reservasPorEstado->pluck('estado'));
        const reservasEstadoData   = @json($reservasPorEstado->pluck('cantidad'));

        const ctxEstado = document.getElementById('chartReservasEstado').getContext('2d');
        new Chart(ctxEstado, {
            type: 'bar',
            data: {
                labels: reservasEstadoLabels,
                datasets: [{
                    label: 'Reservas',
                    data: reservasEstadoData,
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // ---------- Gráfico: Reservas / ingresos por mes (últimos 6) ----------
        const reservasMesLabels = @json($labelsMes);
        const reservasMesData   = @json($dataReservasMes);
        const ingresosMesData   = @json($dataIngresosMes);

        const ctxMes = document.getElementById('chartReservasMes').getContext('2d');
        new Chart(ctxMes, {
            type: 'bar',
            data: {
                labels: reservasMesLabels,
                datasets: [
                    {
                        label: 'Reservas',
                        data: reservasMesData,
                        borderWidth: 1,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Ingresos (S/.)',
                        data: ingresosMesData,
                        type: 'line',
                        borderWidth: 2,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        position: 'left',
                        title: { display: true, text: 'Reservas' }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        title: { display: true, text: 'Ingresos (S/.)' },
                        grid: { drawOnChartArea: false }
                    }
                }
            }
        });

        // ---------- Gráfico: Reservas e ingresos por año ----------
        const reservasAnioLabels = @json($labelsAnio);
        const reservasAnioData   = @json($dataReservasAnio);
        const ingresosAnioData   = @json($dataIngresosAnio);

        const ctxAnio = document.getElementById('chartReservasAnio').getContext('2d');
        new Chart(ctxAnio, {
            type: 'bar',
            data: {
                labels: reservasAnioLabels,
                datasets: [
                    {
                        label: 'Reservas',
                        data: reservasAnioData,
                        borderWidth: 1,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Ingresos (S/.)',
                        data: ingresosAnioData,
                        type: 'line',
                        borderWidth: 2,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        position: 'left',
                        title: { display: true, text: 'Reservas' }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        title: { display: true, text: 'Ingresos (S/.)' },
                        grid: { drawOnChartArea: false }
                    }
                }
            }
        });
    </script>
@endsection

