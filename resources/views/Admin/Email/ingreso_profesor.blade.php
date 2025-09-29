<!DOCTYPE HTML
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style type="text/css">
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            background-color: white;
            font-family: 'Lato', sans-serif;
            color: black;
            display: flex;
            justify-content: center;
            width: 100%;
        }

        h1 {
            font-family: 'Openface';
            display: flex;
            justify-content: center;
            align-items: center;
            color: #140B5C;
            font-size: 55px;
            margin: 15px 0 25px 0;
        }

        h2 {
            font-family: Lato, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            background-color: #140B5C;
            color: white;
            padding: 30px 0;
            font-size: 30px;
        }

        span {
            font-weight: 600;
            color: #140B5C;
            font-size: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 0;

        }

        p {
            margin: 7px 20px;
        }

        .relleno {
            color: gray;
        }

        .border {
            border-top: 1px solid black;
            padding-top: 20px;
            color: gray;
        }

        .divisor {
            width: 10%;
        }

        .info-mail {
            width: 80%;
        }

        .emisor {
            font-weight: 600;
        }

        @media only screen and (max-width: 620px) {
            .divisor {
                width: 0%;
            }

            .info-mail {
                width: 100%;
            }

            h1 {
                font-size: 45px;
            }

            h2 {
                font-size: 20px;
            }

            p {
                font-size: 13px;
            }

            span {
                font-size: 30px;
            }

            .border,
            .emisor {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>

</body>

</html>
<div class="divisor"></div>
<div class="info-mail">
    <h1>ISETA</h1>
    <h2>Verificar cuenta</h2>
    <br>
    <p>Hola, {{ $data['apellido'] }} {{ $data['nombre'] }}</p>
    <p>Estos son tus datos para ingresar a la pagina del iseta:</p>
    <p><b>Mail:</b> {{ $data['email'] }}</p>
    <p><b>Contraseña:</b> {{ $data['password'] }}</p>
    <br>
    <span></span>
    <br>
    <p class="border">Gracias</p>
    <p class="emisor">Iseta inscripciones</p>
</div>
<div class="divisor"></div>
