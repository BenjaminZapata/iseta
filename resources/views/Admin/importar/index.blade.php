@extends('Admin.template')

@section('content')
  <div class="container py-5">
    <!-- Título principal con gradiente -->
    <div class="text-center mb-5">
    <h1 class="display-5 fw-bold bg-gradient text-primary mb-3">
      <i class="fas fa-file-excel text-success me-2"></i>
      Importación de Excel
    </h1>
    <p class="text-muted fs-6">Carga y previsualiza tus datos antes de importarlos</p>
    </div>

    {{-- Mensajes mejorados --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3 mb-4" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    <strong>¡Éxito!</strong> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-3 mb-4" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <strong>Error:</strong> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Tarjeta principal mejorada -->
    <div class="row justify-content-center">
    <div class="col-lg-8 col-xl-6">
      <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
      <!-- Header de la tarjeta -->
      <div class="card-header bg-gradient-primary text-white py-4 border-0">
        <h5 class="card-title mb-0 text-center">
        <i class="fas fa-upload me-2"></i>
        Configuración de Importación
        </h5>
      </div>

      <!-- Cuerpo de la tarjeta -->
      <div class="card-body p-4 bg-light">
        <form id="import-form" action="{{ route('importar.excel') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Selección de tabla -->
        <div class="mb-4">
          <label for="tabla" class="form-label fw-bold text-dark mb-2">
          <i class="fas fa-database text-primary me-2"></i>
          Tabla de destino
          </label>
          <select name="tabla" id="tabla" class="form-select form-select-lg rounded-3 shadow-sm border-2">
          <option value="">🎯 Selecciona una tabla</option>
          @foreach($tables as $t)
        <option value="{{ $t }}">
        <i class="fas fa-table me-1"></i>{{ ucfirst(str_replace('_', ' ', $t)) }}
        </option>
      @endforeach
          </select>
        </div>

        <!-- Selección de archivo -->
        <div class="mb-4">
          <label for="archivo" class="form-label fw-bold text-dark mb-2">
          <i class="fas fa-file-excel text-success me-2"></i>
          Archivo Excel
          </label>
          <div class="position-relative">
          <input type="file" name="archivo" id="archivo"
            class="form-control form-control-lg rounded-3 shadow-sm border-2" accept=".xlsx,.xls,.csv" required>
          <div class="form-text text-muted mt-2">
            <i class="fas fa-info-circle me-1"></i>
            Formatos soportados: .xlsx, .xls, .csv
          </div>
          </div>
        </div>

        <!-- Botones mejorados -->
        <div class="d-grid gap-3 mt-4">
          <button type="button" id="preview-btn" class="btn btn-outline-info btn-lg rounded-3 shadow-sm border-2">
          <i class="fas fa-eye me-2" id="preview-icon"></i>
          <span id="preview-text">Previsualizar Datos</span>
          <span class="spinner-border spinner-border-sm me-2 d-none" id="preview-spinner"></span>
          </button>

          <button type="submit" class="btn btn-primary btn-lg rounded-3 shadow-sm border-2 bg-gradient">
          <i class="fas fa-download me-2"></i>
          Importar a Base de Datos
          </button>
        </div>
        </form>
      </div>
      </div>
    </div>
    </div>

    <!-- Contenedor de previsualización mejorado -->
    <div id="preview-container" class="mt-5"></div>
  </div>

  <!-- JavaScript inline para evitar conflictos -->
  <script>
  (function() {
    'use strict';

    function initPreview() {
      console.log('Iniciando preview script...');

      const previewBtn = document.getElementById('preview-btn');
      const previewIcon = document.getElementById('preview-icon');
      const previewText = document.getElementById('preview-text');
      const previewSpinner = document.getElementById('preview-spinner');

      if (!previewBtn) {
        console.error('Botón preview no encontrado');
        return;
      }

      console.log('Botón encontrado, agregando listener...');

      previewBtn.onclick = function(e) {
        e.preventDefault();
        e.stopPropagation();

        console.log('¡Click en preview detectado!');

        const tablaSelect = document.getElementById('tabla');
        const archivoInput = document.getElementById('archivo');

        // Validaciones
        if (!tablaSelect.value) {
          alert('Por favor selecciona una tabla de destino');
          return;
        }

        if (!archivoInput.files.length) {
          alert('Por favor selecciona un archivo Excel');
          return;
        }

        console.log('Validaciones OK, enviando request...');

        // Cambiar estado del botón
        if (previewIcon) previewIcon.style.display = 'none';
        if (previewText) previewText.textContent = 'Cargando...';
        if (previewSpinner) previewSpinner.style.display = 'inline-block';
        previewBtn.disabled = true;

        // Crear FormData
        const formData = new FormData();
        formData.append('tabla', tablaSelect.value);
        formData.append('archivo', archivoInput.files[0]);
        formData.append('_token', '{{ csrf_token() }}');

        // Hacer fetch
        fetch('{{ route('importar.preview') }}', {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
          }
        })
        .then(response => {
          console.log('Response status:', response.status);

          if (!response.ok) {
            throw new Error('Error HTTP: ' + response.status);
          }

          const contentType = response.headers.get('content-type');
          if (contentType && contentType.includes('text/html')) {
            throw new Error('El servidor devolvió HTML en lugar de JSON');
          }

          return response.json();
        })
        .then(data => {
          console.log('Data recibida:', data);

          const container = document.getElementById('preview-container');
          if (data.html) {
            container.innerHTML = '<div class="card border-0 shadow-lg rounded-4"><div class="card-header bg-success text-white py-3"><h5 class="mb-0"><i class="fas fa-table me-2"></i>Previsualización de Datos</h5></div><div class="card-body p-0">' + data.html + '</div></div>';

            // Scroll
            container.scrollIntoView({ behavior: 'smooth', block: 'start' });

            alert('¡Previsualización cargada!');
          } else if (data.error) {
            alert('Error: ' + data.error);
          } else {
            alert('No se pudo generar la previsualización');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Error: ' + error.message);
        })
        .finally(() => {
          // Restaurar botón
          if (previewIcon) previewIcon.style.display = 'inline';
          if (previewText) previewText.textContent = 'Previsualizar Datos';
          if (previewSpinner) previewSpinner.style.display = 'none';
          previewBtn.disabled = false;
        });
      };

      console.log('Event listener agregado correctamente');
    }

    // Ejecutar cuando la página esté lista
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initPreview);
    } else {
      initPreview();
    }

    // También después de 500ms por seguridad
    setTimeout(initPreview, 500);
  })();
  </script>

  <!-- Estilos CSS adicionales -->
  <style>
  .bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  }

  .bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  }

  .text-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .card {
    transition: all 0.3s ease;
  }

  .card:hover {
    transform: translateY(-5px);
  }

  .btn {
    transition: all 0.3s ease;
    font-weight: 600;
  }

  .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
  }

  .form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
  }

  .alert {
    backdrop-filter: blur(10px);
    border-left: 4px solid;
  }

  .alert-success {
    border-left-color: #198754;
    background: rgba(25, 135, 84, 0.1);
  }

  .alert-danger {
    border-left-color: #dc3545;
    background: rgba(220, 53, 69, 0.1);
  }

  #preview-container .table {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  }

  #preview-container .table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
  }

  #preview-container .table tbody tr:nth-child(even) {
    background-color: rgba(102, 126, 234, 0.05);
  }

  .spinner-border-sm {
    width: 1rem;
    height: 1rem;
  }
  </style>
@endsection

@section('scripts')
  {{-- Scripts adicionales si los necesitas --}}
@endsection