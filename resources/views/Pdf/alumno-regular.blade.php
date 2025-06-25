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
            <div class="inline-flex justify-between items-center w-full py-2">
                <div class="flex">
                    <img class="pl-2 ml-8 transform scale-x-950 scale-150" src="{{$logo}}" alt="Logo ISETA" style="width: 100px; height: 150px">
                    <p class="text-left pl-3 py-2"><strong> Dirección General de
                        <br>cultura y Educacion</br>
                        </strong>
                        Gobierno de la Provincia
                        <br>de Buenos Aires</br>
                        <br>Subsecretaría de Educación</br>
                    </p>
                </div>
                <div class="flex flex-col items-center pr-10 pt-2">
                    @php $logopdf = public_path('img/iseta-pdf.png'); @endphp
                    <img class="" src="{{$logopdf}}" alt="Logo ISETA" style="width: 320px; height: 100px">
                    <p>
                        <strong> INSTITUTO SUPERIOR EXPERIMENTAL DE <br>TECNOLOGIA ALIMENTARIA</br>
                            <br>
                                <span class="underline underline-offset-8">DIRECCIÓN DE EDUCACIÓN SUPERIOR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                            </br>
                        </strong>
                    </p>
                </div>
            </div>
            <div class="px-3 pt-4">
                <h1><strong> CONSTANCIA DE ALUMNO REGULAR</strong></h1>
                <p class="pt-3">Se deja constancia de que, a la fecha,
                    <strong> {{Str::upper($alumno->apellido)}} {{Str::upper($alumno->nombre)}} </strong>
                    DNI <strong> {{$alumno->dni}} es alumno regular </strong> del <strong> Instituto Superior Experimental de Tecnologia Alimentaria, </strong>
                    de la Carrera <strong> nombre_carrera </strong>, curso <strong> anio_carrera. </strong>
                </p>
                <p>
                    <br>
                        A pedido del interesado/a y para ser presentada ante quien corresponda, se extiende la presente constancia en la ciudad de 9 de Julio a los
                        {{$fecha->format('d')}} días del mes {{Str::upper($fecha->translatedFormat('F'))}} de {{$fecha->format('Y')}}.
                    </br>
                </p>
            </div>
            <div class="inline-flex justify-between items-center w-full py-30 px-20">
                <p class="text-center">
                    Sello del establecimiento
                </p>
                <p class="text-center">
                    Firma y sello aclaratoria del <br>Director / Secretario</br>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
