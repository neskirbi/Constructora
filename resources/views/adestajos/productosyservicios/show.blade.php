<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Ver Producto/Servicio</title>
</head>
<body>
    <div class="main-container">
        @include('toast.toasts')
        @include('adestajos.sidebar')
        
        <main class="main-content" id="mainContent">
            @include('adestajos.navbar')

            <div class="content-area">
                <div class="container-fluid py-4">
                    <!-- Card principal -->
                    <div class="card shadow-sm mb-4">
                        <!-- Header -->
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('productosyservicios.index') }}" class="btn btn-outline-secondary btn-sm me-3">
                                        <i class="fas fa-arrow-left me-1"></i> Volver
                                    </a>
                                    <h5 class="mb-0">
                                        <i class="fas fa-eye me-2 text-info"></i>
                                        Detalle del Producto/Servicio
                                    </h5>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('productosyservicios.edit', $producto->id) }}" class="btn btn-warning">
                                        <i class="fas fa-edit me-1"></i> Editar
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" onclick="confirmDelete('{{ $producto->id }}', '{{ $producto->clave }}')">
                                        <i class="fas fa-trash-alt me-1"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Body -->
                        <div class="card-body">
                            <!-- Etiqueta de solo lectura -->
                            <div class="alert alert-info bg-info bg-opacity-10 border-0 mb-4 d-flex align-items-center">
                                <i class="fas fa-info-circle fa-lg me-2"></i>
                                <span>Modo de solo lectura - Puedes ver toda la información del producto/servicio</span>
                            </div>
                            
                            <div class="row g-4">
                                <!-- Columna izquierda - Información principal -->
                                <div class="col-md-8">
                                    <div class="row g-3">
                                        <!-- Clave -->
                                        <div class="col-md-4">
                                            <label class="form-label text-muted small mb-1">
                                                <i class="fas fa-tag me-1"></i>Clave
                                            </label>
                                            <div class="form-control bg-light" style="cursor: default;" readonly>
                                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                                    {{ $producto->clave }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Unidades -->
                                        <div class="col-md-4">
                                            <label class="form-label text-muted small mb-1">
                                                <i class="fas fa-ruler me-1"></i>Unidades
                                            </label>
                                            <div class="form-control bg-light fw-semibold" style="cursor: default;" readonly>
                                                {{ $producto->unidades }}
                                            </div>
                                        </div>

                                        <!-- Último costo -->
                                        <div class="col-md-4">
                                            <label class="form-label text-muted small mb-1">
                                                <i class="fas fa-dollar-sign me-1"></i>Último costo
                                            </label>
                                            <div class="form-control bg-light fw-bold text-success" style="cursor: default;" readonly>
                                                ${{ number_format($producto->ult_costo, 2) }}
                                            </div>
                                        </div>

                                        <!-- Descripción -->
                                        <div class="col-12">
                                            <label class="form-label text-muted small mb-1">
                                                <i class="fas fa-align-left me-1"></i>Descripción
                                            </label>
                                            <div class="form-control bg-light" style="min-height: 100px; cursor: default;" readonly>
                                                {{ $producto->descripcion }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Columna derecha - Metadatos -->
                                <div class="col-md-4">
                                    <div class="card bg-light border-0">
                                        <div class="card-body">
                                            <h6 class="fw-bold mb-3">
                                                <i class="fas fa-clock me-2 text-primary"></i>
                                                Información del registro
                                            </h6>
                                            
                                            <div class="mb-3">
                                                <small class="text-muted d-block mb-1">
                                                    <i class="fas fa-fingerprint me-1"></i>ID único
                                                </small>
                                                <code class="small">{{ $producto->id }}</code>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <small class="text-muted d-block mb-1">
                                                    <i class="fas fa-calendar-plus me-1"></i>Fecha de creación
                                                </small>
                                                <div class="fw-semibold">
                                                    {{ $producto->created_at->format('d/m/Y') }}
                                                    <small class="text-muted">({{ $producto->created_at->format('h:i A') }})</small>
                                                </div>
                                                <small class="text-muted">{{ $producto->created_at->diffForHumans() }}</small>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <small class="text-muted d-block mb-1">
                                                    <i class="fas fa-calendar-check me-1"></i>Última actualización
                                                </small>
                                                <div class="fw-semibold">
                                                    {{ $producto->updated_at->format('d/m/Y') }}
                                                    <small class="text-muted">({{ $producto->updated_at->format('h:i A') }})</small>
                                                </div>
                                                @if($producto->created_at != $producto->updated_at)
                                                <small class="text-muted">{{ $producto->updated_at->diffForHumans() }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Footer -->
                        <div class="card-footer bg-white py-3">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('productosyservicios.index') }}" class="btn btn-primary">
                                    <i class="fas fa-arrow-left me-1"></i> Volver al listado
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal de confirmación para eliminar -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-body text-center p-4">
                    <div class="mb-4">
                        <div class="rounded-circle bg-danger bg-opacity-10 d-inline-flex p-3">
                            <i class="fas fa-exclamation-triangle fa-4x text-danger"></i>
                        </div>
                    </div>
                    
                    <h4 class="fw-bold mb-2">¿Confirmar eliminación?</h4>
                    
                    <p class="text-muted mb-4">
                        ¿Estás seguro de que deseas eliminar el producto/servicio <strong id="productoNombre"></strong>?
                    </p>
                    
                    <div class="alert alert-warning bg-warning bg-opacity-10 border-0 mb-4 py-2">
                        <i class="fas fa-info-circle me-1"></i>
                        <small>Esta acción no se puede deshacer.</small>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light flex-fill py-2" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </button>
                        
                        <form id="deleteForm" method="POST" class="flex-fill">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100 py-2">
                                <i class="fas fa-trash-alt me-1"></i> Sí, eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('footer')
    
    <script>
        function confirmDelete(id, nombre) {
            document.getElementById('productoNombre').textContent = nombre;
            
            const form = document.getElementById('deleteForm');
            form.action = '{{ route("productosyservicios.destroy", "") }}/' + id;
            
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }
    </script>
</body>
</html>