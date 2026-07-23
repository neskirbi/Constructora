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
        .drop-zone.has-file {
            border-color: #28a745;
            background: #d4edda;
        }
        .drop-zone.has-file i {
            color: #28a745;
        }
        /* Modal de procesamiento */
        .loading-spinner {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border: 8px solid #f3f3f3;
            border-top: 8px solid #0d6efd;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .loading-box {
            background: white;
            border-radius: 12px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .loading-box h5 {
            color: #495057;
            margin-top: 10px;
        }
        .loading-box p {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 0;
        }
        /* SweetAlert personalizado */
        .swal-custom {
            border-radius: 12px !important;
            padding: 20px !important;
        }
        .swal-custom .swal2-icon {
            border-color: #ffc107 !important;
            color: #ffc107 !important;
        }
        .swal-custom .swal2-title {
            font-size: 1.3rem !important;
        }
        .swal-custom .swal2-content {
            font-size: 0.95rem !important;
        }
        .swal-custom .swal2-confirm {
            background: #0d6efd !important;
            border-radius: 8px !important;
            padding: 10px 30px !important;
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
                                                accept=".xlsx,.xls,.csv" style="display:none;">
                                            <span id="nombreArchivo" class="text-primary fw-bold"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <button type="button" class="btn btn-info w-100" id="btnCargar">
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

    <!-- Modal de procesamiento -->
    <div class="modal fade" id="modalProcesando" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content" style="background: transparent; border: none; box-shadow: none;">
                <div class="loading-box">
                    <div class="loading-spinner"></div>
                    <h5>Procesando archivo...</h5>
                    <p>Esto puede tomar unos segundos</p>
                </div>
            </div>
        </div>
    </div>

    @include('footer')

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Drag and drop
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('archivo_excel');
        const nombreArchivo = document.getElementById('nombreArchivo');
        const form = document.getElementById('excelForm');
        const btnCargar = document.getElementById('btnCargar');

        // Remover required para evitar el error de focus
        fileInput.removeAttribute('required');

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
                dropZone.classList.add('has-file');
            }
        });

        dropZone.addEventListener('click', function() {
            fileInput.click();
        });

        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                nombreArchivo.textContent = this.files[0].name;
                dropZone.classList.add('has-file');
            } else {
                nombreArchivo.textContent = '';
                dropZone.classList.remove('has-file');
            }
        });

        // Validar antes de enviar con SweetAlert
        btnCargar.addEventListener('click', function(e) {
            // Validar que haya un archivo seleccionado
            if (!fileInput.files || fileInput.files.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: '⚠️ No hay archivo seleccionado',
                    text: 'Selecciona un archivo Excel para procesar',
                    confirmButtonColor: '#0d6efd',
                    confirmButtonText: 'Entendido',
                    customClass: {
                        popup: 'swal-custom'
                    }
                });
                return;
            }

            // Confirmar antes de enviar
            Swal.fire({
                icon: 'question',
                title: '¿Procesar archivo?',
                text: 'Se cargarán los productos del Excel: ' + fileInput.files[0].name,
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '✅ Sí, procesar',
                cancelButtonText: '❌ Cancelar',
                customClass: {
                    popup: 'swal-custom'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar modal de procesamiento
                    const modal = new bootstrap.Modal(document.getElementById('modalProcesando'));
                    modal.show();

                    // Deshabilitar el botón para evitar doble clic
                    btnCargar.disabled = true;
                    btnCargar.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Cargando...';

                    // Enviar el formulario
                    form.submit();
                }
            });
        });

        // Si el formulario tiene errores de validación, ocultar el modal
        @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalProcesando'));
            if (modal) {
                modal.hide();
            }
            btnCargar.disabled = false;
            btnCargar.innerHTML = '<i class="fas fa-upload me-1"></i> Cargar';
        });
        @endif
    </script>

    <!-- Si hay errores de sesión, ocultar el modal -->
    @if(session('error') || session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalProcesando'));
            if (modal) {
                modal.hide();
            }
            btnCargar.disabled = false;
            btnCargar.innerHTML = '<i class="fas fa-upload me-1"></i> Cargar';
        });
    </script>
    @endif
</body>
</html>