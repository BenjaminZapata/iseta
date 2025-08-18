<div class="preview-wrapper">
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="info-card">
        <div class="d-flex align-items-center">
          <i class="fas fa-table text-primary me-2"></i>
          <div>
            <div class="fw-bold" id="total-columns">{{ count($headings) }}</div>
            <small class="text-muted">Columnas</small>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="info-card">
        <div class="d-flex align-items-center">
          <i class="fas fa-list-ol text-success me-2"></i>
          <div>
            <div class="fw-bold">{{ count($previewRows) }}</div>
            <small class="text-muted">Filas</small>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="info-card">
        <div class="d-flex align-items-center">
          <i class="fas fa-check-circle text-info me-2"></i>
          <div>
            @php
              $mappedCount = !empty($validColumns) ? count(array_intersect(array_map('strtolower', $headings), array_keys($validColumns))) : 0;
            @endphp
            <div class="fw-bold" id="mapped-count">{{ $mappedCount }}</div>
            <small class="text-muted">Mapeadas</small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="table-container">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">
        <i class="fas fa-table me-2"></i>
        Vista Previa y Mapeo de Datos
      </h5>
      <div class="btn-group btn-group-sm">
        <button class="btn btn-outline-primary" id="process-import-btn">
          <i class="fas fa-upload me-1"></i>Importar Datos
        </button>
        <button class="btn btn-outline-secondary" id="add-column-btn">
          <i class="fas fa-plus me-1"></i>Agregar columna
        </button>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover preview-table">
        <thead class="table-dark">
          <tr>
            <th width="50" class="text-center">#</th>
            @foreach($headings as $index => $heading)
            <th class="column-header" data-column-index="{{ $index }}">
              <div class="column-excel-name" contenteditable="true">{{ $heading }}</div>
              <div class="column-mapping mt-1">
                <select class="form-select form-select-sm column-mapping-select" data-excel-column="{{ $index }}">
                  <option value="">-- Sin mapear --</option>
                  @foreach($validColumns as $dbCol => $label)
                  <option value="{{ $dbCol }}" {{ strtolower($heading) == strtolower($dbCol) ? 'selected' : '' }}>{{ $label }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mapping-status mt-1">
                @if(isset($validColumns[strtolower($heading)]))
                  <span class="badge bg-success badge-sm"><i class="fas fa-check me-1"></i>Mapeada</span>
                @else
                  <span class="badge bg-warning badge-sm"><i class="fas fa-exclamation me-1"></i>Sin mapear</span>
                @endif
              </div>
            </th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          @forelse($previewRows as $rowIndex => $row)
          <tr class="data-row">
            <td class="text-center text-muted fw-bold">{{ $rowIndex + 1 }}</td>
            @foreach($headings as $colIndex => $heading)
            <td class="data-cell" contenteditable="true" data-row="{{ $rowIndex }}" data-col="{{ $colIndex }}">
              {{ $row[$colIndex] ?? '' }}
            </td>
            @endforeach
          </tr>
          @empty
          <tr>
            <td colspan="{{ count($headings) + 1 }}" class="text-center py-5">
              <div class="text-muted">
                <i class="fas fa-file-excel fa-3x mb-3 opacity-50"></i>
                <h6>No se encontraron datos para previsualizar</h6>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let columnMappings = Array.from({length: {{ count($headings) }}}, () => '');
    let editedCells = {};

    function updateMappedCount() {
        document.getElementById('mapped-count').textContent = Object.values(columnMappings).filter(v => v).length;
    }

    // Inicializar selects
    document.querySelectorAll('.column-mapping-select').forEach(select => {
        const idx = select.dataset.excelColumn;
        if(select.value) columnMappings[idx] = select.value;
        select.addEventListener('change', () => {
            columnMappings[idx] = select.value;
            const badge = select.closest('.column-header').querySelector('.mapping-status .badge');
            if(select.value){
                badge.className = 'badge bg-success badge-sm';
                badge.innerHTML = '<i class="fas fa-check me-1"></i>Mapeada';
            } else {
                badge.className = 'badge bg-warning badge-sm';
                badge.innerHTML = '<i class="fas fa-exclamation me-1"></i>Sin mapear';
            }
            updateMappedCount();
        });
    });

    // Detectar edición
    document.querySelectorAll('.data-cell').forEach(cell => {
        cell.addEventListener('input', function(){
            const row = this.dataset.row;
            const col = this.dataset.col;
            if(!editedCells[row]) editedCells[row]={};
            editedCells[row][col] = this.textContent.trim();
        });
    });

    // Agregar columna
    document.getElementById('add-column-btn').addEventListener('click', function(){
        const table = document.querySelector('.preview-table');
        const thead = table.querySelector('thead tr');
        const tbody = table.querySelector('tbody');

        const newIndex = thead.querySelectorAll('th').length - 1;

        const th = document.createElement('th');
        th.classList.add('column-header');
        th.dataset.columnIndex = newIndex;
        th.innerHTML = `
            <div class="column-excel-name" contenteditable="true">Nueva columna</div>
            <div class="column-mapping mt-1">
                <select class="form-select form-select-sm column-mapping-select" data-excel-column="${newIndex}">
                    <option value="">-- Sin mapear --</option>
                    @foreach($validColumns as $dbCol => $label)
                    <option value="{{ $dbCol }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mapping-status mt-1">
                <span class="badge bg-warning badge-sm"><i class="fas fa-exclamation me-1"></i>Sin mapear</span>
            </div>
        `;
        thead.appendChild(th);

        tbody.querySelectorAll('tr').forEach((row, rowIndex) => {
            const td = document.createElement('td');
            td.classList.add('data-cell');
            td.contentEditable = "true";
            td.dataset.row = rowIndex;
            td.dataset.col = newIndex;
            td.addEventListener('input', function(){
                const r = this.dataset.row;
                const c = this.dataset.col;
                if(!editedCells[r]) editedCells[r]={};
                editedCells[r][c] = this.textContent.trim();
            });
            row.appendChild(td);
        });

        columnMappings[newIndex] = '';

        const select = th.querySelector('select');
        select.addEventListener('change', function(){
            columnMappings[newIndex] = this.value;
            const badge = th.querySelector('.mapping-status .badge');
            if(this.value){
                badge.className='badge bg-success badge-sm';
                badge.innerHTML='<i class="fas fa-check me-1"></i>Mapeada';
            } else {
                badge.className='badge bg-warning badge-sm';
                badge.innerHTML='<i class="fas fa-exclamation me-1"></i>Sin mapear';
            }
            updateMappedCount();
        });

        updateMappedCount();
        document.getElementById('total-columns').textContent = thead.querySelectorAll('th').length - 1;
    });

    // Importar
    document.getElementById('process-import-btn').addEventListener('click', function(){
        Swal.fire({
            title: '¿Confirmar importación?',
            icon:'question',
            showCancelButton:true,
            confirmButtonText:'Sí, importar',
            cancelButtonText:'Cancelar'
        }).then((result)=>{
            if(result.isConfirmed){
                fetch("{{ route('imports.processEdited') }}",{
                    method:'POST',
                    headers:{
                        'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type':'application/json'
                    },
                    body:JSON.stringify({
                        tabla: "{{ request()->tabla }}",
                        data: editedCells,
                        mappings: columnMappings
                    })
                }).then(res=>res.json()).then(res=>{
                    if(res.success){
                        Swal.fire('Importación completa','Se importaron '+res.inserted_rows+' filas','success');
                    }else{
                        Swal.fire('Error',res.message,'error');
                    }
                });
            }
        });
    });
});
</script>
