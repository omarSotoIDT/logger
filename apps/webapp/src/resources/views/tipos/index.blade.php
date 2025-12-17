@extends('layout.app')

@section('titulo', 'Tipos de proyectos logger')

@section('contenido')

{{-- MODAL NUEVO TIPO --}}
<div class="modal-overlay" id="modal-tipo">
    <div class="modal">
        <header class="modal-cabecera">
            <h2 class="modal-titulo">Nuevo Tipo de Proyecto</h2>

            <button type="button" class="modal-cerrar" id="btn-cerrar-modal-tipo" aria-label="Cerrar">✕</button>
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
                <button type="button" class="btn btn-secundario" id="btn-cancelar-modal-tipo">Cancelar</button>
            </footer>
        </form>
    </div>
</div>
{{-- MODAL EDITAR TIPO --}}
<div class="modal-overlay" id="modal-tipo-editar">
    <div class="modal">
        <header class="modal-cabecera">
            <h2 class="modal-titulo">Editar Tipo de Proyecto</h2>

            <button type="button" class="modal-cerrar" data-modal-cerrar="modal-tipo-editar" aria-label="Cerrar">✕</button>
        </header>

        <form class="modal-cuerpo" method="POST" id="form-editar-tipo" action="">
            @csrf
            @method('PATCH')

            <div class="formulario-campo">
                <label for="nombre_editar">Nombre del tipo</label>
                <input
                    type="text"
                    id="nombre_editar"
                    name="nombre"
                    value="{{ old('nombre') }}"
                    placeholder="Ej: Laravel, Python, NodeJS"
                    required
                >
            </div>

            <footer class="modal-pie">
                <button type="submit" class="btn-principal">Guardar cambios</button>
                <button type="button" class="btn-secundario" data-modal-cerrar="modal-tipo-editar">Cancelar</button>
            </footer>
        </form>
    </div>
</div>


{{-- MODAL CONFIRMAR ELIMINACIÓN --}}
<div class="modal-overlay" id="modal-tipo-eliminar">
    <div class="modal">
        <header class="modal-cabecera">
            <h2 class="modal-titulo">Confirmar eliminación</h2>

            <button type="button" class="modal-cerrar" data-modal-cerrar="modal-tipo-eliminar" aria-label="Cerrar">✕</button>
        </header>

        <div class="modal-cuerpo">
            <p class="modal-texto">
                ¿Seguro que deseas eliminar el tipo
                <strong id="tipo-eliminar-nombre">—</strong>?
            </p>

            <form method="POST" id="form-eliminar-tipo" action="">
                @csrf
                @method('DELETE')

                <footer class="modal-pie">
                    <button type="button" class="btn-secundario" data-modal-cerrar="modal-tipo-eliminar">Cancelar</button>
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
            <h1 class="titulo pagina-titulo">Catálogo de Tipos de Proyecto</h1>
            <p class="pagina-descripcion">Gestiona los tipos de proyectos disponibles</p>
        </div>

        <button type="button" class="btn-principal btn-encabezado" id="btn-abrir-modal-tipo">
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
                        <th>Nombre del Tipo</th>
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
                            class="btn-terciario js-btn-editar"
                            data-id="{{ $tipo->tipo_proyecto_id }}"
                            data-nombre="{{ $tipo->nombre }}"
                            >
                            Editar
                            </button>

                            <button
                            type="button"
                            class="btn-peligro js-btn-eliminar"
                            data-id="{{ $tipo->tipo_proyecto_id }}"
                            data-nombre="{{ $tipo->nombre }}"
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
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // ===== Helpers =====
    const abrirModal = (modal) => {
        if (!modal) return;
        modal.classList.add('esta-activo');
        document.body.classList.add('modal-abierto');
    };

    const cerrarModal = (modal) => {
        if (!modal) return;
        modal.classList.remove('esta-activo');

        const algunoActivo = document.querySelector('.modal-overlay.esta-activo');
        if (!algunoActivo) document.body.classList.remove('modal-abierto');
    };

    const cerrarTodos = () => {
        document.querySelectorAll('.modal-overlay.esta-activo').forEach(m => m.classList.remove('esta-activo'));
        document.body.classList.remove('modal-abierto');
    };

    // ===== Modales =====
    const modalCrear   = document.getElementById('modal-tipo');
    const modalEditar  = document.getElementById('modal-tipo-editar');
    const modalEliminar= document.getElementById('modal-tipo-eliminar');

    // ===== Crear (tu modal actual) =====
    const btnAbrirCrear = document.getElementById('btn-abrir-modal-tipo');
    const btnCerrarCrear = document.getElementById('btn-cerrar-modal-tipo');
    const btnCancelarCrear = document.getElementById('btn-cancelar-modal-tipo');

    btnAbrirCrear?.addEventListener('click', () => abrirModal(modalCrear));
    btnCerrarCrear?.addEventListener('click', (e) => { e.preventDefault(); cerrarModal(modalCrear); });
    btnCancelarCrear?.addEventListener('click', (e) => { e.preventDefault(); cerrarModal(modalCrear); });

    [modalCrear, modalEditar, modalEliminar].forEach(modal => {
        modal?.addEventListener('click', (e) => {
            if (e.target === modal) cerrarModal(modal);
        });
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') cerrarTodos();
    });

    document.querySelectorAll('[data-modal-cerrar]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const id = btn.getAttribute('data-modal-cerrar');
            cerrarModal(document.getElementById(id));
        });
    });

    // ===== Editar =====
    const inputNombreEditar = document.getElementById('nombre_editar');
    const formEditar = document.getElementById('form-editar-tipo');

    // plantilla de ruta (reemplazamos :id)
    const rutaActualizarTemplate = @json(route('tipos.actualizar', ['id' => ':id']));

    document.querySelectorAll('.js-btn-editar').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const nombre = btn.dataset.nombre || '';

            inputNombreEditar.value = nombre;
            formEditar.action = rutaActualizarTemplate.replace(':id', id);

            abrirModal(modalEditar);
            setTimeout(() => inputNombreEditar.focus(), 0);
        });
    });

    // ===== Eliminar =====
    const spanNombreEliminar = document.getElementById('tipo-eliminar-nombre');
    const formEliminar = document.getElementById('form-eliminar-tipo');
    const rutaEliminarTemplate = @json(route('tipos.eliminar', ['id' => ':id']));

    document.querySelectorAll('.js-btn-eliminar').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const nombre = btn.dataset.nombre || '';

            spanNombreEliminar.textContent = nombre;
            formEliminar.action = rutaEliminarTemplate.replace(':id', id);

            abrirModal(modalEliminar);
        });
    });

    @if ($errors->any())
        abrirModal(modalCrear);
    @endif
});
</script>
@endsection




