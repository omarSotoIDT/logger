@extends('layout.app')

@section('titulo', 'Tipos de proyectos logger')

@section('contenido')

<div id="app-tipos">
    {{-- MODAL NUEVO TIPO --}}
    <div
        class="modal-overlay"
        id="modal-tipo"
        v-if="mCrear"
        @click.self="cerrarTodo"
    >
        <div class="modal">
            <header class="modal-cabecera">
                <h2 class="modal-titulo">Nuevo tipo de proyecto</h2>
                <button type="button" class="modal-cerrar" aria-label="Cerrar" @click="cerrarTodo">✕</button>
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
                    >
                </div>

                <footer class="modal-pie">
                    <button type="submit" class="btn btn-principal">Agregar</button>
                    <button type="button" class="btn btn-secundario" @click="cerrarTodo">Cancelar</button>
                </footer>
            </form>
        </div>
    </div>

    {{-- MODAL EDITAR TIPO --}}
    <div
        class="modal-overlay"
        id="modal-tipo-editar"
        v-if="mEditar"
        @click.self="cerrarTodo"
    >
        <div class="modal">
            <header class="modal-cabecera">
                <h2 class="modal-titulo">Editar tipo de proyecto</h2>
                <button type="button" class="modal-cerrar" aria-label="Cerrar" @click="cerrarTodo">✕</button>
            </header>

            <form class="modal-cuerpo" method="POST" id="form-editar-tipo" :action="rutaActualizar">
                @csrf
                @method('PATCH')

                <div class="formulario-campo">
                    <label for="nombre_editar">Nombre del tipo</label>
                    <input
                        type="text"
                        id="nombre_editar"
                        name="nombre"
                        v-model="editarNombre"
                        placeholder="Ej: Laravel, Python, NodeJS"
                        required
                    >
                </div>

                <footer class="modal-pie">
                    <button type="submit" class="btn-principal">Guardar cambios</button>
                    <button type="button" class="btn-secundario" @click="cerrarTodo">Cancelar</button>
                </footer>
            </form>
        </div>
    </div>

    {{-- MODAL CONFIRMAR ELIMINACIÓN --}}
    <div
        class="modal-overlay"
        id="modal-tipo-eliminar"
        v-if="mEliminar"
        @click.self="cerrarTodo"
    >
        <div class="modal">
            <header class="modal-cabecera">
                <h2 class="modal-titulo">Confirmar eliminación</h2>
                <button type="button" class="modal-cerrar" aria-label="Cerrar" @click="cerrarTodo">✕</button>
            </header>

            <div class="modal-cuerpo">
                <p class="modal-texto">
                    ¿Seguro que deseas eliminar el tipo
                    <strong id="tipo-eliminar-nombre">@{{ eliminarNombre || '—' }}</strong>?
                </p>

                <form method="POST" id="form-eliminar-tipo" :action="rutaEliminar">
                    @csrf
                    @method('DELETE')

                    <footer class="modal-pie">
                        <button type="button" class="btn-secundario" @click="cerrarTodo">Cancelar</button>
                        <button type="submit" class="btn-peligro">Sí, eliminar</button>
                    </footer>
                </form>
            </div>
        </div>
    </div>

    <main class="contenedor">
        {{-- Cabecera --}}
        <section class="pagina-encabezado">
            <div class="pagina-encabezado-texto">
                <h1 class="titulo pagina-titulo">Catálogo de tipos de proyecto</h1>
                <p class="pagina-descripcion">Gestiona los tipos de proyectos disponibles</p>
            </div>

            <button type="button" class="btn-principal btn-encabezado" @click="abrirCrear">
                <span class="btn-icono">+</span>
                Agregar Tipo
            </button>
        </section>

        {{-- Tabla --}}
        <section class="card card-seccion">
            <div class="tabla-contenedor">
                <table class="tabla">
                    <thead class="tabla-cabecera">
                        <tr>
                            <th class="tabla-col-id">ID</th>
                            <th>Nombre del tipo</th>
                            <th class="tabla-col-acciones">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="tabla-cuerpo">
                        @foreach ($tipos as $tipo)
                        <tr class="tabla-fila">
                            <td class="tabla-col-id">{{ $tipo->tipo_proyecto_id }}</td>

                            <td class="tabla-nombre">
                                <span class="tabla-icono" aria-hidden="true">&lt;/&gt;</span>
                                <span>{{ $tipo->nombre }}</span>
                            </td>

                            <td class="tabla-col-acciones">
                                <div class="tabla-acciones">
                                    <button
                                        type="button"
                                        class="btn-terciario"
                                        @click="abrirEditar({{ $tipo->tipo_proyecto_id }}, @js($tipo->nombre))"
                                    >
                                        Editar
                                    </button>

                                    <button
                                        type="button"
                                        class="btn-peligro"
                                        @click="abrirEliminar({{ $tipo->tipo_proyecto_id }}, @js($tipo->nombre))"
                                    >
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </section>
    </main>
</div>
@endsection

@section('scripts')

<script>
Vue.createApp({
    data() {
        return {
            // modales
            mCrear: false,
            mEditar: false,
            mEliminar: false,

            editarId: null,
            editarNombre: '',
            eliminarId: null,
            eliminarNombre: '',

            tplActualizar: @json(route('tipos.actualizar', ['id' => ':id'])),
            tplEliminar:   @json(route('tipos.eliminar',   ['id' => ':id'])),

            abrirCrearPorErrores: @json($errors->any()),
        };
    },

    computed: {
        rutaActualizar() {
            return this.editarId ? this.tplActualizar.replace(':id', this.editarId) : '';
        },
        rutaEliminar() {
            return this.eliminarId ? this.tplEliminar.replace(':id', this.eliminarId) : '';
        }
    },

    methods: {
        setBody() {
            const abierto = this.mCrear || this.mEditar || this.mEliminar;
            document.body.classList.toggle('modal-abierto', abierto);
        },

        cerrarTodo() {
            this.mCrear = this.mEditar = this.mEliminar = false;
            this.setBody();
        },

        abrirCrear() {
            this.mCrear = true;
            this.setBody();
        },

        abrirEditar(id, nombre) {
            this.editarId = id;
            this.editarNombre = nombre || '';
            this.mEditar = true;
            this.setBody();
        },

        abrirEliminar(id, nombre) {
            this.eliminarId = id;
            this.eliminarNombre = nombre || '';
            this.mEliminar = true;
            this.setBody();
        }
    },

    mounted() {
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') this.cerrarTodo();
        });

        if (this.abrirCrearPorErrores) this.abrirCrear();
    }
}).mount('#app-tipos');
</script>
@endsection
