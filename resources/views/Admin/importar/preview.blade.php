@extends('Admin.template')

@section('content')
<div class="container">
    <h2>Previsualización Editable - {{ $tabla }}</h2>

    <table class="table table-bordered" id="editable-table">
        <thead>
            <tr>
                @foreach($headings as $heading)
                    <th>{{ $heading }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($previewRows as $row)
            <tr>
                @foreach($row as $cell)
                <td contenteditable="true">{{ $cell }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mb-3">
        <button id="add-row" class="btn btn-secondary">Agregar fila</button>
        <button id="add-column" class="btn btn-secondary">Agregar columna</button>
        <button id="save-changes" class="btn btn-success">Guardar cambios</button>
        <button id="import-data" class="btn btn-primary">Importar datos</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('editable-table');

    // --------- FUNCIONES AUX ----------
    function getTableData() {
        const data = [];
        const headings = Array.from(table.tHead.rows[0].cells).map(th => th.textContent.trim());

        for (let i = 0; i < table.tBodies[0].rows.length; i++) {
            const row = {};
            const cells = table.tBodies[0].rows[i].cells;
            for (let j = 0; j < cells.length; j++) {
                row[headings[j]] = cells[j].textContent.trim();
            }
            data.push(row);
        }
        return {headings, data};
    }

    // --------- BOTONES ----------
    // Agregar fila
    document.getElementById('add-row').addEventListener('click', () => {
        const row = table.insertRow(-1);
        for (let i = 0; i < table.rows[0].cells.length; i++) {
            const cell = row.insertCell();
            cell.contentEditable = "true";
        }
    });

    // Agregar columna
    document.getElementById('add-column').addEventListener('click', () => {
        const th = document.createElement('th');
        th.textContent = "Nueva columna";
        table.tHead.rows[0].appendChild(th);

        for (let i = 0; i < table.tBodies[0].rows.length; i++) {
            const cell = table.tBodies[0].rows[i].insertCell();
            cell.contentEditable = "true";
        }
    });

    // Guardar cambios
    document.getElementById('save-changes').addEventListener('click', () => {
        const {data} = getTableData();

        fetch("{{ route('admin.importar.save') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                tabla: "{{ $tabla }}",
                data: data
            })
        })
        .then(res => res.json())
        .then(res => {
            if(res.success) {
                alert("Cambios guardados correctamente.");
            } else {
                alert("Error al guardar: " + (res.message ?? ''));
            }
        })
        .catch(err => alert("Error de conexión: " + err));
    });

    // Importar datos
    document.getElementById('import-data').addEventListener('click', () => {
        const {headings, data} = getTableData();

        fetch("{{ route('admin.importar.processEditedImport') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                tabla: "{{ $tabla }}",
                headings: headings,
                data: data
            })
        })
        .then(res => res.json())
        .then(res => {
            if(res.success) {
                alert("Importación completada. Filas insertadas: " + res.inserted_rows);
            } else {
                alert("Error en la importación: " + (res.message ?? ''));
            }
        })
        .catch(err => alert("Error de conexión: " + err));
    });
});
</script>
@endsection
