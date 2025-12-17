@php
  $success = session('success');
  $error   = session('error');
  $hasErrors = $errors->any();
@endphp

<div class="toast-contenedor" id="toast-contenedor" aria-live="polite" aria-atomic="true">

  @if ($success)
    <div class="toast toast-exito" data-timeout="3500">
      <div class="toast-icono" aria-hidden="true">✓</div>

      <div class="toast-cuerpo">
        <div class="toast-titulo">Éxito</div>
        <div class="toast-texto">{{ $success }}</div>
      </div>

      <button class="toast-cerrar" type="button" aria-label="Cerrar">✕</button>

      <div class="toast-progress" aria-hidden="true">
        <span></span>
      </div>
    </div>
  @endif

  @if ($error)
    <div class="toast toast-error" data-timeout="6000">
      <div class="toast-icono" aria-hidden="true">!</div>

      <div class="toast-cuerpo">
        <div class="toast-titulo">Ocurrió un error</div>
        <div class="toast-texto">{{ $error }}</div>
      </div>

      <button class="toast-cerrar" type="button" aria-label="Cerrar">✕</button>

      <div class="toast-progress" aria-hidden="true">
        <span></span>
      </div>
    </div>
  @endif

  @if ($hasErrors)
    <div class="toast toast-error" data-timeout="0">
      <div class="toast-icono" aria-hidden="true">!</div>

      <div class="toast-cuerpo">
        <div class="toast-titulo">Revisa los campos</div>
        <div class="toast-texto">
          <ul>
            @foreach ($errors->all() as $msg)
              <li>{{ $msg }}</li>
            @endforeach
          </ul>
        </div>
      </div>

      <button class="toast-cerrar" type="button" aria-label="Cerrar">✕</button>

      <div class="toast-progress" aria-hidden="true">
        <span></span>
      </div>
    </div>
  @endif

</div>
