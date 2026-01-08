@extends('layout.app')


@section('titulo', 'Usuarios Logger')

@section('contenido')
<div id="app-usuarios">
    {{-- =========================
       MAIN
    ========================= --}}
    <main class="contenedor">
        {{-- Cabecera --}}
        <section class="pagina-encabezado">
            <div class="pagina-encabezado-texto">
                <h1 class="titulo pagina-titulo">Gestion de usuarios</h1>
                <p class="pagina-descripcion">Administra usuarios y asigna permisos a proyectos</p>
            </div>

            <button
                type="button"
                class="btn-principal btn-encabezado"
                @click="abrirModal('crear')">
                <span class="btn-icono">+</span>
                Agregar Usuario
            </button>
        </section>

        {{-- usuarios --}}
        <section class="card card-seccion">
            <section class="lista-usuarios">
                <template v-for="usuario in usuarios" :key="usuario.usuario_id">
                    <article class="usuario-item">
                        <div class="usuario-info">
                            <div class="usuario-icono">
                                <img src="{{ asset('assets/icons/usuarios.svg') }}" alt="Usuario">
                            </div>

                            <div class="usuario-datos">
                                <p class="usuario-nombre">@{{ usuario.usuario }}</p>
                                <p class="usuario-email">@{{ usuario.email }}</p>
                                <div class="datos-proyecto">
                                    <p class="usuario-nameCorto">@{{ usuario.nombre_corto }}</p>
                                    <p class="usuario-proyectos">@{{ usuario.proyectos?.length || 0}} proyectos</p>
                                </div>
                            </div>
                        </div>
                        <div class="usuario-accion">
                            <button class="btn-accion" @click="abrirEditar(usuario)">
                                <img src="{{ asset('assets/icons/editar.svg') }}" alt="Boton de Editar">
                            </button>

                            <button class="btn-accion" @click="abrirEliminar(usuario)">
                                <img src="{{ asset('assets/icons/eliminar.svg') }}" alt="Boton de Eliminar">
                            </button>

                            <span class="flecha" @click="toggleUsuario(usuario.usuario_id)">@{{ usuarioActivo === usuario.usuario_id ? '▴' : '▾' }}</span>
                        </div>
                    </article>
                    {{-- =========================
                                PANEL
                    ========================= --}}
                    <div
                        v-if="usuarioActivo === usuario.usuario_id"
                        class="usuario-panel" ref="panelUsuario">
                        <!-- PERFILES -->
                        <div class="usuario-panel-seccion">
                            <h4 class="usuario-panel-titulo">Perfiles Asignados</h4>

                            <div v-if="!usuario.perfiles || usuario.perfiles.length === 0" class="usuario-panel-vacio">
                                No se encontraron perfiles asignados
                            </div>

                            <div v-for="perfil in usuario.perfiles" :key="perfil.perfil_id" class="perfil-card">
                                <div class="crear-usuario-icono">
                                    <img src="{{ asset('assets/icons/perfil.svg') }}" alt="Perfil">
                                </div>
                                <div class="perfil-info">
                                    <span class="perfil-nombre">@{{ perfil.titulo }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- PROYECTOS -->
                        <div class="usuario-panel-seccion">
                            <h4 class="usuario-panel-titulo">Proyectos Asignados</h4>

                            <div v-if="!usuario.proyectos || usuario.proyectos.length === 0" class="usuario-panel-vacio">
                                No se encontraron proyectos asignados
                            </div>

                            <div v-for="proyecto in usuario.proyectos" :key="proyecto.proyecto_id" class="proyecto-card">
                                <span class="proyecto-nombre">@{{ proyecto.nombre }}</span>
                                <span class="badge-status" :class="proyecto.status === 'ACTIVO' ? 'badge-activo' : 'badge-inactivo' ">@{{ proyecto.status }}</span>
                            </div>
                        </div>
                    </div>
                    <p v-if="usuarios.length === 0">No se encontraron usuarios registrados</p>
                </template>
            </section>
        </section>

        <div class="paginacion">
            Página {{ $usuarios->currentPage() }} de {{ $usuarios->lastPage() }}
            {{ $usuarios->links() }}
        </div>
    </main>

    {{-- =========================
       MODAL CREAR USUARIO
    ========================= --}}
    <transition name="transicion-modal">
        <div
            v-if="modales.crear"
            class="modal-overlay"
            id="modal-usuario-crear"
            @click.self="cerrarModal('crear')">
            <div class="modal crear-usuario-modal">

                <header class="modal-cabecera">
                    <h2 class="modal-titulo">Agregar Usuario</h2>

                    <button
                        type="button"
                        class="modal-cerrar"
                        aria-label="Cerrar"
                        @click.prevent="cerrarModal('crear')">
                        <img src="{{ asset('assets/icons/x.svg') }}" alt="icono">
                    </button>
                </header>

                <form class="modal-cuerpo" method="POST" action="{{ route('usuarios.crear') }}">
                    @csrf

                    <div class="crear-usuario-grid">
                        <div class="crear-usuario-campo">
                            <label>Nombre de Usuario</label>
                            <input
                                type="text"
                                name="usuario"
                                value="{{ old('usuario') }}"
                                placeholder="User1"
                                required
                                ref="inputusuarioCrear">
                        </div>

                        <div class="crear-usuario-campo">
                            <label>Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="usuario@ejemplo.com"
                                required>
                        </div>
                    </div>
                    <div class="crear-usuario-grid">
                        <div class="crear-usuario-campo">
                            <label for="nombreCorto">Tipo Usuario</label>
                            <select id="nombreCorto" name="nombreCorto" required>
                                <option value="Administrador" {{ old('nombreCorto', 'Administrador') === 'Administrador' ? 'selected' : '' }}>Administrador</option>
                                <option value="Usuario" {{ old('nombreCorto') === 'Usuario' ? 'selected' : '' }}>Usuario</option>
                            </select>
                        </div>
                        <div class="crear-usuario-campo">
                            <label>Contraseña</label>
                            <input type="password" id="password" value="{{ old('password') }}" placeholder="Contraseña" name="password" required>
                        </div>
                    </div>

                    <!-- PERFILES -->
                    <div class="crear-usuario-campo">
                        <label>Perfiles</label>

                        <div class="crear-usuario-cards">
                            <span v-if="!perfiles || perfiles.length === 0" class="crear-usuario-card-titulo">No se encontró ningún perfil</span>
                            <label v-for="perfil in perfiles" :key="perfil.perfil_id" class="crear-usuario-card">
                                <input type="checkbox" name="perfiles[]" value="perfil.perfil_id">
                                <div class="crear-usuario-icono">
                                    <img src="{{ asset('assets/icons/perfil.svg') }}" alt="Perfil">
                                </div>
                                <div class="crear-usuario-card-info">
                                    <span class="crear-usuario-card-titulo">@{{perfil.titulo}}</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- PROYECTOS -->
                    <div class="crear-usuario-campo">
                        <label>Proyectos Asignados</label>

                        <div class="crear-usuario-cards">
                            <span v-if="!proyectos || proyectos.length === 0" class="crear-usuario-card-titulo">No se encontró ningún proyecto</span>
                            <label v-for="proyecto in proyectos" :key="proyecto.proyecto_id" class="crear-usuario-card">
                                <input type="checkbox" name="proyectos[]" value="proyecto.proyecto_id">
                                <div class="crear-usuario-card-info">
                                    <span class="crear-usuario-card-titulo">@{{proyecto.nombre}}</span>
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

    {{-- =========================
       MODAL EDITAR TIPO
    ========================= --}}
    <transition name="transicion-modal">
        <div
            v-if="modales.editar"
            class="modal-overlay"
            id="modal-tipo-editar"
            @click.self="cerrarModal('editar')">
            <div class="modal">
                <header class="modal-cabecera">
                    <h2 class="modal-titulo">Editar Usuario</h2>

                    <button
                        type="button"
                        class="modal-cerrar"
                        aria-label="Cerrar"
                        @click.prevent="cerrarModal('editar')">
                        <img src="{{ asset('assets/icons/x.svg') }}" alt="icono">
                    </button>
                </header>

                <form class="modal-cuerpo" method="POST" id="form-editar-tipo" :action="formEditarAction">
                    @csrf
                    @method('PATCH')

                    <div class="crear-usuario-grid">
                        <div class="crear-usuario-campo">
                            <label>Nombre de Usuario</label>
                            <input
                                type="text"
                                name="usuario"
                                v-model="editar.usuario"
                                placeholder="User1"
                                required
                                ref="inputusuarioCrear">
                        </div>

                        <div class="crear-usuario-campo">
                            <label>Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                v-model="editar.email"
                                placeholder="usuario@ejemplo.com"
                                required>
                        </div>
                    </div>
                    <div class="crear-usuario-grid">
                        <div class="crear-usuario-campo">
                            <label for="nombreCorto">Tipo Usuario</label>
                            <select id="nombreCorto" name="nombreCorto" v-model="editar.nombreCorto" required>
                                <option value="Administrador" {{ old('nombreCorto', 'Administrador') === 'Administrador' ? 'selected' : '' }}>Administrador</option>
                                <option value="Usuario" {{ old('nombreCorto') === 'Usuario' ? 'selected' : '' }}>Usuario</option>
                            </select>
                        </div>
                        <div class="crear-usuario-campo">
                            <label>Contraseña</label>
                            <input type="password" id="password" value="{{ old('password') }}" placeholder="Contraseña" name="password">
                        </div>
                    </div>

                    <!-- PERFILES -->
                    <div class="crear-usuario-campo">
                        <label>Perfiles</label>

                        <div class="crear-usuario-cards">
                            <span v-if="!perfiles || perfiles.length === 0" class="crear-usuario-card-titulo">No se encontró ningún perfil</span>
                            <label v-for="perfil in perfiles" :key="perfil.perfil_id" class="crear-usuario-card">
                                <input type="checkbox" name="perfiles[]" :value="perfil.perfil_id" v-model="editar.perfiles">
                                <div class="crear-usuario-icono">
                                    <img src="{{ asset('assets/icons/perfil.svg') }}" alt="Perfil">
                                </div>
                                <div class="crear-usuario-card-info">
                                    <span class="crear-usuario-card-titulo">@{{perfil.titulo}}</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- PROYECTOS -->
                    <div class="crear-usuario-campo">
                        <label>Proyectos Asignados</label>

                        <div class="crear-usuario-cards">
                            <span v-if="!proyectos || proyectos.length === 0" class="crear-usuario-card-titulo">No se encontró ningún proyecto</span>
                            <label v-for="proyecto in proyectos" :key="proyecto.proyecto_id" class="crear-usuario-card">
                                <input type="checkbox" name="proyectos[]" :value="proyecto.proyecto_id" v-model="editar.proyectos">
                                <div class="crear-usuario-card-info">
                                    <span class="crear-usuario-card-titulo">@{{proyecto.nombre}}</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <footer class="modal-pie">
                        <button type="submit" class="btn-principal">Guardar cambios</button>
                        <button
                            type="button"
                            class="btn-secundario"
                            @click.prevent="cerrarModal('editar')">Cancelar</button>
                    </footer>
                </form>
            </div>
        </div>
    </transition>

    {{-- =========================
       MODAL CONFIRMAR ELIMINACIÓN
    ========================= --}}
    <transition name="transicion-modal">
        <div
            v-if="modales.eliminar"
            class="modal-overlay"
            id="modal-tipo-eliminar"
            @click.self="cerrarModal('eliminar')">
            <div class="modal">
                <header class="modal-cabecera">
                    <h2 class="modal-titulo">Confirmar eliminación</h2>

                    <button
                        type="button"
                        class="modal-cerrar"
                        aria-label="Cerrar"
                        @click.prevent="cerrarModal('eliminar')">
                        <img src="{{ asset('assets/icons/x.svg') }}" alt="icono">
                    </button>
                </header>

                <div class="modal-cuerpo">
                    <p class="modal-texto">
                        ¿Seguro que deseas eliminar el usuario
                        <strong id="tipo-eliminar-nombre">@{{ eliminar.usuario || '—' }}</strong>?
                    </p>

                    <form method="POST" id="form-eliminar-tipo" :action="formEliminarAction">
                        @csrf
                        @method('DELETE')

                        <footer class="modal-pie">
                            <button
                                type="button"
                                class="btn-secundario"
                                @click.prevent="cerrarModal('eliminar')">Cancelar</button>
                            <button type="submit" class="btn-peligro">Sí, eliminar</button>
                        </footer>
                    </form>
                </div>
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
                usuarioActivo: null,

                editar: {
                    id: null,
                    usuario: '',
                    email: '',
                    nombreCorto: '',
                    proyectos: [],
                    perfiles: []
                },

                eliminar: {
                    id: null,
                    usuario: '',
                },

                rutaActualizarTemplate: @json(route('usuarios.actualizar', ['id' => ':id'])),
                rutaEliminarTemplate: @json(route('usuarios.eliminar', ['id' => ':id'])),

                abrirCrearPorErrores: @json($errors -> any()),

                usuarios: @json($usuarios->items()),
                proyectos: @json($proyectos),
                perfiles: @json($perfiles),
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

            toggleUsuario(id) {
                this.usuarioActivo = this.usuarioActivo === id ? null : id;
            },

            abrirEditar(payload) {
                this.editar.id = payload.usuario_id;
                this.editar.usuario = payload.usuario;
                this.editar.email = payload.email;
                this.editar.nombreCorto = payload.nombre_corto;
                this.editar.proyectos = payload.proyectos ? payload.proyectos.map(p => p.proyecto_id) : [];
                this.editar.perfiles = payload.perfiles ? payload.perfiles.map(p => p.perfil_id) : [];

                this.abrirModal('editar');
            },

            abrirEliminar(payload) {
                this.eliminar.id = payload.usuario_id;
                this.eliminar.usuario = payload.usuario;
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
    }).mount('#app-usuarios')
</script>
@endsection