<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Nuevo Producto/Servicio</title>
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
                        <!-- Header -->
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-plus-circle me-2 text-primary"></i>
                                    Nuevo Producto/Servicio
                                </h5>
                                <a href="{{ route('productosyservicios.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Volver
                                </a>
                            </div>
                        </div>
                        
                        <!-- Body -->
                        <div class="card-body">
                            <form method="POST" action="{{ route('productosyservicios.store') }}" id="productoForm">
                                @csrf
                                
                                <div class="row g-4">
                                    <!-- Columna izquierda -->
                                    <div class="col-md-8">
                                        <div class="row g-3">
                                            <!-- Clave -->
                                            <div class="col-md-4">
                                                <label class="form-label">
                                                    Clave <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" 
                                                       class="form-control @error('clave') is-invalid @enderror" 
                                                       name="clave" 
                                                       id="clave"
                                                       value="{{ old('clave') }}"
                                                       maxlength="32"
                                                       placeholder="Ej: PROD-001"
                                                       required>
                                                @error('clave')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                                <small class="text-muted">
                                                    Máximo 32 caracteres
                                                </small>
                                            </div>

                                            <!-- Unidades -->
                                            <div class="col-md-4">
                                                <label class="form-label">
                                                    Unidades <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" 
                                                       class="form-control @error('unidades') is-invalid @enderror" 
                                                       name="unidades" 
                                                       id="unidades"
                                                       value="{{ old('unidades') }}"
                                                       maxlength="10"
                                                       placeholder="Ej: PZA, M2, LTS"
                                                       required>
                                                @error('unidades')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                                <small class="text-muted">
                                                    Ej: PZA, M2, LTS, KG
                                                </small>
                                            </div>

                                            <!-- Último costo -->
                                            <div class="col-md-4">
                                                <label class="form-label">
                                                    Último costo <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" 
                                                           class="form-control @error('ult_costo') is-invalid @enderror" 
                                                           name="ult_costo" 
                                                           id="ult_costo"
                                                           value="{{ old('ult_costo') }}"
                                                           step="0.01" 
                                                           min="0"
                                                           placeholder="0.00"
                                                           required>
                                                    @error('ult_costo')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <small class="text-muted">
                                                    Costo unitario actual
                                                </small>
                                            </div>

                                            <!-- Descripción (ocupa toda la fila) -->
                                            <div class="col-12">
                                                <label class="form-label">
                                                    Descripción <span class="text-danger">*</span>
                                                </label>
                                                <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                                          name="descripcion" 
                                                          id="descripcion"
                                                          rows="5"
                                                          placeholder="Describe el producto o servicio..."
                                                          required>{{ old('descripcion') }}</textarea>
                                                @error('descripcion')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Columna derecha - Información adicional / Ayuda -->
                                    <div class="col-md-4">
                                        <div class="card bg-light border-0">
                                            <div class="card-body">
                                                <h6 class="fw-bold mb-3">
                                                    <i class="fas fa-info-circle me-2 text-primary"></i>
                                                    Información importante
                                                </h6>
                                                
                                                <div class="mb-4">
                                                    <small class="text-muted d-block mb-2">
                                                        <i class="fas fa-tag me-1"></i>
                                                        Clave:
                                                    </small>
                                                    <p class="small text-muted mb-0">
                                                        La clave debe ser única y descriptiva. Puedes usar códigos como:
                                                        <span class="d-block mt-2 text-dark">
                                                            <span class="badge bg-light text-dark me-1">MAT-001</span>
                                                            <span class="badge bg-light text-dark me-1">SER-045</span>
                                                            <span class="badge bg-light text-dark">HERR-12</span>
                                                        </span>
                                                    </p>
                                                </div>

                                                <div class="mb-4">
                                                    <small class="text-muted d-block mb-2">
                                                        <i class="fas fa-ruler me-1"></i>
                                                        Unidades:
                                                    </small>
                                                    <p class="small text-muted mb-0">
                                                        Utiliza abreviaturas estándar:<br>
                                                        <span class="text-dark">PZA</span> (Piezas)<br>
                                                        <span class="text-dark">M2</span> (Metros cuadrados)<br>
                                                        <span class="text-dark">M3</span> (Metros cúbicos)<br>
                                                        <span class="text-dark">ML</span> (Metro lineal)<br>
                                                        <span class="text-dark">KG</span> (Kilogramos)<br>
                                                        <span class="text-dark">LTS</span> (Litros)<br>
                                                        <span class="text-dark">HR</span> (Horas)
                                                    </p>
                                                </div>

                                                <div class="mb-3">
                                                    <small class="text-muted d-block mb-2">
                                                        <i class="fas fa-dollar-sign me-1"></i>
                                                        Costo:
                                                    </small>
                                                    <p class="small text-muted mb-0">
                                                        Ingresa el costo unitario actual del producto o servicio. 
                                                        Este valor puede actualizarse después.
                                                    </p>
                                                </div>

                                                <hr>

                                                <div class="alert alert-info py-2 mb-0 small">
                                                    <i class="fas fa-lightbulb me-1"></i>
                                                    <strong>Nota:</strong> Los campos marcados con <span class="text-danger">*</span> son obligatorios.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer con botones de acción -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <hr>
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('productosyservicios.index') }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-times me-1"></i> Cancelar
                                            </a>
                                            <button type="submit" class="btn btn-primary" id="btnGuardar">
                                                <i class="fas fa-save me-1"></i> Guardar producto/servicio
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
            const form = document.getElementById('productoForm');
            const btnGuardar = document.getElementById('btnGuardar');

            form.addEventListener('submit', function() {
                btnGuardar.disabled = true;
                btnGuardar.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Guardando...';
            });

            // Auto-mayúsculas para clave
            document.getElementById('clave').addEventListener('input', function(e) {
                this.value = this.value.toUpperCase();
            });

            // Auto-mayúsculas para unidades
            document.getElementById('unidades').addEventListener('input', function(e) {
                this.value = this.value.toUpperCase();
            });

            // Validar que el costo no sea negativo mientras se escribe
            document.getElementById('ult_costo').addEventListener('input', function(e) {
                if (this.value < 0) {
                    this.value = 0;
                }
            });
        });
    </script>
</body>
</html>