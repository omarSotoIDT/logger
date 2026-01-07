@extends('layout.app')

@section('titulo', 'Proyectos logger')

@section('contenido')

<div id="app-proyectos">
    {{-- =========================
       MODAL CREAR PROYECTO
    ========================= --}}
    <transition name="transicion-modal">
        <div
            v-if="modales.crear"
            class="modal-overlay"
            id="modal-proyecto-crear"
            @click.self="cerrarModal('crear')"
        >
            <div class="modal">
                <header class="modal-cabecera">
                    <h2 class="modal-titulo">Nuevo Proyecto</h2>

                    <button
                        type="button"
                        class="modal-cerrar"
                        aria-label="Cerrar"
                        @click.prevent="cerrarModal('crear')"
                    >✕</button>
                </header>

                <form class="modal-cuerpo" method="POST" action="{{ route('proyectos.crear') }}">
                    @csrf

                    <div class="formulario-campo">
                        <label for="nombre">Nombre del proyecto</label>
                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            value="{{ old('nombre') }}"
                            placeholder="Ej: Logger WebApp"
                            required
                            ref="inputNombreCrear"
                        >
                    </div>

                    <div class="formulario-campo">
                        <label for="tipoProyectoId">Tipo de proyecto</label>
                        <select
                            id="tipoProyectoId"
                            name="tipoProyectoId"
                            required
                        >
                            <option value="" disabled {{ old('tipoProyectoId') ? '' : 'selected' }}>Selecciona un tipo</option>
                            @foreach ($tipos ?? [] as $tipo)
                                <option
                                    value="{{ $tipo->tipo_proyecto_id ?? $tipo->tipoProyectoId ?? $tipo->id }}"
                                    {{ (string)old('tipoProyectoId') === (string)($tipo->tipo_proyecto_id ?? $tipo->tipoProyectoId ?? $tipo->id) ? 'selected' : '' }}
                                >
                                    {{ $tipo->nombre ?? '—' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="formulario-campo">
                        <label for="urlEndpoint">URL Endpoint</label>
                        <input
                            type="text"
                            id="urlEndpoint"
                            name="urlEndpoint"
                            value="{{ old('urlEndpoint') }}"
                            placeholder="Ej: https://miapp.com/api/logs"
                            required
                        >
                    </div>

                    <div class="formulario-campo">
                        <label for="apiKey">API Key</label>
                        <input
                            type="text"
                            id="apiKey"
                            name="apiKey"
                            value="{{ old('apiKey') }}"
                            placeholder="Ej: sk_live_********"
                            required
                        >
                    </div>

                    <div class="formulario-campo">
                        <label for="timezone">Timezone</label>
                        <select id="timezone" name="timezone" required>
                            <option value="" disabled {{ old('timezone') ? '' : 'selected' }}>Selecciona una zona horaria</option>
                            @foreach ($timezones ?? [] as $tz)
                                <option value="{{ $tz }}" {{ old('timezone') === $tz ? 'selected' : '' }}>
                                    {{ $tz }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="formulario-campo">
                        <label for="status">Estado</label>
                        <select id="status" name="status" required>
                            <option value="ACTIVO" {{ old('status', 'ACTIVO') === 'ACTIVO' ? 'selected' : '' }}>ACTIVO</option>
                            <option value="INACTIVO" {{ old('status') === 'INACTIVO' ? 'selected' : '' }}>INACTIVO</option>
                        </select>
                    </div>

                    <footer class="modal-pie">
                        <button type="submit" class="btn-principal">Agregar</button>
                        <button type="button" class="btn-terciario" @click.prevent="cerrarModal('crear')">Cancelar</button>
                    </footer>
                </form>
            </div>
        </div>
    </transition>


    {{-- =========================
       MODAL EDITAR PROYECTO
    ========================= --}}
    <transition name="transicion-modal">
        <div
            v-if="modales.editar"
            class="modal-overlay"
            id="modal-proyecto-editar"
            @click.self="cerrarModal('editar')"
        >
            <div class="modal">
                <header class="modal-cabecera">
                    <h2 class="modal-titulo">Editar Proyecto</h2>

                    <button
                        type="button"
                        class="modal-cerrar"
                        aria-label="Cerrar"
                        @click.prevent="cerrarModal('editar')"
                    >✕</button>
                </header>

                <form class="modal-cuerpo" method="POST" :action="formEditarAction">
                    @csrf
                    @method('PATCH')

                    <div class="formulario-campo">
                        <label for="nombre_editar">Nombre del proyecto</label>
                        <input
                            type="text"
                            id="nombre_editar"
                            name="nombre"
                            v-model="editar.nombre"
                            placeholder="Ej: Logger WebApp"
                            required
                            ref="inputNombreEditar"
                        >
                    </div>

                    <div class="formulario-campo">
                        <label for="tipoProyectoId_editar">Tipo de proyecto</label>
                        <select
                            id="tipoProyectoId_editar"
                            name="tipoProyectoId"
                            v-model="editar.tipoId"
                            required
                        >
                            <option value="" disabled>Selecciona un tipo</option>
                            <option
                                v-for="t in tipos"
                                :key="t.id"
                                :value="t.id"
                            >
                                @{{ t.nombre }}
                            </option>
                        </select>
                    </div>

                    <div class="formulario-campo">
                        <label for="urlEndpoint_editar">URL Endpoint</label>
                        <input
                            type="text"
                            id="urlEndpoint_editar"
                            name="urlEndpoint"
                            v-model="editar.url"
                            placeholder="Ej: https://miapp.com/api/logs"
                            required
                        >
                    </div>

                    <div class="formulario-campo">
                        <label for="apiKey_editar">API Key</label>
                        <input
                            type="text"
                            id="apiKey_editar"
                            name="apiKey"
                            v-model="editar.api"
                            placeholder="Ej: sk_live_********"
                            required
                        >
                    </div>

                    <div class="formulario-campo">
                        <label for="timezone_editar">Timezone</label>
                        <select
                            id="timezone_editar"
                            name="timezone"
                            v-model="editar.timezone"
                            required
                        >
                            <option value="" disabled>Selecciona una zona horaria</option>
                            <option v-for="tz in timezones" :key="tz" :value="tz">
                                @{{ tz }}
                            </option>
                        </select>
                    </div>

                    <div class="formulario-campo">
                        <label for="status_editar">Estado</label>
                        <select
                            id="status_editar"
                            name="status"
                            v-model="editar.status"
                            required
                        >
                            <option value="ACTIVO">ACTIVO</option>
                            <option value="INACTIVO">INACTIVO</option>
                        </select>
                    </div>

                    <footer class="modal-pie">
                        <button type="submit" class="btn-principal">Guardar cambios</button>
                        <button type="button" class="btn-terciario" @click.prevent="cerrarModal('editar')">Cancelar</button>
                    </footer>
                </form>
            </div>
        </div>
    </transition>


    {{-- =========================
       MODAL ELIMINAR PROYECTO
    ========================= --}}
    <transition name="transicion-modal">
        <div
            v-if="modales.eliminar"
            class="modal-overlay"
            id="modal-proyecto-eliminar"
            @click.self="cerrarModal('eliminar')"
        >
            <div class="modal">
                <header class="modal-cabecera">
                    <h2 class="modal-titulo">Confirmar eliminación</h2>

                    <button
                        type="button"
                        class="modal-cerrar"
                        aria-label="Cerrar"
                        @click.prevent="cerrarModal('eliminar')"
                    >✕</button>
                </header>

                <div class="modal-cuerpo">
                    <p class="modal-texto">
                        ¿Seguro que deseas eliminar el proyecto
                        <strong>@{{ eliminar.nombre || '—' }}</strong>?
                    </p>

                    <form method="POST" :action="formEliminarAction">
                        @csrf
                        @method('DELETE')

                        <footer class="modal-pie">
                            <button type="button" class="btn-terciario" @click.prevent="cerrarModal('eliminar')">Cancelar</button>
                            <button type="submit" class="btn-peligro">Sí, eliminar</button>
                        </footer>
                    </form>
                </div>
            </div>
        </div>
    </transition>


    {{-- =========================
       MAIN
    ========================= --}}
    <main class="contenedor">
        {{-- Cabecera --}}
        <section class="pagina-encabezado">
            <div class="pagina-encabezado-texto">
                <h1 class="titulo pagina-titulo">Administración de Proyectos</h1>
                <p class="pagina-descripcion">Gestiona todos los proyectos del sistema</p>
            </div>

            <button
                type="button"
                class="btn-principal btn-encabezado"
                @click="abrirModal('crear')"
            >
                <span class="btn-icono">+</span>
                Agregar Proyecto
            </button>
        </section>

        {{-- Tabla --}}
        <section class="card-seccion">
            <div class="tabla-contenedor">
                <table class="tabla">
                    <thead class="tabla-cabecera">
                        <tr>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>URL Endpoint</th>
                            <th>Timezone</th>
                            <th>Estado</th>
                            <th class="tabla-col-acciones">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="tabla-cuerpo">
                        @forelse ($proyectos as $proyecto)
                            <tr class="tabla-fila">

                                <td>
                                    <div class="tabla-nombre">
                                        <img
                                            src="{{ asset('assets/icons/proyectos.svg') }}"
                                            alt="Icono de proyectos"
                                            class=""
                                        >
                                        <span>{{ $proyecto->nombre ?? '—' }}</span>
                                    </div>
                                </td>

                                <td>{{ $proyecto->tipo_nombre ?? '—' }}</td>

                                <td>
                                    <p class="url">
                                        {{ $proyecto->url_endpoint ?? '—' }}
                                    </p>
                                </td>

                                <td>{{ $proyecto->timezone ?? '—' }}</td>

                                <td>
                                    <span class="badge-status {{ $proyecto->status === 'ACTIVO' ? 'badge-activo' : 'badge-inactivo' }}">
                                        {{ strtolower($proyecto->status) }}
                                    </span>
                                </td>

                                <td class="tabla-col-acciones">
                                    <div class="tabla-acciones">
                                        <button
                                            type="button"
                                            class="btn-terciario"
                                            @click="abrirEditar({
                                                id: {{ $proyecto->proyecto_id }},
                                                nombre: @js($proyecto->nombre ?? ''),
                                                tipoId: {{ $proyecto->tipo_proyecto_id}},
                                                url: @js($proyecto->url_endpoint ?? ''),
                                                api: @js($proyecto->api_key ?? ''),
                                                timezone: @js($proyecto->timezone ?? ''),
                                                status: @js($proyecto->status ?? 'ACTIVO'),
                                            })"
                                        >
                                            Editar
                                        </button>

                                        <button
                                            type="button"
                                            class="btn-peligro"
                                            @click="abrirEliminar({
                                                id: {{ $proyecto->proyectoId ?? $proyecto->proyecto_id }},
                                                nombre: @js($proyecto->nombre ?? ''),
                                            })"
                                        >
                                            Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="tabla-fila">
                                <td colspan="7" class="tabla-vacia">
                                    No hay proyectos agregados aún.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </section>
    </main>
</div>

@endsection

@section('scripts')
<script>
console.log('Vue script cargado');

const { createApp, nextTick } = Vue;

createApp({
    data() {
        return {
            modales: {
                crear: false,
                editar: false,
                eliminar: false,
            },

            editar: {
                id: null,
                nombre: '',
                tipoId: null,
                url: '',
                api: '',
                timezone: '',
                status: 'ACTIVO',
            },

            eliminar: {
                id: null,
                nombre: '',
            },

            tipos: @json(collect($tipos ?? [])->map(function($t){
                return [
                    'id' => $t->tipo_proyecto_id ?? $t->tipoProyectoId ?? $t->id,
                    'nombre' => $t->nombre ?? '—',
                ];
            })->values()),

            timezones: @json($timezones ?? []),

            rutaActualizarTemplate: @json(route('proyectos.actualizar', ['id' => ':id'])),
            rutaEliminarTemplate:   @json(route('proyectos.eliminar',   ['id' => ':id'])),

            abrirCrearPorErrores: @json($errors->any()),
        };
    },

    computed: {
        formEditarAction() {
            if (!this.editar.id) return '';
            return this.rutaActualizarTemplate.replace(':id', this.editar.id);
        },

        formEliminarAction() {
            if (!this.eliminar.id) return '';
            return this.rutaEliminarTemplate.replace(':id', this.eliminar.id);
        },
    },

    methods: {
        aplicarBodyClass() {
            const algunoActivo = this.modales.crear || this.modales.editar || this.modales.eliminar;
            document.body.classList.toggle('modal-abierto', !!algunoActivo);
        },

        abrirModal(key) {
            this.modales[key] = true;
            this.aplicarBodyClass();

            nextTick(() => {
                if (key === 'crear') this.$refs.inputNombreCrear?.focus?.();
                if (key === 'editar') this.$refs.inputNombreEditar?.focus?.();
            });
        },

        cerrarModal(key) {
            this.modales[key] = false;
            this.aplicarBodyClass();
        },

        cerrarTodos() {
            this.modales.crear = false;
            this.modales.editar = false;
            this.modales.eliminar = false;
            this.aplicarBodyClass();
        },

        abrirEditar(payload) {
            this.editar.id = payload.id;
            this.editar.nombre = payload.nombre || '';
            this.editar.tipoId = payload.tipoId ?? null;
            this.editar.url = payload.url || '';
            this.editar.api = payload.api || '';
            this.editar.timezone = payload.timezone || '';
            this.editar.status = payload.status || 'ACTIVO';

            this.abrirModal('editar');
        },

        abrirEliminar(payload) {
            this.eliminar.id = payload.id;
            this.eliminar.nombre = payload.nombre || '';
            this.abrirModal('eliminar');
        },

        onKeydown(e) {
            if (e.key === 'Escape') this.cerrarTodos();
        },
    },

    mounted() {
        document.addEventListener('keydown', this.onKeydown);

        if (this.abrirCrearPorErrores) {
            this.abrirModal('crear');
        }
    },

    beforeUnmount() {
        document.removeEventListener('keydown', this.onKeydown);
        document.body.classList.remove('modal-abierto');
    },
}).mount('#app-proyectos');
</script>
@endsection
