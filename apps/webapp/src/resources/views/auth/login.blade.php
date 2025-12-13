<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Logger Inicio de Sesión</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
</head>
<body class="centrar">

    <div class="card card-login">

        <div class="card-cabecera">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="50"
              height="50"
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
    
            <p class="titulo">Logger</p>
            <p>Sistema de gestión de logs</p>
            @if(session('error'))
                <p style="color:red;">
                    {{ session('error') }}
                </p>
            @endif
            
            @if(session('success'))
                <p style="color:green;">
                    {{ session('success') }}
                </p>
            @endif
            
            @if($errors->any())
                <ul style="color:red;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            
        </div>
    
    
        <form method="POST" action="{{ route('login.logger') }}">
            @csrf
    
            <div class="formulario-campo">
                <label for="usuario">Usuario</label><br>
                <input
                    type="text"
                    name="usuario"
                    id="usuario"
                    value="{{ old('usuario') }}"
                    placeholder="Ingresa tu usuario"
                    required
                >
            </div>
    
            <br>
    
            <div class="formulario-campo">
                <label for="password">Contraseña</label><br>
                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Ingresa tu contraseña"
                    required
                >
            </div>
    
            <br>
    
            <button class="btn-principal" type="submit">
                Iniciar Sesión
            </button>
        </form>

        <div class="subcard">
            <p class="titulo">Credenciales de prueba:</p>
            <p>admin / password(Administrador)</p> 
            <p>user1 / password(Usuario)</p> 
        </div>

    </div> <!--.card-->
</body>
</html>