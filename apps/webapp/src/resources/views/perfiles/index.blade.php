@extends('layout.app')


@section('titulo', 'Perfiles Logger')

@section('contenido')
<div id="app-perfiles">
    {{-- =========================
       MAIN
    ========================= --}}
    <main class="contenedor">
        {{-- Cabecera --}}
        <section class="pagina-encabezado">
            <div class="pagina-encabezado-texto">
                <h1 class="titulo pagina-titulo">Gestión perfiles</h1>
                <p class="pagina-descripcion">Administra los perfiles y sus permisos asociados</p>
            </div>

            <button
                type="button"
                class="btn-principal btn-encabezado"
                @click="abrirModal('crear')">
                <span class="btn-icono">+</span>
                Agregar Perfil
            </button>
        </section>

        {{-- perfiles --}}
        <section class="card card-seccion">
            <section class="lista-usuarios">
                <template v-for="perfil in perfiles" :key="perfil.perfil_id">
                    <article class="usuario-item">
                        <div class="usuario-info">
                            <div class="usuario-icono">
                                <img src="{{ asset('assets/icons/escudo.svg') }}" alt="Perfil">
                            </div>

                            <div class="usuario-datos">
                                <p class="usuario-nombre">@{{ perfil.titulo }}</p>
                                <div class="datos-proyecto">
                                    <p class="usuario-nameCorto">@{{ perfil.total_permisos || 0 }} permisos asignados</p>
                                </div>
                            </div>
                        </div>
                        <div class="usuario-accion">
                            <button class="acciones" @click="abrirEditar(perfil)">
                                <img src="{{ asset('assets/icons/lapiz.svg') }}" alt="Boton de Editar">
                            </button>

                            <button class="acciones" @click="abrirEliminar(perfil)">
                                <img src="{{ asset('assets/icons/basura.svg') }}" alt="Boton de Eliminar">
                            </button>
                        </div>
                    </article>
                    <p v-if="perfiles.length === 0">No se encontraron perfiles registrados</p>
                </template>
            </section>
        </section>

        <div class="paginacion">
            Página {{ $perfiles->currentPage() }} de {{ $perfiles->lastPage() }}
            {{ $perfiles->links() }}
        </div>
    </main>

    {{-- =========================
       MODAL CREAR PERFIL
    ========================= --}}
    <transition name="transicion-modal">
        <div
            v-if="modales.crear"
            class="modal-overlay"
            id="modal-usuario-crear"
            @click.self="cerrarModal('crear')">
            <div class="modal crear-usuario-modal">

                <header class="modal-cabecera">
                    <h2 class="modal-titulo">Agregar Perfil</h2>

                    <button
                        type="button"
                        class="modal-cerrar"
                        aria-label="Cerrar"
                        @click.prevent="cerrarModal('crear')">
                        <img src="{{ asset('assets/icons/x.svg') }}" alt="icono">
                    </button>
                </header>

                <form class="modal-cuerpo" method="POST" action="{{ route('perfiles.crear') }}">
                    @csrf

                    <div class="crear-usuario-grid">
                        <div class="crear-usuario-campo">
                            <label>Nombre del perfil</label>
                            <input
                                type="text"
                                name="titulo"
                                value="{{ old('titulo') }}"
                                placeholder="Developer"
                                required
                                ref="inputtituloCrear">
                        </div>

                        <div class="crear-usuario-campo">
                            <label>Clave</label>
                            <input
                                type="text"
                                id="clave"
                                name="clave"
                                value="{{ old('clave') }}"
                                placeholder="clave-001"
                                required>
                        </div>
                    </div>
                    <div class="crear-usuario-campo">
                        <label>Descripción</label>
                        <input type="text" id="descripcion" value="{{ old('descripcion') }}" placeholder="ver_proyectos" name="descripcion" required>
                    </div>


                    <!-- PERMISOS -->
                    <div class="crear-usuario-campo">
                        <label>Permisos</label>

                        <div class="permisos-grid permisos-columnas">
                            <span v-if="!permisos || permisos.length === 0" class="crear-usuario-card-titulo">No se encontró ningún permiso</span>
                            <label v-for="permiso in permisos" :key="permiso.permiso_id" class="permiso-card">
                                <input type="checkbox" name="permisos[]" :value="permiso.permiso_id">
                                <div class="permiso-textos">
                                    <span class="permiso-titulo">@{{ permiso.titulo }}</span>
                                    <small class="permiso-subtitulo">@{{permiso.codigo}}</small>
                                </div>
                            </label>
                        </div>
                    </div>
                    <footer class="modal-pie">
                        <button type="submit" class="btn-principal">Agregar</button>
                        <button type="button" class="btn-terciario" @click.prevent="cerrarModal('crear')">Cancelar</button>
                    </footer>
                </form>
            </div>
        </div>
    </transition>
</div>
@endsection

@section('scripts')
<script>
    const {
        createApp,
        nextTick
    } = Vue;

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
                    titulo: '',
                    clave: '',
                    descripcion: '',
                    permisos: [],
                },

                eliminar: {
                    id: null,
                    titulo: '',
                },

                abrirCrearPorErrores: @json($errors->any()),

                perfiles: @json($perfiles->items()),
                permisos: @json($permisos)

            }
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
                this.editar.id = payload.perfil_id;
                this.editar.titulo = payload.titulo;
                this.editar.clave = payload.clave;
                this.editar.descripcion = payload.descripcion;
                this.editar.permisos = payload.permisos ? payload.permisos.map(p => p.permiso_id) : [];

                this.abrirModal('editar');
            },

            abrirEliminar(payload) {
                this.eliminar.id = payload.perfil_id;
                this.eliminar.titulo = payload.titulo;
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
    }).mount('#app-perfiles')
</script>
@endsection