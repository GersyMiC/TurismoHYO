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

    {{-- Fila: ingresos totales + tabla reservas por estado --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Ingresos totales</h5>
                    <p class="display-6 mb-0">S/. {{ number_format($ingresosTotales, 2) }}</p>
                    <small class="text-muted">Suma de reservas con estado "confirmada" (ajustable).</small>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Reservas por estado</h5>

                    @if($reservasPorEstado->isEmpty())
                        <p class="text-muted mb-0">No hay reservas registradas.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Estado</th>
                                        <th>Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservasPorEstado as $fila)
                                        <tr>
                                            <td>{{ ucfirst($fila->estado) }}</td>
                                            <td>{{ $fila->cantidad }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Fila con gráficas --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Gráfico: Reservas por estado</h5>
                    <canvas id="chartReservasEstado" height="160"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Gráfico: Reservas / Ingresos por mes</h5>
                    <canvas id="chartReservasMes" height="160"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Top 5 clientes --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Top 5 clientes con más reservas</h5>

            @if($topClientes->isEmpty())
                <p class="text-muted mb-0">Aún no hay suficientes datos de reservas.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Email</th>
                                <th>Reservas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topClientes as $fila)
                                <tr>
                                    <td>{{ optional($fila->usuario)->nombre_completo ?? 'Sin nombre' }}</td>
                                    <td>{{ optional($fila->usuario)->email ?? '-' }}</td>
                                    <td>{{ $fila->reservas }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Top destinos --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Top destinos / paquetes más reservados</h5>

            @if($topDestinos->isEmpty())
                <p class="text-muted mb-0">Aún no hay suficientes datos para mostrar destinos.</p>
            @else
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
            @endif
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
    </script>
@endsection

