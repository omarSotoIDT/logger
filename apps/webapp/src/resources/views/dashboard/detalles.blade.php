@extends('layout.app')

@section('titulo', 'Detalle Proyecto')

@section('contenido')
<main class="contenedor">
    <div">
        <a href="{{ route('proyectos.dashboard') }}" class="volver">
            <img
                src="{{ asset('assets/icons/arrow-back.svg') }}"
                alt="Icono de tipos"
            > 
            Volver al dashboard
        </a>
    </div>

    <section class="card">
        <div class="detalles-encabezado">
            <div>
                <h1 class="titulo pagina-titulo">{{ $proyecto->nombre }}</h1>
                <p>Información general del proyecto</p>
            </div>

            <span class="badge-status {{ $proyecto->status === 'ACTIVO' ? 'badge-activo' : 'badge-inactivo' }}">
                {{ strtolower($proyecto->status) }}
            </span>
        </div>

        <div class="detalles-contenido">
            <div class="detalles-campo">
                <p>Tipo de proyecto</p>
                <p>{{$proyecto->tipo_nombre}}</p>
            </div>
            <div class="detalles-campo">
                <p>Timezone</p>
                <p>{{ $proyecto->timezone}}</p>
            </div>
            <div class="detalles-campo">
                <p>Endpoint</p>
                <span>{{ $proyecto->url_endpoint }}</span>
            </div>
        </div>
        
        <a
            href="{{ route('proyectos.analisis', ['id' => $proyecto->proyecto_id]) }}"
            class="btn-analisis"
            >
            <img src="{{ asset('assets/icons/grafica.svg') }}" alt="Icono error">
            Dashboard Analítico
        </a>
    </section>
        
    <section class="card card-dias-disponibles">
        <div class="detalles-encabezado">
            <div>
                <h1 class="titulo pagina-titulo">Días disponibles</h1>
                <p>Consulta y sincroniza logs por día</p>
            </div>
        </div>

        <div id="diasApp" class="dias-disponibles">
    <div
  class="dia-row"
  v-for="log in diasDisponibles"
  :key="`${log.log_fecha}-${log.nombre}`"
>

        <div class="dia-left">
            <div class="dia-icono">
                <img src="{{ asset('assets/icons/calendario.svg') }}" alt="Calendario">
            </div>

            <div class="dia-texto">
                <p class="dia-fecha">
                    @{{ formatearFechaLarga(log.log_fecha) }}
                    <span
                        v-if="log.disponible_remoto === false"
                        class="dia-no-disponible"
                        title="Este archivo ya no existe en el remoto"
                    >
                        Sincronización no disponible
                    </span>
                    <span
                        v-else-if="log.ultima_sincronizacion"
                        class="dia-sincronizado"
                        :title="`Sincronizado el ${formatearFechaLarga(log.ultima_sincronizacion)}`"
                    >
                        Sincronizado
                    </span>
                    <span v-else class="dia-pendiente">Pendiente</span>
                </p>

                <p class="dia-sub">
                    @{{ log.nombre }}
                </p>
            </div>
        </div>

        <div class="dia-right">
            <a
            :href="`{{ route('proyectos.detalles', ['id' => '__LOG__']) }}`.replace('__LOG__', log.log_id)"
            class="btn-pill-outline btn-pill"
            >
            <img src="{{ asset('assets/icons/doc.svg') }}" class="btn-pill-ico" alt="icono documento">
            Ver Logs
            </a>



            <form
                method="POST"
                action="{{ route('proyectos.sync') }}"
                style="display:inline;"
            >
                @csrf
                <input type="hidden" name="log_id" :value="log.log_id">

                <button type="submit" class="btn-pill btn-pill-solid">
                    <img
                        src="{{ asset('assets/icons/sync.svg') }}"
                        class="btn-pill-ico"
                        alt="icono de sincronizacion"
                    >
                    Sincronizar
                </button>
            </form>
        </div>
    </div>

    <p v-if="diasDisponibles.length === 0" class="dias-vacio">
        No hay días disponibles todavía.
    </p>
</div>
 

    </section>


</main>
@endsection

@section('scripts')
<script>
  const { createApp } = Vue;

  createApp({
    data() {
        return {
        diasDisponibles: @json($diasDisponibles ?? []),
        formatearFechaLarga: window.formatearFechaLarga
        }
    }
    }).mount('#diasApp');

</script>

@endsection
