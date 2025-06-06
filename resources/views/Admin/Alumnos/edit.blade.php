@extends('Admin.template')

@section('content')



<div class="edit-form-container">
    <div class="perfil_one br">
        <div class="perfil__info">

    <?= $form->generate(route('admin.alumnos.update',['alumno'=>$alumno->id]),'put',[
        'Alumno' => [
            $form->text('nombre','Nombre:','label-input-y-75',$alumno),
            $form->text('apellido','Apellido:','label-input-y-75',$alumno),
            $form->text('dni','DNI:','label-input-y-75',$alumno),
            $form->date('fecha_nacimiento','Fecha de nacimiento:','label-input-y-75',$alumno,['default' => $alumno->fecha_nacimiento->format('Y-m-d'),'inputclass'=>'p-1 w-75p']),
            $form->select('estado_civil','Estado civil:','label-input-y-75',$alumno,['Vacio','Soltero','Casado','Divorciado','Viudo','Conyuge','Otro'])
        ],
        'Dirección' => [
            $form->text('ciudad','Ciudad:','label-input-y-75',$alumno),
                $form->text('codigo_postal','Codigo postal:','label-input-y-75',$alumno),
                $form->text('calle','Calle:','label-input-y-75',$alumno),
                $form->text('casa_numero','Altura:','label-input-y-75',$alumno),
                $form->text('dpto','Departamento:','label-input-y-75',$alumno),
                $form->text('piso','Piso:','label-input-y-75',$alumno)
        ],
        'Contacto' => [
            $form->text('email','Email:','label-input-y-75',$alumno),
            $form->text('telefono1','Telefono 1:','label-input-y-75',$alumno),
            $form->text('telefono2','Telefono 2:','label-input-y-75',$alumno),
            $form->text('telefono3','Telefono 3:','label-input-y-75',$alumno)
        ],
        'Academico' => [
            $form->text('titulo_anterior','Titulo anterior:','label-input-y-75',$alumno),
            $form->text('becas','Becas:','label-input-y-75',$alumno)
        ],
        'Otros' => [$form->textarea('observaciones', 'Observaciones:', 'label-input-y-75', $alumno)]
    ]) ?>
    </div>
</div>



    <div class="perfil_one br">

        <div class="perfil__header">
            <h2>Rematriculación manual</h2>
        </div>

        <div class="matricular">
            <form action="{{route('admin.alumno.rematricular',['alumno' => $alumno->id])}}">
                <select name="carrera">
                    @foreach ($carreras as $carrera)
                        <option value="{{$carrera->carrera_id}}">{{$carrera->carrera_nombre}}</option>
                    @endforeach
                </select>
                <div class="upd"><button class="btn_blue"><i class="ti ti-paperclip"></i>Matricular</button></div>
            </form>
            <a href="{{route('admin.inscriptos.create')}}" style="display:block;width:190px"><button class="btn_blue" style="margin-top:-40px">Inscribir a otra carrera</button></a>
        </div>
    </div>



    <div class="table">
        <div class="table__header">
            <h2>Cursadas</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Materia</th>
                    <th>Condicion</th>
                    <th class="center">Estado</th>
                    <th class="center">Acción</th>
                </tr>
            </thead>
            <tbody class="table__body">

            @php
                $carrera_actual = "";
                $anio_actual = "";
            @endphp

            @foreach($cursadas as $cursada)

                {{-- @dd($cursada) --}}
                @if ($carrera_actual != $cursada->carrera)
                    <tr>
                        <td class="center font-600 tit-year2" colspan=5>{{$cursada->carrera}}</td>
                    </tr>
                    @php
                        $carrera_actual = $cursada->carrera;
                        $anio_actual = "";
                    @endphp
                @endif


                @if ($anio_actual != $cursada->anio_asig)
                    <tr>
                        <td class="center font-600 tit-year" colspan=5>
                            Año: {{$cursada->anio_asig+1}}
                        </td>
                    </tr>
                    @php
                            $anio_actual = $cursada->anio_asig
                    @endphp
                @endif


                <tr data-name="MateriaCursada">
                    <td>{{$cursada->asignatura}}</td>
                    <td>{{$cursada->condicionString()}}</td>
                    <td class="center">{{$cursada->aprobado()}}</td>

                    <td class="flex just-center">
                        <a href="{{route('admin.cursadas.edit', ['cursada' => $cursada->id,])}}">
                            <button class="btn_blue"><i class="ti ti-edit"></i>Editar</button>
                        </a>
                    </td>
                </tr>

            @endforeach
            
            </tbody>
        </table>
<<<<<<< HEAD


    </div>
=======
        
        
    </div> 
    
    
    <!-- <?php
    use Illuminate\Support\Str;
        
    // --- Variables de estado y contadores ---
    $carrera_actual_nombre = "";
    $carrera_actual_slug = "";
    $anio_actual_valor = "";
        
    $id_counter_carrera = 0;
    $id_counter_anio = 0; // Este contador será para IDs únicos globales de año
        
    $carrera_item_abierto = false;
    $anio_item_abierto = false;
        
    // --- Inicio del Acordeón Principal de Carreras ---
    echo '<div class="vanilla-accordion" id="accordionCarreras">';
        
    foreach ($cursadas as $index => $cursada) {
        // Asegúrate de que $cursada->carrera y $cursada->anio_asig existen
        $nombre_carrera_actual_iter = $cursada->carrera ?? 'Carrera Desconocida';
        $anio_asignatura_actual_iter = $cursada->anio_asig ?? 0;
    
        // Generar slug para la carrera actual
        $nueva_carrera_slug = Str::slug($nombre_carrera_actual_iter); 
        // $nueva_carrera_slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $nombre_carrera_actual_iter));
    
    
        // =======================
        // CAMBIO DE CARRERA
        // =======================
        if ($carrera_actual_slug != $nueva_carrera_slug) {
            if ($anio_item_abierto) {
                // Cerrar tabla y contenido del año anterior
                echo '</tbody></table>';
                echo '</div></div>'; // Cierra .vanilla-accordion-content y .vanilla-accordion-item del año
                $anio_item_abierto = false;
            }
        
            if ($carrera_item_abierto) {
                // Cerrar contenido de la carrera anterior (que contiene el acordeón de años)
                echo '</div></div>'; // Cierra .vanilla-accordion-content y .vanilla-accordion-item de la carrera
            }
        
            $id_counter_carrera++;
            $carrera_actual_nombre = $nombre_carrera_actual_iter;
            $carrera_actual_slug = $nueva_carrera_slug;
            $anio_actual_valor = ""; // Resetear el año
            $carrera_item_abierto = true;
        
            $id_content_carrera = "contentCarrera" . $id_counter_carrera;
        
            echo '<div class="vanilla-accordion-item">'; // Inicio item carrera
            echo '<button class="vanilla-accordion-header" aria-expanded="false" aria-controls="' . $id_content_carrera . '">';
            echo htmlspecialchars($carrera_actual_nombre);
            echo '<span class="vanilla-accordion-icon">+</span>';
            echo '</button>';
            echo '<div class="vanilla-accordion-content" id="' . $id_content_carrera . '">';
            // Contenedor para el acordeón de Años (anidado)
            echo '<div class="vanilla-accordion" id="accordionAnios' . $id_counter_carrera . '">';
        }
    
        // ==========================================
        // CAMBIO DE AÑO (dentro de la misma carrera)
        // ==========================================
        if ($anio_actual_valor !== $anio_asignatura_actual_iter) { // Usar !== para comparación estricta
            if ($anio_item_abierto) {
                // Cerrar tabla y contenido del año anterior
                echo '</tbody></table>';
                echo '</div></div>'; // Cierra .vanilla-accordion-content y .vanilla-accordion-item del año
            }
        
            $id_counter_anio++; // Contador global para IDs de año únicos
            $anio_actual_valor = $anio_asignatura_actual_iter;
            $anio_item_abierto = true;
        
            $id_content_anio = "contentAnio" . $id_counter_anio;
        
            echo '<div class="vanilla-accordion-item">'; // Inicio item año
            echo '<button class="vanilla-accordion-header" aria-expanded="false" aria-controls="' . $id_content_anio . '">';
            echo 'Año: ' . htmlspecialchars($anio_actual_valor + 1);
            echo '<span class="vanilla-accordion-icon">+</span>';
            echo '</button>';
            echo '<div class="vanilla-accordion-content" id="' . $id_content_anio . '">';
            // Inicio tabla para materias
            echo '<table class="simple-table">';
            echo '<thead><tr><th>Asignatura</th><th>Condición</th><th class="text-center">Aprobado</th><th class="text-center">Acciones</th></tr></thead>';
            echo '<tbody>';
        }
    
        // =======================
        // FILA DE MATERIA CURSADA
        // =======================
        if ($anio_item_abierto) { // Solo imprimir si estamos dentro de un año
            echo '<tr>';
            echo '<td>' . htmlspecialchars($cursada->asignatura ?? 'N/A') . '</td>';
            // Asumiendo que condicionString y aprobado son métodos o existen como propiedades.
            // Necesitarás adaptarlo si son métodos: $cursada->condicionString()
            echo '<td>' . htmlspecialchars(is_callable([$cursada, 'condicionString']) ? $cursada->condicionString() : ($cursada->condicion ?? 'N/A')) . '</td>';
            echo '<td class="text-center">' . htmlspecialchars(is_callable([$cursada, 'aprobado']) ? $cursada->aprobado() : ($cursada->aprobado ?? 'N/A')) . '</td>';
            echo '<td class="text-center">';
            // Asegúrate de que route() está disponible o genera el enlace de otra manera
            $url_editar = route('admin.cursadas.edit', ['cursada' => $cursada->id]); 
            // $url_editar = 'editar_cursada.php?id=' . ($cursada->id ?? ''); // Ejemplo sin Laravel
            echo '<a href="' . htmlspecialchars($url_editar) . '" class="btn-edit">Editar</a>';
            echo '</td>';
            echo '</tr>';
        }
    } // Fin del bucle @foreach
    
    // ====================================================
    // Cerrar las etiquetas restantes después del bucle
    // ====================================================
    if ($anio_item_abierto) {
        echo '</tbody></table>';
        echo '</div></div>'; // Cierra .vanilla-accordion-content y .vanilla-accordion-item del último año
    }
    
    if ($carrera_item_abierto) {
        echo '</div>'; // Cierra el contenedor .vanilla-accordion de los años
        echo '</div></div>'; // Cierra .vanilla-accordion-content y .vanilla-accordion-item de la última carrera
    }
    
    echo '</div>'; // Cierra #accordionCarreras
?> -->
>>>>>>> master

    <div class="table">
        <div class="table__header">
            <h2>Examenes</h2>
            <p>Importante: algunos examanes de alumnos mas antiguos podrian no tener datos sobre las mesas.
            </p>
        </div>
            <table class="table__body">
                <thead>
                    <tr>
                        <th>Materia</th>
                        <th>Fecha</th>
                        <th>Nota</th>
                        <th class="center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $carrera_actual = "";
                        $anio_actual = "";
                    @endphp

                    @foreach($examenes as $examen)

                        @if ($carrera_actual != $examen->carrera)
                            <tr>
                                <td class="center font-600 tit-year2" colspan=4>{{$examen->carrera}}</td>
                            </tr>
                            @php
                                $carrera_actual = $examen->carrera;
                                $anio_actual = "";
                            @endphp
                        @endif


                        @if ($anio_actual != $examen->anio_asig)
                            <tr>
                                <td class="center font-600 tit-year" colspan=4>
                                    Año: {{$examen->anio_asig+1}}
                                </td>
                            </tr>
                            @php
                                    $anio_actual = $examen->anio_asig
                            @endphp
                        @endif

                        <tr>
                            <td>{{$examen->asignatura}}</td>

                            <td>

                                {{$formatoFecha->dma($examen->fecha())}}
                            </td>

                            <td>

                            @if ($examen->aprobado==3)
                                Ausente
                            @elseif($examen->nota<=0)
                                Sin nota
                            @else
                                {{$examen->nota}}
                            @endif
                            </td>
                            <td class="flex just-center"><a href="{{route('admin.examenes.edit', ['examen' => $examen->id,])}}"><button class="btn_blue"><i class="ti ti-edit"></i>Editar</button></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

    </div>
    @if ($alumno->verificado == 0)
        <a href="{{route('admin.alumnos.verificar', ['alumno' => $alumno->id])}}">Verificar alumno</a>
    @endif
</div>

@endsection
