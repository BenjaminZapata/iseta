<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Analítico</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        .acta_contenedor {
            width: 100%;
        }
        .tabla1 {
            width: 100%;
            border: 1px solid black;
            border-collapse: collapse;
        }
        .tabla1 th, .tabla1 td {
            border: 2.5px solid black;
        }
        .analitico-content, p {
            font-size: 15px;
        }
        .analitico-content span, .pos1, .pos2, .pos3 {
            text-transform: uppercase;
        }
        .pos2, .pos3 {
            text-align: center;
        }
        .tabla1 th {
            text-align: center;
            font-style: italic;
            font-size: 14px;
        }
        .pos1 { width: 450px; }
        .pos2 { width: 100px; }
        .pos3 { width: 130px; }
        .footer-analitico {
            font-size: 12px; 
            text-align: center;
        }
        .pad {
            margin: 0 10px;
        }
    </style>
</head>
<body>

    <table class="acta_contenedor">
        <thead>
            <tr>
                <th><img style="width: 100%" src="{{ $src }}" alt="Logo"></th>
            </tr>
        </thead>
        <tbody>
            <tr class="analitico-content">
                <td colspan="4">
                    Se deja constancia que {{ $alumno->apellido }} {{ $alumno->nombre }} (DNI {{ $alumno->dni }}) ha aprobado las siguientes asignaturas correspondientes al plan de estudio de la carrera {{ $carrera->nombre }}, resolución: {{ $carrera->resolucion }}.
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <table class="tabla1">
                        <thead>
                            <tr>
                                <th class="pos1">PRIMER AÑO</th>
                                <th class="pos2">FECHA</th>
                                <th class="pos3">CALIFICACIÓN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $aniosTexto = ['SEGUNDO AÑO', 'TERCER AÑO', 'CUARTO AÑO', 'QUINTO AÑO', 'SEXTO AÑO', 'SÉPTIMO AÑO'];
                                $anio = 0;
                            @endphp

                            @foreach ($materias as $materia)
                                @if ($materia->anio - 1 == $anio + 1)
                                    @php $anio++; @endphp
                                    <tr>
                                        <th class="pos1">{{ $aniosTexto[$anio - 1] }}</th>
                                        <th class="pos2">FECHA</th>
                                        <th class="pos3">CALIFICACIÓN</th>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="pos1">{{ $materia->nombre }}</td>
                                    <td class="pos2">
                                        @if(isset($materia->examen))
                                            {{ \Carbon\Carbon::parse($materia->examen->fecha)->format('d/m/Y') }}
                                        @else
                                            -----------
                                        @endif
                                    </td>
                                    <td class="pos3">
                                        @if(isset($materia->examen))
                                            {{ $materia->examen->nota }}
                                        @else
                                            -----------
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

    <p>Porcentaje de materias aprobadas: {{ $porcentaje }}</p>

    @php
        $fecha = \Carbon\Carbon::now();
        $meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
        $mes = $meses[$fecha->format('n') - 1];
        $dia = $fecha->format('d');
        $anio = $fecha->format('Y');
    @endphp

    <p>Se extiende la presente en la ciudad de 9 de Julio a los {{ $dia }} días del mes de {{ $mes }} de {{ $anio }}.</p>

    <p class="footer-analitico">
        <span>H. Yrigoyen 931 - Tel/Fax (02317) 4225507/422305 - C.P.: 6500 - 9 de Julio (Bs As) República Argentina</span><br>
        <b>www.iseta.edu.ar</b><br>
        <span class="pad">direccion@iseta.edu.ar</span>
        <span class="pad">preceptoria@iseta.edu.ar</span>
        <span class="pad">regenciadeinvestigacion@iseta.edu.ar</span>
    </p>

</body>
</html>
