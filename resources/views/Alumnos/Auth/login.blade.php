<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISETA - Login Alumno</title>
    <link rel="icon" type="image/png" href="img/icono-iseta.png">

    <!-- Estilos base -->
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body id="logeo">

    @include('Componentes.mensaje')

    <section class="login alumno">
        <!-- Selector de rol -->
        <div class="who">
            <a class="tag-alumno act" href="{{ route('alumno.login') }}">Alumno</a>
            <a class="tag-profesor" href="{{ route('profesor.login') }}">Profesor</a>
            <a class="tag-admin" href="{{ route('admin.login') }}">Admin</a>
        </div>

        <!-- Formulario -->
        <form method="POST" action="{{ route('alumno.login.post') }}">
            @csrf

            <div class="logo">ISETA</div>

            <div class="titulo-login">
                <h1>Inicio de sesión</h1>
                <p>¡Bienvenido! Por favor ingrese sus datos</p>
            </div>

            <div class="usuario input-box">
                <input value="{{ old('email') }}" type="email" name="email" required
                    placeholder="Nombre de usuario">
            </div>

            <div class="contraseña input-box">
                <input type="password" name="password" id="pw-input" required placeholder="Contraseña">
            </div>

            <div class="entrar input-box button">
                <input type="submit" value="Entrar">
            </div>

            <div class="etiquetas">
                <a href="{{ route('alumno.registro') }}">¡Registrate!</a>
            </div>

            <div class="etiquetas">
                <a href="{{ route('reset.password') }}">¿Ha olvidado su contraseña?</a>
            </div>

        </form>
    </section>

    <!-- Scripts funcionales -->
    <script src="{{ asset('js/ocultar-mensaje.js') }}"></script>
    <script src="{{ asset('js/mostrar-contrasenia.js') }}"></script>
</body>

</html>
