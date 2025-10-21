<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ISETA - Restablecer contraseña</title>
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

    <section class="login-profes">
        <form action="{{ route('reset.password.post.profe') }}" method="POST">
            @csrf

            <div class="logo-container-profes">
                <img src="{{ asset('img/logo-profes.png') }}" class="logo-full-profes" alt="Logo ISETA Profesores">
            </div>

            <div class="titulo-login-profes">
                <h1>Restablecer contraseña</h1>
                <p>Ingresá el código recibido por correo y elegí tu nueva contraseña</p>
            </div>

            <div class="input-box-profes">
                <div class="input-wrapper-profes">
                    <input type="text" name="token" required placeholder="Código de verificación">
                </div>
            </div>

            <div class="input-box-profes">
                <div class="password-wrapper-profes">
                    <input type="password" name="password" required placeholder="Nueva contraseña">
                    <button type="button" class="toggle-password-profes" aria-label="Mostrar u ocultar contraseña">
                        <i class="ti ti-eye"></i>
                    </button>
                </div>
            </div>

            <div class="input-box-profes button-profes">
                <input type="submit" value="Restablecer">
            </div>

            <div class="etiquetas-profes">
                <a href="{{ route('profesor.login') }}">Volver al inicio</a>
            </div>
        </form>
    </section>

    <script src="{{ asset('js/ocultar-mensaje.js') }}"></script>
    <script src="{{ asset('js/mostrar-contrasenia.js') }}"></script>
</body>

</html>
