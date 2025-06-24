<!DOCTYPE html>
<html lang="en">
<head>

    @vite('resources/css/app.css')
    <title>Alumno Regular</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            color: #333;
        }
    </style>
</head>
<body>
    <div>
        <div class="static">
            @php $logo = public_path('img/logo-pba-mobile.svg'); @endphp
            <div class="content flex py-2">
                <img class="ml-1 object-top-left transform scale-x-360 scale-150" src="{{$logo}}" alt="Logo ISETA" style="width: 150px; height: 150px; display: inline-block">
                <div class="item-body "><strong> Dirección General de
                    <br>cultura y Educacion</br>
                    </strong>
                    Gobierno de la Provincia
                    <br>de Buenos Aires</br>
                </div>
            </div>
            <h1><strong> CONSTANCIA DE ALUMNO REGULAR</strong></h1>
            <p>Se deja constancia de que, a la fecha,
                <strong> {{Str::upper($alumno->apellido)}} {{Str::upper($alumno->nombre)}} </strong>
                DNI <strong> {{$alumno->dni}}</strong> del <strong> Instituto Superior Experimental de Tecnologia Alimentaria, </strong>
                de la Carrera <strong> nombre_carrera </strong>, curso <strong> anio_carrera. </strong>
            </p>
            <p>
                <br>
                    A pedido del interesado/a y para ser presentada ante quien corresponda, se extiende la presente constancia en la ciudad de 9 de Julio a los
                    {{$fecha->format('d')}} días del mes {{Str::upper($fecha->translatedFormat('F'))}} de {{$fecha->format('Y')}}.
                </br>
            </p>
        </div>
    </div>
</body>
</html>
