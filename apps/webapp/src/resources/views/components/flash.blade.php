@php
  $success = session('success');
  $error   = session('error');
  $warning   = session('warning');
  $hasErrors = $errors->any();
@endphp

<div class="toast-contenedor" id="toast-contenedor" aria-live="polite" aria-atomic="true">

  @if ($warning)
    <div class="toast toast-warn" data-timeout="6000">
      <div class="toast-icono" aria-hidden="true">!</div>

      <div class="toast-cuerpo">
        <div class="toast-titulo">Aviso</div>
        <div class="toast-texto">{{ $warning }}</div>
      </div>

      <button class="toast-cerrar" type="button" aria-label="Cerrar">✕</button>

      <div class="toast-progress" aria-hidden="true">
        <span></span>
      </div>
    </div>

    @php session()->forget('warning'); @endphp

  @endif

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

    @php session()->forget('success'); @endphp

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

    @php session()->forget('error'); @endphp
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
