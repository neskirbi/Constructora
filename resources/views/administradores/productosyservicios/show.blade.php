<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Ver Producto/Servicio</title>
</head>
<body>
    <div class="main-container">
        @include('administradores.sidebar')

        <main class="main-content" id="mainContent">
            @include('administradores.navbar')

            <div class="content-area">
                <div class="container-fluid py-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-box me-2 text-primary"></i>
                                    Detalle del Producto/Servicio
                                </h5>
                                <a href="{{ route('aproductosyservicios.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Volver
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row g-4">
                                <!-- Columna izquierda: datos -->
                                <div class="col-md-8">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small">Clave</label>
                                            <p class="fw-bold mb-0">{{ $producto->clave }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small">Unidades</label>
                                            <p class="fw-bold mb-0">{{ $producto->unidades }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small">Precio</label>
                                            <p class="fw-bold mb-0">${{ number_format($producto->precio, 2) }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small">Último costo</label>
                                            <p class="fw-bold mb-0">${{ number_format($producto->ult_costo, 2) }}</p>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label text-muted small">Descripción</label>
                                            <p class="mb-0">{{ $producto->descripcion }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Columna derecha: info -->
                                <div class="col-md-4">
                                    <div class="card bg-light border-0">
                                        <div class="card-body">
                                            <h6 class="fw-bold mb-3">
                                                <i class="fas fa-info-circle me-2 text-primary"></i>
                                                Información
                                            </h6>
                                            <p class="small text-muted mb-2">
                                                <i class="fas fa-calendar me-1"></i>
                                                Creado: {{ $producto->created_at }}
                                            </p>
                                            <p class="small text-muted mb-0">
                                                <i class="fas fa-calendar-check me-1"></i>
                                                Actualizado: {{ $producto->updated_at }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- ===== PDFs ===== -->
                                <div class="col-12">
                                    <hr>
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-file-pdf me-1 text-danger"></i>
                                        Archivos PDF
                                    </label>
                                </div>

                                <!-- PDF 1 -->
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-header bg-light py-2">
                                            <small class="fw-bold">PDF 1</small>
                                        </div>
                                        <div class="card-body text-center">
                                            @if($producto->archivo_pdf_1)
                                                @php $url1 = asset($producto->archivo_pdf_1); @endphp

                                                <!-- Miniatura -->
                                                <div class="mb-3 border rounded overflow-hidden" style="height: 220px; background: #f8f9fa;">
                                                    <iframe src="{{ $url1 }}#toolbar=0&navpanes=0&scrollbar=0"
                                                            style="width: 100%; height: 220px; border: 0;"
                                                            title="Miniatura PDF 1"></iframe>
                                                </div>

                                                <!-- Botones -->
                                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                                    <button type="button"
                                                            class="btn btn-sm btn-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalPdf1">
                                                        <i class="fas fa-expand me-1"></i> Ver grande
                                                    </button>
                                                    <a href="{{ $url1 }}"
                                                       download
                                                       class="btn btn-sm btn-outline-secondary">
                                                        <i class="fas fa-download me-1"></i> Descargar
                                                    </a>
                                                    <a href="{{ $url1 }}"
                                                       target="_blank"
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-external-link-alt me-1"></i> Abrir
                                                    </a>
                                                </div>
                                            @else
                                                <p class="text-muted small mb-0 py-4">Sin archivo</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- PDF 2 -->
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-header bg-light py-2">
                                            <small class="fw-bold">PDF 2</small>
                                        </div>
                                        <div class="card-body text-center">
                                            @if($producto->archivo_pdf_2)
                                                @php $url2 = asset($producto->archivo_pdf_2); @endphp

                                                <!-- Miniatura -->
                                                <div class="mb-3 border rounded overflow-hidden" style="height: 220px; background: #f8f9fa;">
                                                    <iframe src="{{ $url2 }}#toolbar=0&navpanes=0&scrollbar=0"
                                                            style="width: 100%; height: 220px; border: 0;"
                                                            title="Miniatura PDF 2"></iframe>
                                                </div>

                                                <!-- Botones -->
                                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                                    <button type="button"
                                                            class="btn btn-sm btn-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalPdf2">
                                                        <i class="fas fa-expand me-1"></i> Ver grande
                                                    </button>
                                                    <a href="{{ $url2 }}"
                                                       download
                                                       class="btn btn-sm btn-outline-secondary">
                                                        <i class="fas fa-download me-1"></i> Descargar
                                                    </a>
                                                    <a href="{{ $url2 }}"
                                                       target="_blank"
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-external-link-alt me-1"></i> Abrir
                                                    </a>
                                                </div>
                                            @else
                                                <p class="text-muted small mb-0 py-4">Sin archivo</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- ===== FIN PDFs ===== -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- ===== MODALES PDF ===== -->
    @if($producto->archivo_pdf_1)
    <div class="modal fade" id="modalPdf1" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-file-pdf me-1 text-danger"></i> PDF 1
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-0">
                    <iframe src="{{ asset($producto->archivo_pdf_1) }}"
                            style="width: 100%; height: 80vh; border: 0;"
                            title="PDF 1"></iframe>
                </div>
                <div class="modal-footer">
                    <a href="{{ asset($producto->archivo_pdf_1) }}" download class="btn btn-outline-secondary">
                        <i class="fas fa-download me-1"></i> Descargar
                    </a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($producto->archivo_pdf_2)
    <div class="modal fade" id="modalPdf2" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-file-pdf me-1 text-danger"></i> PDF 2
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-0">
                    <iframe src="{{ asset($producto->archivo_pdf_2) }}"
                            style="width: 100%; height: 80vh; border: 0;"
                            title="PDF 2"></iframe>
                </div>
                <div class="modal-footer">
                    <a href="{{ asset($producto->archivo_pdf_2) }}" download class="btn btn-outline-secondary">
                        <i class="fas fa-download me-1"></i> Descargar
                    </a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @include('footer')
</body>
</html>