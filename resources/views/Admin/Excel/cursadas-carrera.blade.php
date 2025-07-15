<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Cursadas - {{ $carrera->nombre }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #444;
            padding: 4px 6px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }

        h2 {
            margin-top: 30px;
        }
    </style>
</head>

<body>

    <h2>Carrera: {{ $carrera->nombre }}</h2>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th colspan="2">Alumno</th>
                <th>DNI</th>
                <th>Género</th>
                <th>Condición</th>
                <th>Año</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($asignaturas as $asignatura)
                <tr>
                    <td colspan="7"><strong>Asignatura: {{ $asignatura->nombre }}</strong></td>
                </tr>

                @php $i = 1; @endphp
                @foreach ($asignatura->cursadas as $cursada)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td colspan="2">{{ $cursada->alumno->apellidoNombre() }}</td>
                        <td>{{ $cursada->alumno->dni }}</td>
                        <td>{{ ucfirst($cursada->alumno->genero ?? 'No especificado') }}</td>
                        <td>{{ $cursada->condicionString() }}</td>
                        <td>{{ $cursada->anio_cursada }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

</body>

</html>