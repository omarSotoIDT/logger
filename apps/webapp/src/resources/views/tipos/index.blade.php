@extends('layout.app')

@section('titulo', 'Tipos de proyectos logger')

@section('contenido')

<div id="app-tipos">
    {{-- =========================
       MODAL NUEVO TIPO
    ========================= --}}
    <transition name="transicion-modal">
        <div
            v-if="modales.crear"
            class="modal-overlay"
            id="modal-tipo"
            @click.self="cerrarModal('crear')"
        >
            <div class="modal">
                <header class="modal-cabecera">
                    <h2 class="modal-titulo">Nuevo tipo de proyecto</h2>

                    <button
                        type="button"
                        class="modal-cerrar"
                        aria-label="Cerrar"
                        @click.prevent="cerrarModal('crear')"
                    >✕</button>
                </header>

                <form class="modal-cuerpo" method="POST" action="{{ route('tipos.crear') }}">
                    @csrf

                    <div class="formulario-campo">
                        <label for="nombre">Nombre del tipo</label>
                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            value="{{ old('nombre') }}"
                            placeholder="Ej: Laravel, Python, NodeJS"
                            required
                            ref="inputNombreCrear"
                        >
                    </div>

                    <footer class="modal-pie">
                        <button type="submit" class="btn btn-principal">Agregar</button>
                        <button
                            type="button"
                            class="btn btn-terciario"
                            @click.prevent="cerrarModal('crear')"
                        >Cancelar</button>
                    </footer>
                </form>
            </div>
        </div>
    </transition>


    {{-- =========================
       MODAL EDITAR TIPO
    ========================= --}}
    <transition name="transicion-modal">
        <div
            v-if="modales.editar"
            class="modal-overlay"
            id="modal-tipo-editar"
            @click.self="cerrarModal('editar')"
        >
            <div class="modal">
                <header class="modal-cabecera">
                    <h2 class="modal-titulo">Editar tipo de proyecto</h2>

                    <button
                        type="button"
                        class="modal-cerrar"
                        aria-label="Cerrar"
                        @click.prevent="cerrarModal('editar')"
                    >✕</button>
                </header>

                <form class="modal-cuerpo" method="POST" id="form-editar-tipo" :action="formEditarAction">
                    @csrf
                    @method('PATCH')

                    <div class="formulario-campo">
                        <label for="nombre_editar">Nombre del tipo</label>
                        <input
                            type="text"
                            id="nombre_editar"
                            name="nombre"
                            v-model="editar.nombre"
                            placeholder="Ej: Laravel, Python, NodeJS"
                            required
                            ref="inputNombreEditar"
                        >
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
                        <button
                            type="button"
                            class="btn-terciario"
                            @click.prevent="cerrarModal('editar')"
                        >Cancelar</button>
                    </footer>
                </form>
            </div>
        </div>
    </transition>


    {{-- =========================
       CONTENIDO PRINCIPAL
    ========================= --}}
    <main class="contenedor">
        {{-- Cabecera --}}
        <section class="pagina-encabezado">
            <div class="pagina-encabezado-texto">
                <h1 class="titulo pagina-titulo">Catálogo de tipos de proyecto</h1>
                <p class="pagina-descripcion">Gestiona los tipos de proyectos disponibles</p>
            </div>

            <button
                type="button"
                class="btn-principal btn-encabezado"
                @click="abrirModal('crear')"
            >
                <span class="btn-icono">+</span>
                Agregar Tipo
            </button>
        </section>

        {{-- Tabla --}}
        <section class="card-seccion">
            <div class="tabla-contenedor">
                <table class="tabla">
                    <thead class="tabla-cabecera">
                        <tr>
                            <th class="tabla-col-id">ID</th>
                            <th>Nombre del tipo</th>
                            <th>Estado</th>
                            <th class="tabla-col-acciones">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="tabla-cuerpo">
                        <tr class="tabla-fila" v-for="tipo in tipos" :key="tipo.tipo_proyecto_id">
                            <td class="tabla-col-id" v-text="tipo.tipo_proyecto_id"></td>

                            <td>
                                <div class="tabla-nombre">
                                    <img
                                        src="{{ asset('assets/icons/tipos.svg') }}"
                                        alt="Icono de proyectos"
                                        class=""
                                    >
                                    <span v-text="tipo.nombre"></span>
                                </div>
                            </td>

                            <td>
                                <span
                                    :class="['badge-status', tipo.status === 'ACTIVO' ? 'badge-activo' : 'badge-inactivo']"
                                    v-text="tipo.status"
                                ></span>
                            </td>

                            <td class="tabla-col-acciones">
                                <div class="tabla-acciones">
                                    <button
                                        type="button"
                                        class="btn-terciario"
                                        @click="abrirEditar(tipo)"
                                    >
                                        Editar
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="tabla-fila" v-if="!tipos.length">
                            <td colspan="4" class="tabla-vacia">
                                No hay tipos de proyectos agregados aún.
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>
        </section>
    </main>
</div>

@endsection

@section('scripts')
<script>
const { createApp, nextTick } = Vue;

createApp({
    data() {
        return {
            modales: {
                crear: false,
                editar: false,
            },

            editar: {
                tipo_proyecto_id: null,
                nombre: '',
                status: 'ACTIVO',
            },

            tipos: @json($tipos),

            rutaActualizarTemplate: @json(route('tipos.actualizar', ['id' => ':id'])),

            abrirCrearPorErrores: @json($errors->any()),
        };
    },

    computed: {
        formEditarAction() {
            return this.rutaActualizarTemplate.replace(':id', this.editar.tipo_proyecto_id);
        },
    },

    methods: {
        aplicarBodyClass() {
            const algunoActivo = this.modales.crear || this.modales.editar;
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
            this.aplicarBodyClass();
        },

        abrirEditar(tipo) {
            this.editar = tipo;
            this.abrirModal('editar');
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
}).mount('#app-tipos');
</script>
@endsection
