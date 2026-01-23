<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Ingresos</title>
    
    <!-- Estilos personalizados -->
    <style>
        .card-formulario {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        
        .card-header-form {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e0e0e0;
            border-radius: 10px 10px 0 0 !important;
            padding: 1.25rem 1.5rem;
        }
        
        .required-label::after {
            content: " *";
            color: #dc3545;
        }
        
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #002153;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .form-group-custom {
            margin-bottom: 1.25rem;
        }
        
        .form-label-custom {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }
        
        .form-control-custom {
            border-radius: 6px;
            border: 1px solid #ced4da;
            padding: 0.5rem 0.75rem;
            font-size: 0.95rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        
        .form-control-custom:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        .btn-submit {
            padding: 0.625rem 1.5rem;
            font-weight: 500;
            border-radius: 6px;
            min-width: 120px;
        }
        
        .btn-cancel {
            padding: 0.625rem 1.5rem;
            font-weight: 500;
            border-radius: 6px;
            min-width: 120px;
        }
        
        .form-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #426ec1;
        }
        
        .help-text {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }
        
        .input-group-custom {
            border-radius: 6px;
        }
        
        .input-group-custom .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            color: #495057;
        }
        
        .numeric-input {
            text-align: right;
        }
        
        @media (max-width: 768px) {
            .form-section {
                padding: 1rem;
            }
            
            .card-header-form {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        @include('toast.toasts')
        @include('aingresos.sidebar')
        
        <!-- Contenido principal -->
        <main class="main-content" id="mainContent">
            @include('aingresos.navbar')

            <!-- Área de contenido -->
            <div class="content-area">
                <!-- Título y botón -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-1 text-gray-800">Nuevo Contrato</h1>
                        <p class="text-muted mb-0">Complete el formulario para registrar un nuevo contrato</p>
                    </div>
                    <div>
                        <button class="btn btn-outline-secondary" onclick="window.history.back()">
                            <i class="fas fa-arrow-left me-1"></i> Regresar
                        </button>
                    </div>
                </div>
                
                <!-- Formulario -->
                <div class="card card-formulario">
                    <div class="card-header card-header-form">
                        <h5 class="mb-0">
                            <i class="fas fa-file-contract me-2 text-primary"></i>
                            Información del Contrato
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('contratos.store') }}" method="POST">
                            @csrf
                            
                            <!-- Sección 1: Información General -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Información General
                                </h5>

                                
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group-custom">
                                            <label for="obra" class="form-label-custom required-label">
                                                Nombre de la Obra
                                            </label>
                                            <textarea class="form-control form-control-custom" 
                                                      id="obra" 
                                                      name="obra" 
                                                      rows="2"
                                                      placeholder="Descripción detallada de la obra..."
                                                      required>{{ old('obra') }}</textarea>
                                            @error('obra')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="contrato_no" class="form-label-custom required-label">
                                                Número de Contrato
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="contrato_no" 
                                                   name="contrato_no" 
                                                   value="{{ old('contrato_no') }}"
                                                   placeholder="Ej: CTO-2024-001"
                                                   required>
                                            @error('contrato_no')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="contrato_fecha" class="form-label-custom required-label">
                                                Fecha del Contrato
                                            </label>
                                            <input type="date" 
                                                   class="form-control form-control-custom" 
                                                   id="contrato_fecha" 
                                                   name="contrato_fecha" 
                                                   value="{{ old('contrato_fecha') }}"
                                                   required>
                                            @error('contrato_fecha')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="obras" class="form-label-custom">
                                                Referencia Interna
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="obras" 
                                                   name="obras" 
                                                   value="{{ old('obras') }}"
                                                   placeholder="Código interno de la obra">
                                            <div class="help-text">Identificador interno de la obra</div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="duracion" class="form-label-custom">
                                                Duración
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="duracion" 
                                                   name="duracion" 
                                                   value="{{ old('duracion') }}"
                                                   placeholder="Ej: 12 meses, 6 semanas">
                                            <div class="help-text">Tiempo estimado para la obra</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sección 2: Datos del Cliente -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-user-tie me-2"></i>
                                    Datos del Cliente
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="cliente" class="form-label-custom required-label">
                                                Nombre del Cliente
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="cliente" 
                                                   name="cliente" 
                                                   value="{{ old('cliente') }}"
                                                   placeholder="Nombre o razón social del cliente"
                                                   required>
                                            @error('cliente')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="empresa" class="form-label-custom">
                                                Empresa/Organización
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="empresa" 
                                                   name="empresa" 
                                                   value="{{ old('empresa') }}"
                                                   placeholder="Empresa del cliente">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="razon_social" class="form-label-custom">
                                                Razón Social
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="razon_social" 
                                                   name="razon_social" 
                                                   value="{{ old('razon_social') }}"
                                                   placeholder="Razón social completa">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="rfc" class="form-label-custom">
                                                RFC
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="rfc" 
                                                   name="rfc" 
                                                   value="{{ old('rfc') }}"
                                                   placeholder="RFC del cliente"
                                                   maxlength="20">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group-custom">
                                            <label for="representante_legal" class="form-label-custom">
                                                Representante Legal
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="representante_legal" 
                                                   name="representante_legal" 
                                                   value="{{ old('representante_legal') }}"
                                                   placeholder="Nombre del representante legal">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sección 3: Ubicación y Fechas -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    Ubicación y Fechas
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="lugar" class="form-label-custom required-label">
                                                Ubicación/Lugar
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="lugar" 
                                                   name="lugar" 
                                                   value="{{ old('lugar') }}"
                                                   placeholder="Ciudad, Estado donde se realizará la obra"
                                                   required>
                                            @error('lugar')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group-custom">
                                            <label for="inicio_de_obra" class="form-label-custom">
                                                Fecha Inicio Obra
                                            </label>
                                            <input type="date" 
                                                   class="form-control form-control-custom" 
                                                   id="inicio_de_obra" 
                                                   name="inicio_de_obra" 
                                                   value="{{ old('inicio_de_obra') }}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group-custom">
                                            <label for="terminacion_de_obra" class="form-label-custom">
                                                Fecha Terminación Obra
                                            </label>
                                            <input type="date" 
                                                   class="form-control form-control-custom" 
                                                   id="terminacion_de_obra" 
                                                   name="terminacion_de_obra" 
                                                   value="{{ old('terminacion_de_obra') }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="frente" class="form-label-custom">
                                                Frente
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="frente" 
                                                   name="frente" 
                                                   value="{{ old('frente') }}"
                                                   placeholder="Frente de trabajo">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="gerencia" class="form-label-custom">
                                                Gerencia
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="gerencia" 
                                                   name="gerencia" 
                                                   value="{{ old('gerencia') }}"
                                                   placeholder="Gerencia responsable">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sección 4: Montos Económicos -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-dollar-sign me-2"></i>
                                    Montos Económicos
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="importe" class="form-label-custom">
                                                Importe
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="importe" 
                                                       name="importe" 
                                                       value="{{ old('importe') }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="iva" class="form-label-custom">
                                                IVA
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="iva" 
                                                       name="iva" 
                                                       value="{{ old('iva') }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="total" class="form-label-custom">
                                                Total
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="total" 
                                                       name="total" 
                                                       value="{{ old('total') }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="importe_total" class="form-label-custom required-label">
                                                Importe Total
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="importe_total" 
                                                       name="importe_total" 
                                                       value="{{ old('importe_total') }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0"
                                                       required>
                                            </div>
                                            @error('importe_total')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="anticipo" class="form-label-custom">
                                                Anticipo
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="anticipo" 
                                                       name="anticipo" 
                                                       value="{{ old('anticipo') }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="total_total" class="form-label-custom">
                                                Total General
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="total_total" 
                                                       name="total_total" 
                                                       value="{{ old('total_total') }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Campos de convenio (opcionales) -->
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <h6 class="text-muted mb-3">
                                            <i class="fas fa-handshake me-2"></i>
                                            Convenio (opcional)
                                        </h6>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="importe_convenio" class="form-label-custom">
                                                Importe Convenio
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="importe_convenio" 
                                                       name="importe_convenio" 
                                                       value="{{ old('importe_convenio') }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="total_convenio" class="form-label-custom">
                                                Total Convenio
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="total_convenio" 
                                                       name="total_convenio" 
                                                       value="{{ old('total_convenio') }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sección 5: Información Adicional -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-sticky-note me-2"></i>
                                    Información Adicional
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group-custom">
                                            <label for="observaciones" class="form-label-custom">
                                                Observaciones
                                            </label>
                                            <textarea class="form-control form-control-custom" 
                                                      id="observaciones" 
                                                      name="observaciones" 
                                                      rows="3"
                                                      placeholder="Notas adicionales, especificaciones técnicas, condiciones especiales...">{{ old('observaciones') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Información de contacto -->
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="telefono" class="form-label-custom">
                                                Teléfono
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="telefono" 
                                                   name="telefono" 
                                                   value="{{ old('telefono') }}"
                                                   placeholder="Teléfono de contacto">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="mail_facturas" class="form-label-custom">
                                                Email Facturas
                                            </label>
                                            <input type="email" 
                                                   class="form-control form-control-custom" 
                                                   id="mail_facturas" 
                                                   name="mail_facturas" 
                                                   value="{{ old('mail_facturas') }}"
                                                   placeholder="email@ejemplo.com">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Botones de acción -->
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <div>
                                    <small class="text-muted">
                                        Los campos marcados con <span class="text-danger">*</span> son obligatorios
                                    </small>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-cancel btn-outline-secondary" onclick="window.history.back()">
                                        <i class="fas fa-times me-1"></i> Cancelar
                                    </button>
                                    <button type="submit" class="btn btn-submit btn-primary">
                                        <i class="fas fa-save me-1"></i> Guardar Contrato
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @include('footer')
    
    <script>
        // Auto-calcular campos relacionados
        document.addEventListener('DOMContentLoaded', function() {
            // Calcular total si se llena importe e IVA
            const importeInput = document.getElementById('importe');
            const ivaInput = document.getElementById('iva');
            const totalInput = document.getElementById('total');
            
            function calcularTotal() {
                const importe = parseFloat(importeInput.value) || 0;
                const iva = parseFloat(ivaInput.value) || 0;
                const total = importe + iva;
                
                if (!isNaN(total) && total >= 0) {
                    totalInput.value = total.toFixed(2);
                }
            }
            
            if (importeInput && ivaInput && totalInput) {
                importeInput.addEventListener('input', calcularTotal);
                ivaInput.addEventListener('input', calcularTotal);
            }
            
            // Validar fechas
            const inicioObra = document.getElementById('inicio_de_obra');
            const finObra = document.getElementById('terminacion_de_obra');
            
            if (inicioObra && finObra) {
                inicioObra.addEventListener('change', function() {
                    if (this.value && finObra.value && this.value > finObra.value) {
                        alert('La fecha de inicio no puede ser posterior a la fecha de terminación');
                        this.value = '';
                    }
                });
                
                finObra.addEventListener('change', function() {
                    if (this.value && inicioObra.value && this.value < inicioObra.value) {
                        alert('La fecha de terminación no puede ser anterior a la fecha de inicio');
                        this.value = '';
                    }
                });
            }
            
            // Auto-llenar fecha actual en contrato si está vacío
            const contratoFecha = document.getElementById('contrato_fecha');
            if (contratoFecha && !contratoFecha.value) {
                const today = new Date().toISOString().split('T')[0];
                contratoFecha.value = today;
            }
        });
    </script>
</body>
</html>