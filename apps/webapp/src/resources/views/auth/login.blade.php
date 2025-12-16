<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Logger Inicio de Sesión</title>
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="centrar">

    <div class="card card-login">

        <div class="card-cabecera">
            <img
                src="{{ asset('assets/icons/logo-login.svg') }}"
                alt="Login Logger"
                class="login-logo"
            >
    
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

    </div> <!--.card-->
</body>
</html>