<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{asset('css/Admin/login.css')}}">
    <link rel="stylesheet" href="{{asset('css/Reset/reset.css')}}">
    <link rel="stylesheet" href="{{asset('css/Admin/main.css')}}">
    <link rel="stylesheet" href="{{asset('css/global.css')}}">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

</head>
<body>
    <div class="login-box">

        <form method="POST" action="{{route('admin.login.post')}}">
            @csrf
          <div class="user-box">
            <input value="" type="text" name="username" required="">
            <label>Username</label>
          </div>
          <div class="user-box">
            <input value="" type="password" name="password" name="" required="">
            <label>Password</label>
          </div>
          <input type="submit" value="Login">
        </form>
      </div>
      @include('Componentes.mensaje')
</body>
</html>
