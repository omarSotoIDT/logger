@extends('layout.app')


@section('titulo', 'Dashboard Logger')

@section('contenido')
    
    <main class="contenedor">
        <section class="pagina-encabezado">
            <div class="pagina-encabezado-texto">
                <h1 class="titulo pagina-titulo">Mis Proyectos</h1>
                <p class="pagina-descripcion">Proyectos asignados: {{count($proyectos)}}</p>
            </div>
        </section>

        <div class="layout-dashboard">
            @foreach ($proyectos as $proyecto)
            <div class="card card-dashboard">
                <div class="card-dashboard-cabecera">
                    <img
                        src="{{ asset('assets/icons/proyectos.svg') }}"
                        alt="Icono de proyectos"
                    >

                    <span class="badge-status {{ $proyecto->status === 'ACTIVO' ? 'badge-activo' : 'badge-inactivo' }}">
                        {{ $proyecto->status }}
                    </span>
                </div>

                <section class="card-dashboard-contenido">
                    <h2>{{$proyecto->nombre}}</h2>

                    <div>
                        <img
                                src="{{ asset('assets/icons/tipos.svg') }}"
                                alt="Icono de tipos"
                                class=""
                            >   
                        <p>{{$proyecto->tipo_nombre}}</p>
                    </div>
                </section>

                <a
                href="{{ route('proyectos.obtener', ['id' => $proyecto->proyecto_id]) }}"
                class="btn-principal btn-fix"
                >
                Ver Detalle
                </a>
            </div>
            @endforeach
        </div>
    </main>



@endsection