<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Editar Producto/Servicio</title>
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
                                        <i class="fas fa-edit me-2 text-warning"></i>
                                        Editar Producto/Servicio
                                    </h5>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('productosyservicios.show', $producto->id) }}" class="btn btn-outline-info">
                                        <i class="fas fa-eye me-1"></i> Ver detalles
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" onclick="confirmDelete('{{ $producto->id }}', '{{ $producto->clave }}')">
                                        <i class="fas fa-trash-alt me-1"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Body -->
                        <div class="card-body">
                            <!-- Etiqueta de edición -->
                            <div class="alert alert-warning bg-warning bg-opacity-10 border-0 mb-4 d-flex align-items-center">
                                <i class="fas fa-pencil-alt fa-lg me-2"></i>
                                <span>Modo de edición - Modifica los campos que necesites y guarda los cambios</span>
                            </div>
                            
                            <form method="POST" action="{{ route('productosyservicios.update', $producto->id) }}" id="productoForm">
                                @csrf
                                @method('PUT')
                                
                                <div class="row g-4">
                                    <!-- Columna izquierda - Formulario -->
                                    <div class="col-md-8">
                                        <div class="row g-3">
                                            <!-- Clave -->
                                            <div class="col-md-4">
                                                <label class="form-label">
                                                    <i class="fas fa-tag me-1 text-primary"></i>
                                                    Clave <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" 
                                                       class="form-control @error('clave') is-invalid @enderror" 
                                                       name="clave" 
                                                       value="{{ old('clave', $producto->clave) }}"
                                                       maxlength="32"
                                                       required>
                                                @error('clave')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="text-muted">Máximo 32 caracteres</small>
                                            </div>

                                            <!-- Unidades -->
                                            <div class="col-md-4">
                                                <label class="form-label">
                                                    <i class="fas fa-ruler me-1 text-primary"></i>
                                                    Unidades <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" 
                                                       class="form-control @error('unidades') is-invalid @enderror" 
                                                       name="unidades" 
                                                       value="{{ old('unidades', $producto->unidades) }}"
                                                       maxlength="10"
                                                       required>
                                                @error('unidades')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="text-muted">Ej: PZA, M2, LTS</small>
                                            </div>

                                            <!-- Último costo -->
                                            <div class="col-md-4">
                                                <label class="form-label">
                                                    <i class="fas fa-dollar-sign me-1 text-primary"></i>
                                                    Último costo <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" 
                                                           class="form-control @error('ult_costo') is-invalid @enderror" 
                                                           name="ult_costo" 
                                                           value="{{ old('ult_costo', $producto->ult_costo) }}"
                                                           step="0.01" 
                                                           min="0"
                                                           required>
                                                    @error('ult_costo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Descripción -->
                                            <div class="col-12">
                                                <label class="form-label">
                                                    <i class="fas fa-align-left me-1 text-primary"></i>
                                                    Descripción <span class="text-danger">*</span>
                                                </label>
                                                <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                                          name="descripcion" 
                                                          rows="5"
                                                          required>{{ old('descripcion', $producto->descripcion) }}</textarea>
                                                @error('descripcion')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Columna derecha - Información útil -->
                                    <div class="col-md-4">
                                        <div class="card bg-light border-0">
                                            <div class="card-body">
                                                <h6 class="fw-bold mb-3">
                                                    <i class="fas fa-lightbulb me-2 text-warning"></i>
                                                    Consejos de edición
                                                </h6>
                                                
                                                <div class="mb-3">
                                                    <small class="text-muted d-block mb-2">
                                                        <i class="fas fa-tag me-1"></i>Clave única:
                                                    </small>
                                                    <p class="small">La clave actual es <span class="badge bg-primary">{{ $producto->clave }}</span></p>
                                                </div>

                                                <div class="mb-3">
                                                    <small class="text-muted d-block mb-2">
                                                        <i class="fas fa-ruler me-1"></i>Unidades:
                                                    </small>
                                                    <p class="small">Actual: <strong>{{ $producto->unidades }}</strong></p>
                                                </div>

                                                <hr>

                                                <div class="mb-3">
                                                    <small class="text-muted d-block mb-2">
                                                        <i class="fas fa-history me-1"></i>Última modificación:
                                                    </small>
                                                    <p class="small mb-0">
                                                        {{ $producto->updated_at->format('d/m/Y h:i A') }}
                                                        <br>
                                                        <span class="text-muted">{{ $producto->updated_at->diffForHumans() }}</span>
                                                    </p>
                                                </div>

                                                <hr>

                                                <div class="alert alert-info py-2 mb-0 small">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Los campos marcados con <span class="text-danger">*</span> son obligatorios
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer con botones -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <hr>
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('productosyservicios.index') }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-times me-1"></i> Cancelar
                                            </a>
                                            <a href="{{ route('productosyservicios.show', $producto->id) }}" class="btn btn-outline-info">
                                                <i class="fas fa-eye me-1"></i> Ver sin guardar
                                            </a>
                                            <button type="submit" class="btn btn-warning" id="btnGuardar">
                                                <i class="fas fa-save me-1"></i> Actualizar cambios
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
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

        document.addEventListener('DOMContentLoaded', function() {
            const btnGuardar = document.getElementById('btnGuardar');
            const form = document.getElementById('productoForm');

            form.addEventListener('submit', function() {
                btnGuardar.disabled = true;
                btnGuardar.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Actualizando...';
            });

            // Auto-mayúsculas para clave
            document.querySelector('input[name="clave"]').addEventListener('input', function(e) {
                this.value = this.value.toUpperCase();
            });

            // Auto-mayúsculas para unidades
            document.querySelector('input[name="unidades"]').addEventListener('input', function(e) {
                this.value = this.value.toUpperCase();
            });
        });
    </script>
</body>
</html>