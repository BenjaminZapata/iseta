<!DOCTYPE html>
<html lang="es">
<link rel="stylesheet" href="{{ asset('css/Admin/auth.css') }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Portal IM Administradores</title>
</head>

<body>
    <div class="login-box">
        <div class="logo-container">
            <img src="{{ asset('img/logo-sf.png') }}">
        </div>
        <h2>Administradores</h2>

        <!-- Mensaje de error desde backend -->
        {{-- @include('Componentes.mensaje') --}}

        <form method="POST" action="{{ route('admin.login.post') }}" novalidate>
            @csrf

            {{-- Campo Rol --}}
            <label for="rol">Rol</label>
            <select name="rol" id="rol" required aria-required="true" aria-label="Seleccione su rol">
                <option value="" disabled {{ old('rol') ? '' : 'selected' }}>Seleccione rol</option>
                <option value="regente" {{ old('rol') === 'regente' ? 'selected' : '' }}>Regente</option>
                <option value="preceptor" {{ old('rol') === 'preceptor' ? 'selected' : '' }}>Preceptor</option>
                <option value="secretario" {{ old('rol') === 'secretario' ? 'selected' : '' }}>Secretario</option>
            </select>
            @error('rol')
                <div class="error-message">{{ $message }}</div>
            @enderror

            {{-- Campo Usuario --}}
            <label for="username">Usuario</label>
            <input id="username" name="username" type="text" required autocomplete="username" aria-required="true"
                aria-label="Nombre de usuario" placeholder="Ingrese su usuario" value="{{ old('username') }}" />
            @error('username')
                <div class="error-message">{{ $message }}</div>
            @enderror

            {{-- Campo Contraseña --}}
            <label for="password">Contraseña</label>
            <input id="password" name="password" type="password" required autocomplete="current-password"
                aria-required="true" aria-label="Contraseña" placeholder="Ingrese su contraseña" />
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror

            {{-- Botón --}}
            <button type="submit" class="btn-login">Ingresar</button>
        </form>

        <p class="forgot-password">
            <a href="">¿Olvidaste tu contraseña?</a>
        </p>
    </div>
</body>

</html>
