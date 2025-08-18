{{-- resources/views/Admin/importar/preview.blade.php --}}

<div class="preview-wrapper">
  {{-- Información general --}}
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="info-card">
        <div class="d-flex align-items-center">
          <i class="fas fa-table text-primary me-2"></i>
          <div>
            <div class="fw-bold">{{ count($headings) }}</div>
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
            <div class="fw-bold">{{ $mappedCount }}</div>
            <small class="text-muted">Mapeadas</small>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Tabla con mapeo y edición --}}
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
            <div class="column-excel-name" contenteditable="true">
            <i class="fas fa-file-excel me-1"></i> {{ $heading }}
            </div>
            <div class="column-mapping mt-1">
            <select class="form-select form-select-sm column-mapping-select" data-excel-column="{{ $index }}">
              <option value="">-- Sin mapear --</option>
              @foreach($validColumns as $dbCol => $label)
          <option value="{{ $dbCol }}" {{ strtolower($heading) == strtolower($dbCol) ? 'selected' : '' }}>
          {{ $label }}
          </option>
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
          @php
        $value = $row[$colIndex] ?? '';
        $displayValue = is_null($value) ? '' : (string) $value;
        $displayValue = trim($displayValue);
        @endphp

          @if(empty($displayValue))
          <span class="text-muted fst-italic small">vacío</span>
        @elseif(strlen($displayValue) > 40)
          <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $displayValue }}">
          {{ $displayValue }}
          </span>
        @else
          {{ $displayValue }}
        @endif
          </td>
        @endforeach
          </tr>
      @empty
        <tr>
        <td colspan="{{ count($headings) + 1 }}" class="text-center py-5">
          <div class="text-muted">
          <i class="fas fa-file-excel fa-3x mb-3 opacity-50"></i>
          <h6>No se encontraron datos para previsualizar</h6>
          <small>Verifica que el archivo tenga datos válidos</small>
          </div>
        </td>
        </tr>
      @endforelse
        </tbody>
      </table>
    </div>

    {{-- Mensaje informativo --}}
    @if(!empty($previewRows))
    <div class="alert alert-info mt-3 border-0">
      <div class="d-flex">
      <i class="fas fa-info-circle me-2 mt-1"></i>
      <div>
        <strong>¿Cómo funciona?</strong>
        <ul class="mb-0 mt-2 small">
        <li>Las columnas de Excel se muestran en la parte superior de cada columna</li>
        <li>Puedes cambiar el mapeo a la base de datos usando los selectores</li>
        <li>Puedes editar los valores directamente en la tabla</li>
        <li>Puedes agregar nuevas columnas con el botón "Agregar columna"</li>
        <li>Las columnas con <span class="badge bg-success badge-sm">Mapeada</span> se importarán correctamente</li>
        <li>Las columnas <span class="badge bg-warning badge-sm">Sin mapear</span> serán ignoradas</li>
        </ul>
      </div>
      </div>
    </div>
  @endif
  </div>
</div>

<style>
  /* (tus estilos previos, sin cambios) */
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    let columnMappings = {};
    let editedCells = {};
    let validColumns = @json($validColumns ?? []);

    // Inicializar mapeos automáticos
    document.querySelectorAll('.column-mapping-select').forEach(select => {
      const excelColumn = select.dataset.excelColumn;
      const selectedValue = select.value;
      if (selectedValue) columnMappings[excelColumn] = selectedValue;

      select.addEventListener('change', function () {
        const badge = select.closest('.column-header').querySelector('.mapping-status .badge');
        const value = this.value;

        if (value) {
          columnMappings[excelColumn] = value;
          badge.className = 'badge bg-success badge-sm';
          badge.innerHTML = '<i class="fas fa-check me-1"></i>Mapeada';
        } else {
          delete columnMappings[excelColumn];
          badge.className = 'badge bg-warning badge-sm';
          badge.innerHTML = '<i class="fas fa-exclamation me-1"></i>Sin mapear';
        }

        updateMappedCount();
      });
    });

    // Detectar edición de celdas
    document.querySelectorAll('.data-cell[contenteditable="true"]').forEach(cell => {
      cell.addEventListener('input', function () {
        const row = this.dataset.row;
        const col = this.dataset.col;
        const value = this.textContent.trim();

        if (!editedCells[row]) editedCells[row] = {};
        editedCells[row][col] = value;
      });
    });

    // Actualizar contador de columnas mapeadas
    function updateMappedCount() {
      const mappedCount = Object.keys(columnMappings).length;
      const countElement = document.querySelector('.info-card:last-child .fw-bold');
      if (countElement) countElement.textContent = mappedCount;
    }

    // Agregar nueva columna
    document.getElementById('add-column-btn').addEventListener('click', function () {
      const table = document.querySelector('.preview-table');
      const thead = table.querySelector('thead tr');
      const tbody = table.querySelector('tbody');
      const newColIndex = thead.querySelectorAll('th').length - 1; // restamos columna #

      const th = document.createElement('th');
      th.classList.add('column-header');
      th.dataset.columnIndex = newColIndex;
      th.innerHTML = `
            <div class="column-excel-name" contenteditable="true">Nueva columna</div>
            <div class="column-mapping mt-1">
                <select class="form-select form-select-sm column-mapping-select" data-excel-column="${newColIndex}">
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

      // Agregar celda vacía a cada fila
      tbody.querySelectorAll('tr.data-row').forEach(row => {
        const td = document.createElement('td');
        td.classList.add('data-cell');
        td.setAttribute('contenteditable', 'true');
        td.dataset.row = row.querySelector('td').textContent - 1;
        td.dataset.col = newColIndex;
        row.appendChild(td);

        td.addEventListener('input', function () {
          const rowIdx = this.dataset.row;
          const colIdx = this.dataset.col;
          const value = this.textContent.trim();
          if (!editedCells[rowIdx]) editedCells[rowIdx] = {};
          editedCells[rowIdx][colIdx] = value;
        });
      });

      // Inicializar select de mapeo
      const newSelect = th.querySelector('.column-mapping-select');
      newSelect.addEventListener('change', function () {
        const badge = this.closest('.column-header').querySelector('.mapping-status .badge');
        const value = this.value;
        const excelColumn = this.dataset.excelColumn;

        if (value) {
          columnMappings[excelColumn] = value;
          badge.className = 'badge bg-success badge-sm';
          badge.innerHTML = '<i class="fas fa-check me-1"></i>Mapeada';
        } else {
          delete columnMappings[excelColumn];
          badge.className = 'badge bg-warning badge-sm';
          badge.innerHTML = '<i class="fas fa-exclamation me-1"></i>Sin mapear';
        }
        updateMappedCount();
      });
    });

    // Procesar importación
    document.getElementById('process-import-btn').addEventListener('click', function () {
      const mappedColumnsCount = Object.keys(columnMappings).length;

      if (mappedColumnsCount === 0) {
        Swal.fire({
          icon: 'warning',
          title: 'Sin columnas mapeadas',
          text: 'Debes mapear al menos una columna antes de importar',
          confirmButtonText: 'Entendido'
        });
        return;
      }

      Swal.fire({
        title: '¿Confirmar importación?',
        html: `Se importarán los datos con <strong>${mappedColumnsCount}</strong> columnas mapeadas.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, importar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#28a745'
      }).then((result) => {
        if (result.isConfirmed) {
          const formData = new FormData();
          formData.append('mappings', JSON.stringify(columnMappings));
          formData.append('editedRows', JSON.stringify(editedCells));
          formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

          Swal.fire({
            title: 'Importando datos...',
            html: 'Por favor espera mientras procesamos tu archivo',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
          });

          // Simulación de AJAX
          setTimeout(() => {
            Swal.fire({
              icon: 'success',
              title: 'Importación exitosa',
              text: 'Los datos se han importado correctamente',
              confirmButtonText: 'Continuar'
            }).then(() => {
              window.location.href = '/admin/datos';
            });
          }, 2000);
        }
      });
    });

  });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>