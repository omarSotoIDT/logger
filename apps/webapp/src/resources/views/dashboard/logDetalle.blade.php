@extends('layout.app')

@section('titulo', 'Logs del día')

@section('contenido')
<main class="contenedor log-page">
    <div>
        <a href="{{ route('proyectos.obtener', ['id' => $proyecto->proyecto_id]) }}" class="volver volver-logs">
            <img
                src="{{ asset('assets/icons/arrow-back.svg') }}"
                alt="Volver"
            >
            Volver al detalle del proyecto
        </a>
    </div>

    <section class="card">
        <div>
            <h1 class="titulo pagina-titulo">Logs del Día</h1>
            <p class="log-resumen-sub" id="logFechaApp">
                {{$proyecto->nombre}} - @{{ formatearFechaLarga(fechaRaw) || 'Fecha no disponible' }}
            </p>
            <p class="log-resumen-total">Total de logs: {{ count($logs) }}</p>
        </div>
    </section>

    <section class="card">
        <h2 class="log-filtros-titulo">Filtros</h2>
        <form class="log-filtros-grid" action="" method="GET">
            <label class="log-filtro">
                <span>Nivel</span>
                <select name="nivel">
                    <option value="" {{ request('nivel') ? '' : 'selected' }}>Todos</option>
                    <option value="ERROR" {{ request('nivel') === 'ERROR' ? 'selected' : '' }}>Error</option>
                    <option value="WARNING" {{ request('nivel') === 'WARNING' ? 'selected' : '' }}>Warning</option>
                    <option value="DEBUG" {{ request('nivel') === 'DEBUG' ? 'selected' : '' }}>Debug</option>
                </select>
            </label>

            <label class="log-filtro">
                <span>Texto</span>
                <input type="text" name="search" placeholder="Buscar en mensaje..." value="{{ request('search') }}">
            </label>

            <label class="log-filtro">
                <span>Archivo</span>
                <input type="text" name="archivo" placeholder="Buscar archivo..." value="{{ request('archivo') }}">
            </label>

            <label class="log-filtro">
                <span>Código Interno</span>
                <input type="text" name="codigoInterno" placeholder="Código..." value="{{ request('codigoInterno') }}">
            </label>

            <div class="log-filtro log-filtro-boton">
                <button type="submit" class="btn-terciario">Aplicar filtros</button>
            </div>

            <div class="log-filtro log-filtro-boton">
                <a href="{{ url()->current() }}" class="btn-terciario">Limpiar filtros</a>
            </div>
        </form>
    </section>

    <section class="log-lista">
        @forelse($logs as $log)
            <article @class([
                'card',
                'log-nivel-error' => ($log->nivel ?? '') === 'ERROR',
                'log-nivel-warning' => ($log->nivel ?? '') === 'WARNING',
                'log-nivel-debug' => ($log->nivel ?? '') === 'DEBUG',
                'log-nivel-info' => !in_array(($log->nivel ?? ''), ['ERROR', 'WARNING', 'DEBUG'], true),
            ])>
                <header class="log-card-cabecera">
                    <div class="log-card-meta">
                        <span class="log-icono">{{ ($log->nivel ?? '') === 'WARNING' ? '!' : 'x' }}</span>
                        <span class="log-pill log-pill-nivel">{{ $log->nivel ?? 'INFO' }}</span>
                        <span class="log-pill log-pill-codigo">{{ $log->codigo_excepcion ?? $log->codigo_interno ?? 'SIN_CODIGO' }}</span>
                    </div>
                    <span class="log-hora">{{ $log->fecha_hora_log ?? '--:--' }}</span>
                </header>

                <p class="log-mensaje">{{ $log->mensaje ?? 'Mensaje no disponible' }}</p>
                <p class="log-path">{{ $log->archivo ?? 'Archivo no disponible' }}{{ !empty($log->linea) ? ':' . $log->linea : '' }}</p>

                <div class="log-acciones">
                    <details class="log-detalles">
                        <summary>
                            <span class="log-ver">Ver detalles</span>
                            <span class="log-ocultar">Ocultar detalles</span>
                        </summary>
                        <div class="log-detalles-cuerpo">
                            <h3 class="log-stack-titulo">Stacktrace</h3>
                            <pre class="log-stack"><code>{{ $log->stacktrace ?? 'Sin stacktrace disponible.' }}</code></pre>
                        </div>
                    </details>

                    <button type="button" class="log-raw">Ver raw</button>
                </div>
            </article>
        @empty
            <div class="card log-vacio">
                No hay logs disponibles para este día.
            </div>
        @endforelse
    </section>
</main>
@endsection

@section('scripts')
<script>
  const { createApp } = Vue;

  createApp({
    data() {
      return {
        fechaRaw: @json(optional($logs->first())->fecha_hora_log),
        formatearFechaLarga: window.formatearFechaLarga,
      };
    },
  }).mount('#logFechaApp');
</script>
@endsection
