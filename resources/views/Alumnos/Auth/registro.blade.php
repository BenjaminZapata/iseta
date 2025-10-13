<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISETA - Registro Alumno</title>
    <link rel="icon" type="image/png" href="{{ asset('img/icono-iseta.png') }}">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <!-- Reutilizamos los mismos estilos -->
    <link rel="stylesheet" href="{{ asset('css/auth-alumnos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin/mensaje.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin/form.css') }}">
</head>

<body id="logeo-alumno">

    @include('Componentes.mensaje')

    <section class="login alumno">
        <!-- Selector de rol -->
        <div class="who">
            <a class="tag-alumno act" href="{{ route('alumno.registro') }}">Alumno</a>
            <a class="tag-profesor" href="{{ route('profesor.register') }}">Profesor</a>
        </div>

        <!-- Formulario -->
        <form method="POST" action="{{ route('alumno.registro.post') }}">
            @csrf

            <div class="logo-container">
                <img src="{{ asset('img/logo-alumnos-bk.png') }}" class="logo-full" alt="Logo ISETA Alumnos">
            </div>

            <div class="titulo-login">
                <h1>Registrate</h1>
                <p>¡Bienvenido! Por favor ingrese sus datos</p>
            </div>

            <div class="usuario input-box">
                <div class="input-wrapper">
                    <input type="email" name="email" value="{{ old('email') }}" required
                        placeholder="Correo electrónico">
                </div>
            </div>

            <div class="dni input-box">
                <div class="input-wrapper">
                    <input type="text" name="dni" value="{{ old('dni') }}" required placeholder="DNI">
                </div>
            </div>

            <div class="contraseña input-box">
                <div class="password-wrapper">
                    <input type="password" name="password" required placeholder="Contraseña">
                    <button type="button" class="toggle-password" aria-label="Mostrar u ocultar contraseña">
                        <i class="ti ti-eye"></i>
                    </button>
                </div>
            </div>

            <div class="crear input-box button">
                <input type="submit" value="Crear">
            </div>

            <div class="etiquetas">
                <p>¿Ya estás registrado? <a href="{{ route('alumno.login') }}">¡Iniciá sesión!</a></p>
            </div>
        </form>
    </section>

    <script src="{{ asset('js/ocultar-mensaje.js') }}"></script>
    <script src="{{ asset('js/mostrar-contrasenia.js') }}"></script>

</body>

</html>
