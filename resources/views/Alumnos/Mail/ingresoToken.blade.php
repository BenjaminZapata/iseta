<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{asset('css/Reset/reset.css')}}">

    <link rel="stylesheet" href="{{asset('css/main.css')}}">
    <link rel="stylesheet" href="{{asset('css/estilos.css')}}">

    <link rel="stylesheet" href="{{asset('css/header.css')}}">
    <link rel="stylesheet" href="{{asset('css/footer.css')}}">
    
    @yield('estilos')
    <link rel="stylesheet" href="{{asset('css/global.css')}}">
</head>
    @include('Componentes.mensaje')
    <body id="logeo">
        <div class="login">
            <form action="{{route('token.ingreso.post')}}" method="post">
                @csrf
                <div class="logo">ISETA</div>
                <div class="titulo-login">
                    <h1>Verificar correo</h1>
                    <p>Ingresa el código de verificación enviado a su correo</p>
                </div>
                <div class="contraseña-token">
                    <input name="token" required/>
                </div>
                <div class="crear input-box button"><input type="submit" value="Confirmar"></div>
            </form>
            <a href="{{route('token.enviar.mail')}}">reenviar</a>
        </div>
    </body>
</html>