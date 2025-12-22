@extends('layout.app')

@section('titulo', 'Detalle Proyecto')

@section('contenido')
<main class="contenedor">
    <div style="margin-top: 16px;">
        <a href="{{ route('proyectos.dashboard') }}" class="volver">
            <img
                src="{{ asset('assets/icons/arrow-back.svg') }}"
                alt="Icono de tipos"
                class=""
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
                {{ $proyecto->status }}
            </span>
        </div>

        <div class="detalles-contenido">
            <div class="detalles-campo">
                <p>Tipo de proyecto</p>
                <p>{{$proyecto->tipoNombre}}</p>
            </div>
            <div class="detalles-campo">
                <p>Timezone</p>
                <p>{{ $proyecto->timezone}}</p>
            </div>
            <div class="detalles-campo">
                <p>Endpoint</p>
                <span>{{ $proyecto->url }}</span>
            </div>
        </div>
        
        <a
            href="{{ route('proyectos.obtener', ['id' => $proyecto->proyectoId]) }}"
            class="btn-analisis"
            >
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

        <div class="dias-disponibles">
            <div class="dia-disponible">
                <div class="dia-disponible-campo">
                    <img
                        src="{{ asset('assets/icons/calendario.svg') }}"
                        alt="Icono de calendario"
                    >
                    
                </div>
            </div>
        </div>
    </section>


</main>
@endsection

@section('scripts')
<script>

</script>
@endsection