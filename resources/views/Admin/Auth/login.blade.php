<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login Administradores</title>
</head>

<body>
  <div class="login-box">
    <h2>Ingreso Administradores</h2>

    <!-- Mensaje de error desde backend -->
    {{-- @include('Componentes.mensaje') --}}

    <form method="POST" action="{{ route('admin.login.post') }}" novalidate>
      @csrf

      <label for="role">Rol</label>
      <select name="role" id="role" required aria-required="true" aria-label="Seleccione su rol">
        <option value="" disabled selected>Seleccione rol</option>
        <option value="regente">Regente</option>
        <option value="preceptor">Preceptor</option>
        <option value="secretario">Secretario</option>
      </select>

      <label for="username">Usuario</label>
      <input id="username" name="username" type="text" required autocomplete="username" aria-required="true"
        aria-label="Nombre de usuario" placeholder="Ingrese su usuario" />

      <label for="password">Contraseña</label>
      <input id="password" name="password" type="password" required autocomplete="current-password" aria-required="true"
        aria-label="Contraseña" placeholder="Ingrese su contraseña" />

      <button type="submit" class="btn-login">Ingresar</button>
    </form>

    <p class="forgot-password">
      <a href="">¿Olvidaste tu contraseña?</a>
    </p>
  </div>
</body>

</html>