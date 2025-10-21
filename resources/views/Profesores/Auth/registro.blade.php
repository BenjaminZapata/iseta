<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISETA - Registro Profesor</title>
    <link rel="icon" type="image/png" href="{{ asset('img/icono-iseta.png') }}">

    <!-- Iconos -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <!-- Estilos de profesores -->
    <link rel="stylesheet" href="{{ asset('css/auth-profesores.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin/mensaje.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin/form.css') }}">
</head>

<body id="logeo-profes">
    @include('Componentes.mensaje')

    <section class="login-profes profesor-profes">
        <!-- Selector de rol -->
        <div class="who-profes">
            <a class="tag-alumno-profes" href="{{ route('alumno.registro') }}">Alumno</a>
            <a class="tag-profesor-profes act-profes" href="{{ route('profesor.register') }}">Profesor</a>
        </div>

        <!-- Formulario -->
        <form action="{{ route('profesor.register.post') }}" method="POST">
            @csrf

            <div class="logo-container-profes">
                <img src="{{ asset('img/logo-profes.png') }}" class="logo-full-profes" alt="Logo ISETA Profesores">
            </div>

            <div class="titulo-login-profes">
                <h1>Registrate</h1>
                <p>¡Bienvenido! Por favor ingrese sus datos</p>
            </div>

            <div class="input-box-profes">
                <div class="input-wrapper-profes">
                    <input type="email" name="email" value="{{ old('email') }}" required
                        placeholder="Correo electrónico">
                </div>
            </div>

            <div class="input-box-profes">
                <div class="input-wrapper-profes">
                    <input type="text" name="dni" value="{{ old('dni') }}" required placeholder="DNI">
                </div>
            </div>

            <div class="input-box-profes">
                <div class="password-wrapper-profes">
                    <input type="password" name="password" required placeholder="Contraseña">
                    <button type="button" class="toggle-password-profes" aria-label="Mostrar u ocultar contraseña">
                        <i class="ti ti-eye"></i>
                    </button>
                </div>
            </div>

            <div class="input-box-profes button-profes">
                <input type="submit" value="Crear">
            </div>

            <div class="etiquetas-profes">
                <p>¿Ya estás registrado? <a href="{{ route('profesor.login') }}">¡Iniciá sesión!</a></p>
            </div>
        </form>
    </section>

    <!-- Scripts -->
    <script src="{{ asset('js/ocultar-mensaje.js') }}"></script>
    <script src="{{ asset('js/mostrar-contrasenia.js') }}"></script>
</body>

</html>
