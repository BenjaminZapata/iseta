<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
        {{-- <link rel="stylesheet" href="{{asset('css/global.css')}}"> --}}
        <style>

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        .acta_contenedor {
            width: 100%;
        }

        .acta_contenedor th {
            text-align: left;
            width: 50%;
        }

        .acta_info-carrera_bottom td {
            text-align: left;
        }

        .tabla1, .tabla2 {
            width: 100%;
        }

        .tabla1 th {
            text-align: center;
        }

        .tabla2 td {
            min-width: 50px;
        }

        .tabla1, .tabla1 th, .tabla1 td, .tabla2, .tabla2 th, .tabla2 td {
        border: 1px solid black;
        border-collapse: collapse;
        }

        .acta_contenedor {
            /* border: 0.3px solid black; */
            page-break-inside: avoid !important;
        }
        td {
            text-align: center;
        }

        .tabla1 tbody tr td{
            padding: 3px;
        }

        .pos1 {
            width: 60px;
        }

        .pos2 {
            width: 300px;
            text-transform: uppercase;
        }

        .pos3, .pos4, .pos5 {
            width: 60px;
        }

        .pos6 {
            width: 120px;
        }

        </style>
    </head>
    <body>
        <table class="acta_contenedor">
            <thead>
                <tr class="acta_top">
                    <th colspan="2">
                        Provincia de Buenos Aires
                        <span style="margin-left: 100px">{{$condicion}}</span>

                    </th>
                    <th colspan="2">
                        ACTA VOLANTE DE EXÁMENES
                    </th>
                </tr>
                <tr>
                    <th>Dirección General de Cultura y Educación</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td></td>
                <td></td>
                <td>Fecha</td>
                <td>Hora</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>
                    {{ str_replace('-','/',explode(' ',$mesa->fecha)[0]) }}
                </td>
                <td>

                    {{ substr(explode(' ', $mesa->fecha)[1],0,5) }}
                </td>
            </tr>
            <tr>
                <td colspan="4">Carrera: {{$mesa->asignatura->carrera->first()->nombre}}</td>
            </tr>
            <tr class="acta_info-carrera_bottom">
                <td>Año: {{$mesa->asignatura->anio}}</td>
                <td colspan="3">Asignatura: {{$mesa->asignatura->nombre}}</td>
            </tr>
            <tr>
                <td colspan="4">
                    <table class="tabla1">
                        <thead>
                            <tr>
                                <th rowspan="2">N° Orden</th>
                                <th rowspan="2">Nombre y Apellido</th>
                                <th colspan="3">Calificación</th>
                                <th rowspan="2">N° Documento</th>
                            </tr>
                            <tr>
                                <th>Oral </th>
                                <th>Escrito</th>
                                <th>Prom.</th>
                            </tr>
                        </thead>



                        <tbody>
                            @php
                                $actual = 1;
                            @endphp

                            @foreach($alumnos as $alumno)
                                <tr>
                                    <td class="pos1">{{$actual}}</td>
                                    <td class="pos2" style="white-space:nowrap">
                                        {{$alumno->apellido . ', ' . $alumno->nombre}}
                                    </td>
                                    <td class="pos3"></td>
                                    <td class="pos4"></td>
                                    <td class="pos5"></td>
                                    <td class="pos6">{{$alumno->dni}}</td>
                                </tr>
                                @php
                                    $actual++;
                                @endphp
                            @endforeach

                            @php
                                if(count($alumnos)<35){
                                    $restantes = 35-count($alumnos);
                                }
                            @endphp

                        @for ($i = 0; $i < $restantes; $i++)
                            <tr>
                                    <td class="pos1">{{$actual}}</td>
                                    <td class="pos2" style="white-space:nowrap"></td>
                                    <td class="pos3"></td>
                                    <td class="pos4"></td>
                                    <td class="pos5"></td>
                                    <td class="pos6"></td>
                                </tr>
                            @php
                                $actual++;
                            @endphp
                        @endfor

                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="acta_info-profesores" colspan="2">
                    <table>
                        <tbody>
                            <tr>
                                <td>Presidente: {{$mesa->prof_presidente != 0? $mesa->profesor->nombre . ' ' . $mesa->profesor->apellido:'A confirmar'}}</td>
                            </tr>
                            <tr>
                                <td>1° Vocal: {{$mesa->prof_vocal_1 != 0?
                        $mesa->vocal1->nombre . ' ' . $mesa->vocal1->apellido:'Sin definir'}}</td>
                            </tr>
                            <tr>
                                <td>2° Vocal: {{$mesa->prof_vocal_2 != 0?
                $mesa->vocal2->nombre . ' ' . $mesa->vocal2->apellido:'Sin definir'}}</td>
                            </tr>
                            <tr>
                                <td>ISETA, 9 de Julio.</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td class="td_tabla2" colspan="2">
                    <table class="tabla2">
                        <tbody>
                            <tr>
                                <td>Total de alumnos:</td>
                                <td></td>
                                </tr>
                            <tr>
                                <td>Aprobados:</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Aplazados:</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Ausentes:</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>


            </tbody>
        </table>
</body>
</html>
