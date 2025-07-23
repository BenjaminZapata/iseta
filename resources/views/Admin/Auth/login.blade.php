<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login Administradores</title>
  <style>
    /* Reset básico */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      background: #140b5c;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #ffffff;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-box {
      background: #1b1274;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
      width: 350px;
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
      font-weight: bold;
      color: #ffffff;
    }

    form {
      display: flex;
      flex-direction: column;
    }

    label {
      margin-bottom: 5px;
      font-size: 0.95rem;
      font-weight: 500;
      color: #d1d1ff;
    }

    select,
    input[type="text"],
    input[type="password"] {
      padding: 10px 12px;
      margin-bottom: 20px;
      border: 1px solid #2d238a;
      border-radius: 6px;
      background-color: #2c247e;
      color: #ffffff;
      font-size: 1rem;
      transition: border 0.3s, background-color 0.3s;
    }

    select:focus,
    input:focus {
      border: 1px solid #5c4ee5;
      outline: none;
      background-color: #352ca2;
    }

    ::placeholder {
      color: #bcbcf5;
    }

    button.btn-login {
      background-color: #5c4ee5;
      border: none;
      padding: 12px;
      font-size: 1rem;
      font-weight: bold;
      border-radius: 6px;
      cursor: pointer;
      color: #ffffff;
      transition: background-color 0.3s;
    }

    button.btn-login:hover {
      background-color: #483dc2;
    }

    .forgot-password {
      text-align: right;
      margin-top: 12px;
    }

    .forgot-password a {
      color: #b8b8ff;
      font-size: 0.9rem;
      text-decoration: none;
    }

    .forgot-password a:hover {
      text-decoration: underline;
    }

    .error-message {
      background-color: #e74c3c;
      padding: 10px;
      border-radius: 6px;
      color: #fff;
      font-weight: 600;
      text-align: center;
      margin-bottom: 15px;
    }
  </style>
</head>

<body>
  <div class="login-box">
    <h2>Ingreso Administradores</h2>

    <!-- Mensaje de error desde backend -->
    {{-- @include('Componentes.mensaje') --}}

    <form method="POST" action="{{ route('admin.login') }}">
      @csrf
      <input type="text" name="username" placeholder="Usuario">
      <input type="password" name="password" placeholder="Contraseña">
      <select name="rol">
        <option value="regente">Regente</option>
        <option value="preceptor">Preceptor</option>
        <option value="secretario">Secretario</option>
      </select>
      <button type="submit">Ingresar</button>
    </form>


    <p class="forgot-password">
      <a href="">¿Olvidaste tu contraseña?</a>
    </p>
  </div>
</body>

</html>