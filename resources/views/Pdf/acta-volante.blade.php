<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <title>Acta Volante de Exámenes</title>
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            margin: 30px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 18px;
            margin: 0;
        }

        .header h2 {
            font-size: 14px;
            margin: 5px 0;
        }

        .info-table,
        .main-table,
        .summary-table,
        .professors-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .info-table td {
            padding: 4px;
        }

        .main-table th,
        .main-table td,
        .summary-table td {
            border: 1px solid #444;
            padding: 4px;
            text-align: center;
        }

        .main-table th {
            background-color: #f0f0f0;
        }

        .name-cell {
            text-align: left;
            padding-left: 8px;
            text-transform: uppercase;
        }

        .professors-table td {
            padding: 3px 0;
        }

        .acta-footer {
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Provincia de Buenos Aires</h1>
        <h2>ACTA VOLANTE DE EXÁMENES</h2>
        <div>{{ $condicion }}</div>
        <div>Dirección General de Cultura y Educación</div>
    </div>

    <table class="info-table">
        <tr>
            <td>Fecha: {{ str_replace('-', '/', explode(' ', $mesa->fecha)[0]) }}</td>
            <td>Hora: {{ substr(explode(' ', $mesa->fecha)[1], 0, 5) }}</td>
        </tr>
        <tr>
            <td colspan="2">Carrera: {{ $mesa->asignatura->carrera->first()->nombre }}</td>
        </tr>
        <tr>
            <td>Año: {{ $mesa->asignatura->anio }}</td>
            <td>Asignatura: {{ $mesa->asignatura->nombre }}</td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th rowspan="2">N°</th>
                <th rowspan="2">Nombre y Apellido</th>
                <th colspan="4">Calificación</th>
                <th rowspan="2">DNI</th>
            </tr>
            <tr>
                <th>Oral</th>
                <th>Escrito</th>
                <th>Prom.</th>
                <th>Equi.</th>
            </tr>
        </thead>
        <tbody>
            @php $actual = 1; @endphp
            @foreach($examenes as $examen)
                <tr>
                    <td>{{ $actual }}</td>
                    <td class="name-cell">{{ $examen->alumno->apellido }}, {{ $examen->alumno->nombre }}</td>
                    <td>@if($examen->tipo_final == 2) X @endif</td>
                    <td>@if($examen->tipo_final == 1) X @endif</td>
                    <td>@if($examen->tipo_final == 3) X @endif</td>
                    <td>@if($examen->tipo_final == 4) X @endif</td>
                    <td>{{ $examen->alumno->dni }}</td>
                </tr>
                @php $actual++; @endphp
            @endforeach

            @for ($i = $actual; $i <= 35; $i++)
                <tr>
                    <td>{{ $i }}</td>
                    <td class="name-cell"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endfor
        </tbody>
    </table>

    <div class="acta-footer">
        <table class="professors-table" style="width: 60%; float: left;">
            <tr>
                <td>Presidente:
                    {{ $mesa->prof_presidente != 0 ? $mesa->profesor->nombre . ' ' . $mesa->profesor->apellido : 'A confirmar' }}
                </td>
            </tr>
            <tr>
                <td>1° Vocal:
                    {{ $mesa->prof_vocal_1 != 0 ? $mesa->vocal1->nombre . ' ' . $mesa->vocal1->apellido : 'Sin definir' }}
                </td>
            </tr>
            <tr>
                <td>2° Vocal:
                    {{ $mesa->prof_vocal_2 != 0 ? $mesa->vocal2->nombre . ' ' . $mesa->vocal2->apellido : 'Sin definir' }}
                </td>
            </tr>
            <tr>
                <td>ISETA, 9 de Julio.</td>
            </tr>
        </table>

        <table class="summary-table" style="width: 35%; float: right;">
            <tr>
                <td>Total:</td>
                <td>{{ count($examenes) }}</td>
            </tr>
            <tr>
                <td>Aprobados:</td>
                <td>{{ $examenes->where('aprobado', 1)->count() }}</td>
            </tr>
            <tr>
                <td>Aplazados:</td>
                <td>{{ $examenes->where('aprobado', 2)->count() }}</td>
            </tr>
            <tr>
                <td>Ausentes:</td>
                <td>{{ $examenes->where('aprobado', 3)->count() }}</td>
            </tr>
        </table>
    </div>
</body>

</html>