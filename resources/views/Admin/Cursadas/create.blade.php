@extends('Admin.template')

@section('content')
    <div>
        @if ($errors -> any())
            @foreach ($errors->all() as $error)
                <p>{{$error}}</p>
            @endforeach
        @endif

        <select name="carrera" id="carrera_select">
            <option selected >Selecciona una carrera</option>
            @foreach ($carreras as $carrera)
                <option value="{{$carrera->id}}">{{$carrera->nombre}}</option>
            @endforeach
        </select>

       <form method="post" action="{{route('admin.cursadas.store')}}">
        @csrf

        <p>
            materia 
            <select id="asignatura_select" class="asignatura" name="id_asignatura">
                <option value="">selecciona una carrera</option>
            </select>
        </p>
        <p>
            Alumno 
            <select class="alumno" name="id_alumno">
                <option selected>selecciona un alumno</option>
                @foreach($alumnos as $alumno)
                    <option value="{{$alumno->id}}">{{$alumno->nombre.' '.$alumno->apellido}}</option>
                @endforeach
            </select>
        </p>


        <p>Año de cursada <input placeholder="2023" name="anio_cursada"></p>
        <p>Condicion 
            <select name="condicion">
                <option value="1">Libre</option>
                <option selected value="2">Presencial</option>
                <option value="3">Desertor</option>    
                <option value="4">Atraso acadamico</option>
                <option value="5">Otro</option>
            </select>    
        </p>

        <input type="submit" value="Crear">
       </form>
    </div>
    <script src="{{asset('js/obtener-materias.js')}}"></script>
@endsection