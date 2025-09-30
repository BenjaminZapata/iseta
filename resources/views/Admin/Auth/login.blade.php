<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Portal IM Administradores</title>
    <link rel="icon" type="image/png" href="{{ asset('img/icono-iseta.png') }}">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <!-- Estilos base unificados -->
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Admin/mensaje.css') }}">
</head>

<body id="logeo-admins" class="admin">
    @include('Componentes.mensaje')

    <section class="login-admin">
        <!-- Selector de rol -->
        <div class="who">
            <a class="tag-alumno" href="{{ route('alumno.login') }}">Alumno</a>
            <a class="tag-profesor" href="{{ route('profesor.login') }}">Profesor</a>
            <a class="tag-admin act" href="{{ route('admin.login') }}">Admin</a>
        </div>

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf

            <div class="logo-container">
                <img src="{{ asset('img/logo-sf.png') }}" class="logo-full" alt="Logo Admin">
            </div>

            <div class="titulo-login">
                <h1>Inicio de sesión</h1>
                <p>Solo personal administrativo autorizado</p>
            </div>

            <!-- Campo de selección de rol -->
            <div class="input-box">
                <div class="input-wrapper">
                    <select name="rol" id="rol" required>
                        <option value="" disabled {{ old('rol') ? '' : 'selected' }}>Seleccione rol</option>
                        <option value="regente" {{ old('rol') === 'regente' ? 'selected' : '' }}>Regente</option>
                        <option value="preceptor" {{ old('rol') === 'preceptor' ? 'selected' : '' }}>Preceptor</option>
                        <option value="secretario" {{ old('rol') === 'secretario' ? 'selected' : '' }}>Secretario
                        </option>
                    </select>
                </div>
                @error('rol')
                    <div class="campo-alert">{{ $message }}</div>
                @enderror
            </div>

            <!-- Campo de usuario -->
            <div class="input-box">
                <div class="input-wrapper">
                    <input type="text" id="username" name="username" required placeholder="Ingrese su usuario"
                        value="{{ old('username') }}">
                </div>
                @error('username')
                    <div class="campo-alert">{{ $message }}</div>
                @enderror
            </div>

            <!-- Campo de contraseña -->
            <div class="input-box">
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" required placeholder="Ingrese su contraseña">
                    <button type="button" class="toggle-password" aria-label="Mostrar u ocultar contraseña">
                        <i class="ti ti-eye"></i>
                    </button>
                </div>
                @error('password')
                    <div class="campo-alert">{{ $message }}</div>
                @enderror
            </div>

            <!-- Botón de ingreso -->
            <div class="input-box button">
                <input type="submit" value="Ingresar">
            </div>

            <div class="etiquetas">
                <a href="#">¿Olvidaste tu contraseña?</a>
            </div>
        </form>
    </section>

    <!-- Scripts -->
    <script src="{{ asset('js/mostrar-contrasenia.js') }}"></script>
</body>

</html>
