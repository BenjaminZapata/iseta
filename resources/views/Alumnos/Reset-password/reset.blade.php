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

    <!-- Estilos reutilizados -->
    <link rel="stylesheet" href="{{ asset('css/auth-alumnos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin/mensaje.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin/form.css') }}">
</head>

<body id="logeo-alumno">
    @include('Componentes.mensaje')

    <section class="login alumno">
        <form action="{{ route('reset.password.mail') }}" method="GET">
            <div class="logo-container">
                <img src="{{ asset('img/logo-alumnos-bk.png') }}" class="logo-full" alt="Logo ISETA Alumnos">
            </div>

            <div class="titulo-login">
                <h1>Verificar correo</h1>
                <p>Ingresá el correo electrónico donde recibirás el código de verificación</p>
            </div>

            <div class="usuario input-box">
                <div class="input-wrapper">
                    <input type="email" name="email" required placeholder="Correo electrónico">
                </div>
            </div>

            <div class="crear input-box button">
                <input type="submit" value="Enviar mail">
            </div>
        </form>
    </section>

    <script src="{{ asset('js/ocultar-mensaje.js') }}"></script>
</body>

</html>
