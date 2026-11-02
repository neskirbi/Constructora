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
        @endif
        
        <main class="main-content" id="mainContent">
            @if(Guard() == 'adestajos')
                @include('adestajos.navbar')
            @elseif(Guard() == 'acompras')
                @include('acompras.navbar')
            @else
            @endif

            <div class="content-area">
                <div class="container-fluid py-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-edit me-2 text-warning"></i>
                                    Editar Producto/Servicio
                                </h5>
                                <a href="{{ route('productosyservicios.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Volver
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <form method="POST" 
                                  action="{{ route('productosyservicios.update', $producto->id) }}" 
                                  id="productoForm"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row g-4">
                                    <div class="col-md-8">
                                        <div class="row g-3">
                                            <!-- Clave -->
                                            <div class="col-md-4">
                                                <label class="form-label">Clave <span class="text-danger">*</span></label>
                                                <input type="text" 
                                                       class="form-control @error('clave') is-invalid @enderror" 
                                                       name="clave" 
                                                       id="clave"
                                                       value="{{ old('clave', $producto->clave) }}"
                                                       maxlength="32"
                                                       required>
                                                @error('clave') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>

                                            <!-- Unidades -->
                                            <div class="col-md-4">
                                                <label class="form-label">Unidades <span class="text-danger">*</span></label>
                                                <input type="text" 
                                                       class="form-control @error('unidades') is-invalid @enderror" 
                                                       name="unidades" 
                                                       id="unidades"
                                                       value="{{ old('unidades', $producto->unidades) }}"
                                                       maxlength="10"
                                                       required>
                                                @error('unidades') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>

                                            <!-- Precio -->
                                            <div class="col-md-4">
                                                <label class="form-label">Precio <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" 
                                                           class="form-control @error('precio') is-invalid @enderror" 
                                                           name="precio" 
                                                           id="precio"
                                                           value="{{ old('precio', $producto->precio) }}"
                                                           step="0.01"
                                                           min="0"
                                                           required>
                                                    @error('precio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                            </div>

                                            <!-- Descripción -->
                                            <div class="col-12">
                                                <label class="form-label">Descripción <span class="text-danger">*</span></label>
                                                <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                                          name="descripcion" 
                                                          id="descripcion"
                                                          rows="5"
                                                          required>{{ old('descripcion', $producto->descripcion) }}</textarea>
                                                @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>

                                            <!-- ===== Proveedor / Contrato / Estatus ===== -->
                                            <div class="col-12">
                                                <hr class="my-2">
                                                <label class="form-label fw-semibold">
                                                    <i class="fas fa-briefcase me-1 text-primary"></i>
                                                    Proveedor y Contrato
                                                </label>
                                            </div>

                                            <!-- Proveedor -->
                                            <div class="col-md-6">
                                                <label for="id_proveedor" class="form-label">Proveedor</label>
                                                <select class="form-select @error('id_proveedor') is-invalid @enderror"
                                                        id="id_proveedor"
                                                        name="id_proveedor"
                                                        style="width: 100%;">
                                                    <option value="">Seleccione un proveedor</option>
                                                    @foreach($proveedores as $p)
                                                        <option value="{{ $p->id }}" {{ old('id_proveedor', $producto->id_proveedor) == $p->id ? 'selected' : '' }}>
                                                            {{ $p->clave }} - {{ $p->nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_proveedor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>

                                            <!-- Contrato -->
                                            <div class="col-md-6">
                                                <label for="id_contrato" class="form-label">Contrato</label>
                                                <select class="form-select @error('id_contrato') is-invalid @enderror"
                                                        id="id_contrato"
                                                        name="id_contrato"
                                                        style="width: 100%;">
                                                    <option value="">Seleccione un contrato</option>
                                                    @foreach($contratos as $c)
                                                        <option value="{{ $c->id }}" {{ old('id_contrato', $producto->id_contrato) == $c->id ? 'selected' : '' }}>
                                                            {{ $c->consecutivo }} - {{ $c->refinterna ?? '' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('id_contrato') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>

                                            <!-- Estatus -->
                                            <div class="col-12">
                                                <label class="form-label d-block">Estatus de ejecución</label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio"
                                                           name="estatus_ejecucion" id="estatus_por_ejecutar" value="por_ejecutar"
                                                           {{ old('estatus_ejecucion', $producto->estatus_ejecucion ?? 'por_ejecutar') == 'por_ejecutar' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="estatus_por_ejecutar">Por ejecutar</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio"
                                                           name="estatus_ejecucion" id="estatus_ejecutado" value="ejecutado"
                                                           {{ old('estatus_ejecucion', $producto->estatus_ejecucion) == 'ejecutado' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="estatus_ejecutado">Ejecutado</label>
                                                </div>
                                                @error('estatus_ejecucion') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                            </div>
                                            <!-- ===== FIN Proveedor / Contrato / Estatus ===== -->

                                            <!-- ===== PDFs ===== -->
                                            <div class="col-12">
                                                <hr class="my-2">
                                                <label class="form-label fw-semibold">
                                                    <i class="fas fa-file-pdf me-1 text-danger"></i>
                                                    Archivos PDF
                                                    <span class="text-muted small">(opcionales — sube uno nuevo para reemplazar el actual)</span>
                                                </label>
                                            </div>

                                            <!-- PDF 1 -->
                                            <div class="col-md-6">
                                                <label class="form-label">Cédula de precio "incluir cotización"</label>
                                                <input type="file" 
                                                       class="form-control @error('archivo_pdf_1') is-invalid @enderror" 
                                                       name="archivo_pdf_1" 
                                                       id="archivo_pdf_1"
                                                       accept="application/pdf,.pdf">
                                                @error('archivo_pdf_1') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                <small class="text-muted d-block" id="nombre_pdf_1"></small>

                                                @if($producto->archivo_pdf_1)
                                                    <div class="mt-2">
                                                        <a href="{{ asset($producto->archivo_pdf_1) }}" 
                                                           target="_blank" 
                                                           class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-file-pdf me-1"></i> Ver PDF actual
                                                        </a>
                                                    </div>
                                                @else
                                                    <small class="text-muted">Sin archivo actual</small>
                                                @endif
                                            </div>

                                            <!-- PDF 2 -->
                                            <div class="col-md-6">
                                                <label class="form-label">Análisis de precios unitarios</label>
                                                <input type="file" 
                                                       class="form-control @error('archivo_pdf_2') is-invalid @enderror" 
                                                       name="archivo_pdf_2" 
                                                       id="archivo_pdf_2"
                                                       accept="application/pdf,.pdf">
                                                @error('archivo_pdf_2') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                <small class="text-muted d-block" id="nombre_pdf_2"></small>

                                                @if($producto->archivo_pdf_2)
                                                    <div class="mt-2">
                                                        <a href="{{ asset($producto->archivo_pdf_2) }}" 
                                                           target="_blank" 
                                                           class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-file-pdf me-1"></i> Ver PDF actual
                                                        </a>
                                                    </div>
                                                @else
                                                    <small class="text-muted">Sin archivo actual</small>
                                                @endif
                                            </div>
                                            <!-- ===== FIN PDFs ===== -->
                                        </div>
                                    </div>

                                    <!-- Columna derecha -->
                                    <div class="col-md-4">
                                        <div class="card bg-light border-0">
                                            <div class="card-body">
                                                <h6 class="fw-bold mb-3">
                                                    <i class="fas fa-info-circle me-2 text-primary"></i>
                                                    Información
                                                </h6>
                                                <p class="small text-muted mb-0">
                                                    Si no seleccionas un nuevo PDF, se conservará el actual.
                                                    Si seleccionas uno nuevo, el anterior se eliminará.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <hr>
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('productosyservicios.index') }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-times me-1"></i> Cancelar
                                            </a>
                                            <button type="submit" class="btn btn-primary" id="btnGuardar">
                                                <i class="fas fa-save me-1"></i> Actualizar
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

            document.getElementById('clave').addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });

            document.getElementById('unidades').addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });

            document.getElementById('precio').addEventListener('input', function() {
                if (this.value < 0) this.value = 0;
            });

            function mostrarNombrePdf(inputId, labelId, maxMB) {
                const input = document.getElementById(inputId);
                const label = document.getElementById(labelId);
                if (!input || !label) return;

                input.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const file = this.files[0];
                        const sizeMB = file.size / (1024 * 1024);

                        if (sizeMB > maxMB) {
                            alert('El archivo "' + file.name + '" supera el tamaño máximo de ' + maxMB + ' MB.');
                            this.value = '';
                            label.textContent = '';
                            return;
                        }
                        label.textContent = 'Nuevo: ' + file.name;
                    } else {
                        label.textContent = '';
                    }
                });
            }

            mostrarNombrePdf('archivo_pdf_1', 'nombre_pdf_1', 10);
            mostrarNombrePdf('archivo_pdf_2', 'nombre_pdf_2', 10);
        });

        // Select2 para proveedor y contrato (requiere jQuery + select2 ya cargados)
        $(document).ready(function() {
            $('#id_proveedor').select2({
                placeholder: 'Seleccione un proveedor',
                allowClear: true,
                width: '100%'
            });

            $('#id_contrato').select2({
                placeholder: 'Seleccione un contrato',
                allowClear: true,
                width: '100%'
            });
        });
    </script>
</body>
</html>