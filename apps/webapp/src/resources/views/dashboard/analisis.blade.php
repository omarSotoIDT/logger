@extends('layout.app')

@section('titulo', 'Dashboard Analitico')

@section('contenido')
    <main class="contenedor analisis-page">
        <div>
            <a href="{{ route('proyectos.obtener', ['id' => $proyecto->proyecto_id]) }}" class="volver volver-logs">
                <img src="{{ asset('assets/icons/arrow-back.svg') }}" alt="Volver">
                Volver al detalle del proyecto
            </a>
        </div>

        <section class="analisis-header">
            <div>
                <h1 class="titulo pagina-titulo">Dashboard Analitico</h1>
                <p class="analisis-subtitulo">{{ $proyecto->nombre }}</p>
            </div>
        </section>

        <section class="totales">
            <div class="card card-conteo">
                <p>Total Logs</p>
                <p>{{ $conteoDetalles ?? 0 }}</p>
            </div>
            <div class="card card-conteo">
                <p>Errores</p>
                <p>{{ $conteoError ?? 0 }}</p>
            </div>
            <div class="card card-conteo">
                <p>Warnings</p>
                <p>{{ $conteoWarning ?? 0 }}</p>
            </div>
            <div class="card card-conteo">
                <p>Debug</p>
                <p>{{ $conteoDebug ?? 0 }}</p>
            </div>
        </section>

        <section class="rango">
            <form method="GET" action="{{ route('proyectos.analisis', ['id' => $proyecto->proyecto_id]) }}">
                    <label for="rango">Rango</label>
                    <select id="rango" name="rango" onchange="this.form.submit()">
                        <option value="24h" @selected(($rangoSeleccionado ?? 'all') === '24h')>Ultimas 24 horas</option>
                        <option value="1w" @selected(($rangoSeleccionado ?? 'all') === '1w')>1 semana</option>
                        <option value="1m" @selected(($rangoSeleccionado ?? 'all') === '1m')>1 mes</option>
                        <option value="3m" @selected(($rangoSeleccionado ?? 'all') === '3m')>3 meses</option>
                        <option value="1y" @selected(($rangoSeleccionado ?? 'all') === '1y')>1 año</option>
                        <option value="all" @selected(($rangoSeleccionado ?? 'all') === 'all')>Todos</option>
                    </select>
                </form>
        </section>

        <section class="estadisticas">
            <div class="card card-chart">
                <canvas id="myChart"></canvas>
            </div>

            <div class="card card-chart">
                <canvas id="chartNiveles"></canvas>
            </div>

            <div class="card">
                <h2>Códigos internos más frecuentes</h2>

                <div class="card-codigo">
                    @forelse ($topCodigosInternos as $codigo)
                        <div class="codigo-item">
                            <div class="codigo-meta">
                                <span class="codigo">{{ $codigo->codigo_interno_mensaje ?? 'Sin mensaje' }}</span>
                            </div>
                            <span class="total">{{ $codigo->total }} ocurrencias</span>
                        </div>
                    @empty
                        <p class="analisis-vacio">No hay códigos internos disponibles</p>
                    @endforelse
                </div>
            </div>

            <div class="card">
                <h2>Archivos con más errores</h2>

                <div class="card-archivos">
                    @forelse ($topArchivosErrores as $archivo)
                        <div class="archivo-item">
                            <div>
                                <span class="archivo">{{ basename($archivo->archivo) }}</span>
                            </div>
                            <span class="total">{{ $archivo->total }} errores</span>
                        </div>
                    @empty
                        <p class="analisis-vacio">No hay archivos con errores.</p>
                    @endforelse
                </div>
            </div>
        </section>
    </main>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <script>
        {
            const labels = @json($erroresPorHoraLabels ?? []);
            const data = @json($erroresPorHoraData ?? []);

            const canvasLine = document.getElementById('myChart');

            if (canvasLine && labels.length) {
                new Chart(canvasLine, {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Errores por hora',
                            data,
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Errores por hora',
                                align: 'start',
                                padding: {
                                    top: 10,
                                    bottom: 16
                                },
                                font: {
                                    size: 14,
                                    weight: '600'
                                }
                            },
                            legend: {
                                position: 'bottom'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            }
        }

        {
            const nivelesLabels = @json($nivelesLabels ?? []);
            const nivelesData = @json($nivelesData ?? []);

            const nivelPalette = {
                ERROR: '#dc2626',
                WARNING: '#f59e0b',
                DEBUG: '#2563eb',
                INFO: '#0f766e',
                OTROS: '#6b7280',
            };

            const nivelesColors = nivelesLabels.map(l => nivelPalette[l] || '#9ca3af');

            const canvasPie = document.getElementById('chartNiveles');

            if (canvasPie && nivelesLabels.length) {
                new Chart(canvasPie, {
                    type: 'pie',
                    data: {
                        labels: nivelesLabels,
                        datasets: [{
                            data: nivelesData,
                            backgroundColor: nivelesColors,
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Distribución por Nivel',
                                position: 'top',
                                align: 'start',
                                padding: {
                                    top: 10,
                                    bottom: 16
                                },
                                font: {
                                    size: 14,
                                    weight: '600'
                                }
                            },
                            legend: {
                                display: false
                            },
                            datalabels: {
                                anchor: 'end',
                                align: 'end',
                                offset: 16,
                                color: (ctx) => ctx.dataset.backgroundColor[ctx.dataIndex],
                                font: {
                                    weight: '600',
                                    size: 12
                                },
                                formatter: (value, ctx) => {
                                    const label = ctx.chart.data.labels[ctx.dataIndex] ?? '';
                                    return `${label}: ${value}`;
                                },
                                clamp: true
                            }
                        }
                    },

                    plugins: [ChartDataLabels]
                });
            }
        }
    </script>
@endsection
