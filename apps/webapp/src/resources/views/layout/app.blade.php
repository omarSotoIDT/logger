<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Título dinámico --}}
    <title>@yield('titulo', 'Logger')</title>

    {{-- CSS global --}}
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    {{-- CDN Vue--}}
    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
    <script src="https://unpkg.com/dayjs/dayjs.min.js"></script>
    <script src="https://unpkg.com/dayjs/plugin/localizedFormat.js"></script>
    <script src="https://unpkg.com/dayjs/locale/es.js"></script>
    <script>
        dayjs.extend(dayjs_plugin_localizedFormat);
        dayjs.locale('es');
    </script>
    <script src="{{ asset('js/bootstrap.js') }}"></script>

    {{-- Head adicional por vista --}}
    @yield('head')
</head>
<body>

    {{-- Top bar --}}

    <header class="barra-superior">
        <div class="barra-superior-interior">

            {{-- Marca --}}
            <a href="{{ route('proyectos.dashboard') }}" class="barra-superior-marca">
                <span class="barra-superior-logo" aria-hidden="true">
                    <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="40"
                    height="40"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="#fff"
                    stroke-width="1"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    >
                    <path d="M15 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                    <path d="M21 12h-13l3 -3" />
                    <path d="M11 15l-3 -3" />
                    </svg>
                </span>
                <span class="barra-superior-marca-texto">Logger</span>
            </a>

            {{-- Navegación --}}
            <nav class="barra-superior-navegacion">
                <a href="{{ route('proyectos.dashboard') }}"
                class="barra-superior-enlace {{ request()->routeIs('proyectos.dashboard', 'proyectos.obtener') ? 'esta-activo' : '' }}">
                    <span class="barra-superior-icono" aria-hidden="true">
                        <img
                            src="{{ asset('assets/icons/dashboard.svg') }}"
                            alt="Icono del dashboard"
                            class=""
                        >   
                    </span>
                    Dashboard
                </a>

                <a href="{{ route('proyectos.index') }}"
                class="barra-superior-enlace {{ request()->routeIs('proyectos.index') ? 'esta-activo' : '' }}">
                    <span class="barra-superior-icono" aria-hidden="true">
                        <img
                            src="{{ asset('assets/icons/proyectos.svg') }}"
                            alt="Icono de proyectos"
                            class=""
                        >   
                    </span>
                    Proyectos
                </a>

                <a href="{{ route('tipos.index') }}"
                class="barra-superior-enlace {{ request()->routeIs('tipos.*') ? 'esta-activo' : '' }}">
                    <span class="barra-superior-icono" aria-hidden="true">
                        <img
                            src="{{ asset('assets/icons/tipos.svg') }}"
                            alt="Icono de tipos"
                            class=""
                        >   
                    </span>
                    Tipos
                </a>

                <a href="{{ route('usuarios.index') }}"
                class="barra-superior-enlace {{ request()->routeIs('usuarios.*') ? 'esta-activo' : '' }}">
                    <span class="barra-superior-icono" aria-hidden="true">
                        <img
                            src="{{ asset('assets/icons/usuarios.svg') }}"
                            alt="Icono de usuarios"
                            class=""
                        >
                    </span>
                    Usuarios
                </a>
            </nav>

            {{-- Usuario --}}
            <div class="barra-superior-usuario">
                <div class="barra-superior-usuario-texto">
                    <div class="barra-superior-usuario-nombre">{{ auth()->user()->usuario ?? 'admin' }}</div>
                    <div class="barra-superior-usuario-rol">Administrador</div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="barra-superior-salir" type="submit" title="Salir" aria-label="Salir">
                        <img
                            src="{{ asset('assets/icons/logout.svg') }}"
                            alt="Icono de usuarios"
                            class="barra-superior-logout"
                        >
                    </button>
                </form>
            </div>

        </div>
    </header>



    {{-- Contenido principal --}}
    <main class="page-content">
        @yield('contenido')
    </main>

    {{-- Toast / Flash --}}
    <x-flash />

    {{-- Scripts --}}
    <script src="{{ asset('js/flash.js') }}"></script>

    {{-- Scripts por vista --}}
    @yield('scripts')

</body>
</html>
