<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Editar Producto/Servicio</title>
</head>
<body>
    <div class="main-container">
        @include('toast.toasts')
        @if(Guard() == 'adestajos')
            @include('adestajos.sidebar')
        @elseif(Guard() == 'acompras')
            @include('acompras.sidebar')
        @else
            <!-- Opcional: sidebar por defecto o nada -->
        @endif
        
        <main class="main-content" id="mainContent">
            @if(Guard() == 'adestajos')
                @include('adestajos.navbar')
            @elseif(Guard() == 'acompras')
                @include('acompras.navbar')
            @else
                <!-- Opcional: sidebar por defecto o nada -->
            @endif

            <div class="content-area">
                <div class="container-fluid py-4">
                    <!-- Card principal -->
                    <div class="card shadow-sm mb-4">
                        <!-- Header simplificado -->
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <h5 class="mb-0">
                                        <i class="fas fa-edit me-2 text-warning"></i>
                                        Editar Producto/Servicio
                                    </h5>
                                </div>
                                <!-- Eliminados los botones de ver detalles y eliminar -->
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

                                    <!-- Columna derecha - Información del registro (simplificada) -->
                                    <div class="col-md-4">
                                        <div class="card bg-light border-0">
                                            <div class="card-body">
                                                <h6 class="fw-bold mb-3">
                                                    <i class="fas fa-clock me-2 text-primary"></i>
                                                    Información del registro
                                                </h6>
                                                
                                                <div class="mb-3">
                                                    <small class="text-muted d-block mb-2">
                                                        <i class="fas fa-tag me-1"></i>Clave actual:
                                                    </small>
                                                    <p class="small">
                                                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                                            {{ $producto->clave }}
                                                        </span>
                                                    </p>
                                                </div>

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

                                <!-- Footer con botones simplificados -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <hr>
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('productosyservicios.index') }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-times me-1"></i> Cancelar
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

    @include('footer')
    
    <script>
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