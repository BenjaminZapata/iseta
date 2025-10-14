<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISETA - Login Profesor</title>
    <link rel="icon" type="image/png" href="{{ asset('img/icono-iseta.png') }}">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <script src="nav.js" defer></script>
    <link rel="icon" type="image/png" href="img/icono-iseta.png">

    <!-- Estilos base -->
    <link rel="stylesheet" href="{{ asset('css/auth-profesores.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin/mensaje.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin/form.css') }}">
</head>

<body id="logeo-profes">
    @include('Componentes.mensaje')

    <section class="login-profes profesor-profes">
        <!-- Selector de rol -->
        <div class="who-profes">
            <a class="tag-alumno-profes" href="{{ route('alumno.login') }}">Alumno</a>
            <a class="tag-profesor-profes act-profes" href="{{ route('profesor.login') }}">Profesor</a>
            <a class="tag-admin-profes" href="{{ route('admin.login') }}">Admin</a>
        </div>

        <!-- Formulario -->
        <form method="POST" action="{{ route('profesor.login.post') }}">
            @csrf

            <div class="logo-container-profes">
                <img src="{{ asset('img/logo-profes.png') }}" class="logo-full-profes" alt="Logo ISETA Profesores">
            </div>

            <div class="titulo-login-profes">
                <h1>Inicio de sesión</h1>
                <p>¡Bienvenido! Por favor ingrese sus datos</p>
            </div>

            <div class="usuario-profes input-box-profes">
                <div class="input-wrapper-profes">
                    <input value="{{ old('email') }}" type="email" name="email" required
                        placeholder="Correo electrónico">
                </div>
                @if (session('error'))
                    <div class="campo-alert-profes">
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            <div class="contraseña-profes input-box-profes">
                <div class="password-wrapper-profes">
                    <input type="password" name="password" id="pw-input" required placeholder="Contraseña">
                    <button type="button" class="toggle-password-profes" aria-label="Mostrar u ocultar contraseña">
                        <i class="ti ti-eye"></i>
                    </button>
                </div>
                @if (session('error'))
                    <div class="campo-alert-profes">
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            <div class="entrar-profes input-box-profes button-profes">
                <input type="submit" value="Ingresar">
            </div>

            <div class="etiquetas-profes">
                <a href="{{ route('profesor.register') }}">¡Registrate!</a>
            </div>

            <div class="etiquetas-profes">
                <a href="{{ route('reset.password.profe') }}">¿Ha olvidado su contraseña?</a>
            </div>
        </form>
    </section>

    <!-- Scripts funcionales -->
    <script src="{{ asset('js/ocultar-mensaje.js') }}"></script>
    <script src="{{ asset('js/mostrar-contrasenia.js') }}"></script>
</body>

</html>
