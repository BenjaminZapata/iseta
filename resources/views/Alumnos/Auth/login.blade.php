<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISETA inscripciones</title>
    <link rel="icon" type="image/png" href="img/icono-iseta.png">
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <script src="nav.js" defer></script>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">


</head>

<body class="flex p-2" id="logeo">

    <script src="{{ asset('js/libs/ElementEv.js') }}"></script>
    <script src="{{ asset('js/libs/ElementList.js') }}"></script>
    @include('Componentes.mensaje')

    <section class="login">
        <div class="who">
            <a class="tag-alumno act" href="{{ route('alumno.login') }}">Alumno</a>
            <a class="tag-profesor" href="{{ route('profesor.login') }}">Profesor</a>
        </div>

        <form class="w-100p h-50p" method="post" action="{{ route('alumno.login.post') }}">
            @csrf

            <div class="logo">ISETA</div>

            <div class="titulo-login">
                <h1>Inicio de sesión</h1>
                <p>¡Bienvenido! Por favor ingrese sus datos</p>
            </div>

            <div class="usuario input-box">
                <input value="{{ old('email') }}" type="email" name="email" required placeholder="Nombre de usuario">
                <div class="underline"></div>
            </div>

            <div class="contraseña input-box">
                <input type="password" name="password" id="pw-input" required placeholder="Contraseña">
                <div class="flex just-center items-end"><i class="ti ti-eye"></i></div>
                <div class="underline"></div>
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

            <div class="admin-btn-container">
                <a href="{{ route('admin.login') }}" class="admin-btn" title="Solo para administradores">
                    <i class="ti ti-lock"></i> Soy Admin
                </a>
            </div>
        </form>

        <!-- Botón Soy Admin -->

    </section>

    <script src="{{ asset('js/ocultar-mensaje.js') }}"></script>
    <script src="{{ asset('js/mostrar-contrasenia.js') }}"></script>

</body>

</html>