<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ISETA - Recuperar contraseña</title>
    <link rel="icon" type="image/png" href="{{ asset('img/icono-iseta.png') }}">

    <!-- Iconos -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <!-- Estilos de profesor -->
    <link rel="stylesheet" href="{{ asset('css/auth-profesores.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin/mensaje.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin/form.css') }}">
</head>

<body id="logeo-profes">
    @include('Componentes.mensaje')

    <section class="login-profes">
        <form action="{{ route('reset.password.mail') }}" method="GET">
            <div class="logo-container-profes">
                <img src="{{ asset('img/logo-profes.png') }}" class="logo-full-profes" alt="Logo ISETA Profesores">
            </div>

            <div class="titulo-login-profes">
                <h1>Verificar correo</h1>
                <p>Ingresá el correo electrónico donde recibirás el código de verificación</p>
            </div>

            <div class="input-box-profes">
                <div class="input-wrapper-profes">
                    <input type="email" name="email" required placeholder="Correo electrónico">
                </div>
            </div>

            <div class="input-box-profes button-profes">
                <input type="submit" value="Enviar mail">
            </div>
            <div class="etiquetas-profes">
                <a href="{{ route('profesor.login') }}">Volver al inicio</a>
            </div>
        </form>
    </section>

    <script src="{{ asset('js/ocultar-mensaje.js') }}"></script>
</body>

</html>
