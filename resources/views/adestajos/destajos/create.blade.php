<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Nuevo Destajo</title>
    <style>
        .required-label::after {
            content: " *";
            color: #dc3545;
        }
        .form-card {
            max-width: 1000px;
            margin: 0 auto;
        }
        .input-group-text {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="main-container">
        @include('toast.toasts')
        @include('adestajos.sidebar')
        
        <main class="main-content" id="mainContent">
            @include('adestajos.navbar')

            <div class="content-area">
                <div class="container-fluid py-4">
                    <div class="card shadow-sm form-card">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-plus-circle me-2 text-success"></i>
                                    Nuevo Destajo
                                </h5>
                                <a href="{{ route('destajos.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-arrow-left me-1"></i> Regresar
                                </a>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <form action="{{ route('destajos.store') }}" method="POST" id="destajoForm">
                                @csrf
                                
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="consecutivo" class="form-label required-label">Consecutivo</label>
                                            <input type="number" 
                                                   class="form-control form-control-sm @error('consecutivo') is-invalid @enderror" 
                                                   id="consecutivo" 
                                                   name="consecutivo" 
                                                   value="{{ old('consecutivo', $siguienteConsecutivo) }}"
                                                   min="1"
                                                   required>
                                            @error('consecutivo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Número secuencial del destajo</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="id_contrato" class="form-label required-label">Contrato</label>
                                            <select class="form-select form-select-sm @error('id_contrato') is-invalid @enderror" 
                                                    id="id_contrato" 
                                                    name="id_contrato"
                                                    required>
                                                <option value="">Seleccione un contrato</option>
                                                @foreach($contratos as $contrato)
                                                    <option value="{{ $contrato->id }}" {{ old('id_contrato') == $contrato->id ? 'selected' : '' }}>
                                                        {{ $contrato->contrato_no }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('id_contrato')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="id_proveedor" class="form-label required-label">Proveedor</label>
                                            <select class="form-select form-select-sm @error('id_proveedor') is-invalid @enderror" 
                                                    id="id_proveedor" 
                                                    name="id_proveedor"
                                                    required>
                                                <option value="">Seleccione un proveedor</option>
                                                @foreach($proveedores as $proveedor)
                                                    <option value="{{ $proveedor->id }}" {{ old('id_proveedor') == $proveedor->id ? 'selected' : '' }}>
                                                        {{ $proveedor->clave }} - {{ $proveedor->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('id_proveedor')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="clave_concepto" class="form-label required-label">Clave del Concepto</label>
                                            <input type="text" 
                                                   class="form-control form-control-sm @error('clave_concepto') is-invalid @enderror" 
                                                   id="clave_concepto" 
                                                   name="clave_concepto" 
                                                   value="{{ old('clave_concepto') }}"
                                                   maxlength="50"
                                                   required>
                                            @error('clave_concepto')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="unidad_concepto" class="form-label required-label">Unidad</label>
                                            <select class="form-select form-select-sm @error('unidad_concepto') is-invalid @enderror" 
                                                    id="unidad_concepto" 
                                                    name="unidad_concepto"
                                                    required>
                                                <option value="">Seleccione unidad</option>
                                                @foreach($unidades as $unidad)
                                                    <option value="{{ $unidad }}" {{ old('unidad_concepto') == $unidad ? 'selected' : '' }}>
                                                        {{ $unidad }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('unidad_concepto')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="cantidad" class="form-label required-label">Cantidad</label>
                                            <input type="number" 
                                                   class="form-control form-control-sm @error('cantidad') is-invalid @enderror" 
                                                   id="cantidad" 
                                                   name="cantidad" 
                                                   value="{{ old('cantidad', 1) }}"
                                                   step="0.01"
                                                   min="0.01"
                                                   required>
                                            @error('cantidad')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="descripcion_concepto" class="form-label required-label">Descripción del Concepto</label>
                                            <textarea class="form-control form-control-sm @error('descripcion_concepto') is-invalid @enderror" 
                                                      id="descripcion_concepto" 
                                                      name="descripcion_concepto" 
                                                      rows="3"
                                                      maxlength="500"
                                                      required>{{ old('descripcion_concepto') }}</textarea>
                                            @error('descripcion_concepto')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="costo_unitario_concepto" class="form-label required-label">Costo Unitario</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control @error('costo_unitario_concepto') is-invalid @enderror" 
                                                       id="costo_unitario_concepto" 
                                                       name="costo_unitario_concepto" 
                                                       value="{{ old('costo_unitario_concepto', 0) }}"
                                                       step="0.01"
                                                       min="0"
                                                       required>
                                                @error('costo_unitario_concepto')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="referencia" class="form-label">Referencia</label>
                                            <input type="text" 
                                                   class="form-control form-control-sm @error('referencia') is-invalid @enderror" 
                                                   id="referencia" 
                                                   name="referencia" 
                                                   value="{{ old('referencia') }}"
                                                   maxlength="1500">
                                            @error('referencia')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Folio, número de contrato, ubicación, etc.</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="iva" class="form-label">IVA (%)</label>
                                            <div class="input-group input-group-sm">
                                                <input type="number" 
                                                       class="form-control @error('iva') is-invalid @enderror" 
                                                       id="iva" 
                                                       name="iva" 
                                                       value="{{ old('iva', 16) }}"
                                                       step="0.01"
                                                       min="0">
                                                <span class="input-group-text">%</span>
                                                @error('iva')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card bg-light">
                                            <div class="card-body p-3">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <p class="mb-1 small">Subtotal:</p>
                                                        <p class="mb-1 small">IVA:</p>
                                                        <p class="mb-0 small fw-bold">Total:</p>
                                                    </div>
                                                    <div class="col-6 text-end">
                                                        <p class="mb-1 small" id="subtotal">$0.00</p>
                                                        <p class="mb-1 small" id="ivaCalculado">$0.00</p>
                                                        <p class="mb-0 small fw-bold" id="totalCalculado">$0.00</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('destajos.index') }}" class="btn btn-secondary btn-sm">
                                                Cancelar
                                            </a>
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-save me-1"></i> Guardar Destajo
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
            document.getElementById('clave_concepto').focus();
            
            // Elementos para cálculos
            const cantidadInput = document.getElementById('cantidad');
            const costoUnitarioInput = document.getElementById('costo_unitario_concepto');
            const ivaInput = document.getElementById('iva');
            const subtotalElement = document.getElementById('subtotal');
            const ivaCalculadoElement = document.getElementById('ivaCalculado');
            const totalCalculadoElement = document.getElementById('totalCalculado');
            
            // Función para calcular totales
            function calcularTotales() {
                const cantidad = parseFloat(cantidadInput.value) || 0;
                const costoUnitario = parseFloat(costoUnitarioInput.value) || 0;
                const ivaPorcentaje = parseFloat(ivaInput.value) || 0;
                
                const subtotal = cantidad * costoUnitario;
                const iva = subtotal * (ivaPorcentaje / 100);
                const total = subtotal + iva;
                
                subtotalElement.textContent = '$' + subtotal.toFixed(2);
                ivaCalculadoElement.textContent = '$' + iva.toFixed(2);
                totalCalculadoElement.textContent = '$' + total.toFixed(2);
            }
            
            // Escuchar cambios en inputs
            cantidadInput.addEventListener('input', calcularTotales);
            costoUnitarioInput.addEventListener('input', calcularTotales);
            ivaInput.addEventListener('input', calcularTotales);
            
            // Calcular al cargar
            calcularTotales();
            
            // Formato de números
            [cantidadInput, costoUnitarioInput, ivaInput].forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.value) {
                        this.value = parseFloat(this.value).toFixed(2);
                        calcularTotales();
                    }
                });
            });
            
            // Validación de formulario
            const form = document.getElementById('destajoForm');
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    </script>
</body>
</html>