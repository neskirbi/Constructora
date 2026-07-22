<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Nueva Requisición</title>
    <style>
        .drop-zone {
            border: 2px dashed #dee2e6;
            border-radius: 12px;
            padding: 60px 20px;
            text-align: center;
            cursor: pointer;
            background: #f8f9fa;
            transition: all 0.3s;
        }
        .drop-zone:hover, .drop-zone.dragover {
            border-color: #0d6efd;
            background: #e7f1ff;
        }
        .drop-zone i {
            font-size: 4rem;
            color: #6c757d;
        }
        .drop-zone.dragover i {
            color: #0d6efd;
        }
        .drop-zone .text-muted {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="main-container">
        @include('toast.toasts')
        @include('acompras.sidebar')
        
        <main class="main-content" id="mainContent">
            @include('acompras.navbar')

            <div class="content-area">
                <div class="container-fluid py-4">
                    <!-- Título -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4>
                            <i class="fas fa-plus-circle text-primary me-2"></i>
                            Nueva Requisición
                        </h4>
                        <a href="{{ url('requisiciones') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Volver
                        </a>
                    </div>

                    <!-- Zona de carga de Excel -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0">
                                <i class="fas fa-file-excel text-success me-2"></i>
                                Cargar desde Excel
                            </h6>
                        </div>
                        <div class="card-body">
                            <form id="excelForm" action="{{ url('requisiciones/procesar-excel') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="drop-zone" id="dropZone">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <p class="mb-1"><strong>Arrastra tu archivo Excel aquí</strong></p>
                                            <p class="text-muted small mb-2">o haz clic para seleccionarlo</p>
                                            <input type="file" name="archivo_excel" id="archivo_excel" 
                                                accept=".xlsx,.xls,.csv" style="display:none;" required>
                                            <span id="nombreArchivo" class="text-primary fw-bold"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <button type="submit" class="btn btn-info w-100">
                                            <i class="fas fa-upload me-1"></i> Cargar
                                        </button>
                                    </div>
                                </div>
                            </form>
                            
                            <div class="mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Columnas: Clave, Descripción, Unidad, Cantidad
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Mensaje de ayuda -->
                    <div class="card shadow-sm mt-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <i class="fas fa-info-circle text-primary me-2"></i>
                                    <span class="text-muted">Carga un archivo Excel con los productos/servicios a requisitar.</span>
                                </div>
                                <div class="col-md-4 text-end">
                                    <a href="{{ url('productosyservicios') }}" class="btn btn-outline-primary btn-sm" target="_blank">
                                        <i class="fas fa-box me-1"></i> Ver Catálogo
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @include('footer')

    <script>
        // Drag and drop
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('archivo_excel');
        const nombreArchivo = document.getElementById('nombreArchivo');

        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('dragover');
        });

        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
        });

        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                nombreArchivo.textContent = files[0].name;
            }
        });

        dropZone.addEventListener('click', function() {
            fileInput.click();
        });

        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                nombreArchivo.textContent = this.files[0].name;
            }
        });
    </script>
</body>
</html>