<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Editar Contrato</title>
    
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
        
        /* Estilos para el mapa */
        .map-container {
            height: 300px;
            border-radius: 8px;
            border: 1px solid #ced4da;
            margin-bottom: 10px;
        }
        
        .map-coordinates {
            font-size: 0.8rem;
            color: #666;
            margin-top: 5px;
        }
        
        .coordinates-display {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 8px 12px;
            font-family: monospace;
            margin-top: 5px;
        }
        
        @media (max-width: 768px) {
            .form-section {
                padding: 1rem;
            }
            
            .card-header-form {
                padding: 1rem;
            }
            
            .map-container {
                height: 250px;
            }
        }
    </style>
    
    <!-- Incluir Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMap" async defer></script>
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
                        <h1 class="h3 mb-1 text-gray-800">Editar Contrato</h1>
                        <p class="text-muted mb-0">Editando: {{ $contrato->contrato_no }}</p>
                    </div>
                    <div>
                        <a href="{{ route('contratos.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Regresar
                        </a>
                    </div>
                </div>
                
                <!-- Formulario de edición -->
                <div class="card card-formulario">
                    <div class="card-header card-header-form">
                        <h5 class="mb-0">
                            <i class="fas fa-edit me-2 text-warning"></i>
                            Editar Información del Contrato
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('contratos.update', $contrato->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <!-- Sección 1: Información General -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Información General
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group-custom">
                                            <label for="obra" class="form-label-custom">
                                                Nombre de la Obra
                                            </label>
                                            <textarea class="form-control form-control-custom" 
                                                      id="obra" 
                                                      name="obra" 
                                                      rows="2"
                                                      placeholder="Descripción detallada de la obra...">{{ old('obra', $contrato->obra) }}</textarea>
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
                                                   value="{{ old('contrato_no', $contrato->contrato_no) }}"
                                                   placeholder="Ej: CTO-2024-001"
                                                   required>
                                            @error('contrato_no')
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
                                                   value="{{ old('empresa', $contrato->empresa) }}"
                                                   placeholder="Empresa del cliente">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="frente" class="form-label-custom">
                                                Frente de Trabajo
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="frente" 
                                                   name="frente" 
                                                   value="{{ old('frente', $contrato->frente) }}"
                                                   placeholder="Descripción del frente de trabajo">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="gerencia" class="form-label-custom">
                                                Gerencia Responsable
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="gerencia" 
                                                   name="gerencia" 
                                                   value="{{ old('gerencia', $contrato->gerencia) }}"
                                                   placeholder="Gerencia responsable">
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
                                            <label for="cliente" class="form-label-custom">
                                                Nombre del Cliente
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="cliente" 
                                                   name="cliente" 
                                                   value="{{ old('cliente', $contrato->cliente) }}"
                                                   placeholder="Nombre o razón social del cliente">
                                            @error('cliente')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="lugar" class="form-label-custom">
                                                Lugar del Proyecto
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="lugar" 
                                                   name="lugar" 
                                                   value="{{ old('lugar', $contrato->lugar) }}"
                                                   placeholder="Ciudad, Estado donde se realizará la obra">
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
                                                   value="{{ old('razon_social', $contrato->razon_social) }}"
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
                                                   value="{{ old('rfc', $contrato->rfc) }}"
                                                   placeholder="RFC del cliente">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="representante_legal" class="form-label-custom">
                                                Representante Legal
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="representante_legal" 
                                                   name="representante_legal" 
                                                   value="{{ old('representante_legal', $contrato->representante_legal) }}"
                                                   placeholder="Nombre del representante legal">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sección 3: Ubicación y Dirección -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    Ubicación y Dirección
                                </h5>
                                
                                <!-- Mapa de Google -->
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <div class="form-group-custom">
                                            <label class="form-label-custom">
                                                <i class="fas fa-map-marked-alt me-2"></i>
                                                Seleccionar Ubicación en Mapa
                                            </label>
                                            <div id="map" class="map-container"></div>
                                            <div class="map-coordinates">
                                                Coordenadas seleccionadas:
                                            </div>
                                            <div class="coordinates-display">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-2">
                                                            <label class="form-label-custom small">Latitud:</label>
                                                            <input type="text" 
                                                                   class="form-control form-control-custom bg-light" 
                                                                   id="latitud_display" 
                                                                   readonly
                                                                   value="{{ old('latitud', $contrato->latitud) }}">
                                                            <input type="hidden" id="latitud" name="latitud" value="{{ old('latitud', $contrato->latitud) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-2">
                                                            <label class="form-label-custom small">Longitud:</label>
                                                            <input type="text" 
                                                                   class="form-control form-control-custom bg-light" 
                                                                   id="longitud_display" 
                                                                   readonly
                                                                   value="{{ old('longitud', $contrato->longitud) }}">
                                                            <input type="hidden" id="longitud" name="longitud" value="{{ old('longitud', $contrato->longitud) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Dirección del proyecto -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="calle_numero" class="form-label-custom">
                                                Calle y Número
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="calle_numero" 
                                                   name="calle_numero" 
                                                   value="{{ old('calle_numero', $contrato->calle_numero) }}"
                                                   placeholder="Calle, número, colonia">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="colonia" class="form-label-custom">
                                                Colonia
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="colonia" 
                                                   name="colonia" 
                                                   value="{{ old('colonia', $contrato->colonia) }}"
                                                   placeholder="Colonia">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="alcaldia_municipio" class="form-label-custom">
                                                Municipio/Alcaldía
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="alcaldia_municipio" 
                                                   name="alcaldia_municipio" 
                                                   value="{{ old('alcaldia_municipio', $contrato->alcaldia_municipio) }}"
                                                   placeholder="Municipio o alcaldía">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="entidad" class="form-label-custom">
                                                Entidad
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="entidad" 
                                                   name="entidad" 
                                                   value="{{ old('entidad', $contrato->entidad) }}"
                                                   placeholder="Estado o entidad federativa">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="codigo_postal" class="form-label-custom">
                                                Código Postal
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="codigo_postal" 
                                                   name="codigo_postal" 
                                                   value="{{ old('codigo_postal', $contrato->codigo_postal) }}"
                                                   placeholder="Código postal">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="telefono" class="form-label-custom">
                                                Teléfono
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="telefono" 
                                                   name="telefono" 
                                                   value="{{ old('telefono', $contrato->telefono) }}"
                                                   placeholder="Teléfono de contacto">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sección 4: Montos del Contrato -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-dollar-sign me-2"></i>
                                    Montos del Contrato
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="concepto" class="form-label-custom">
                                                Concepto
                                            </label>
                                            <select class="form-control form-control-custom" 
                                                    id="concepto" 
                                                    name="concepto">
                                                <option value="">Seleccionar concepto...</option>
                                                <option value="TOTAL CONTRATO" {{ old('concepto', $contrato->concepto) == 'TOTAL CONTRATO' ? 'selected' : '' }}>TOTAL CONTRATO</option>
                                                <option value="CONVENIO APLIACION" {{ old('concepto', $contrato->concepto) == 'CONVENIO APLIACION' ? 'selected' : '' }}>CONVENIO APLIACION</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="porcentaje_iva" class="form-label-custom">
                                                Porcentaje IVA (%)
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="porcentaje_iva" 
                                                       value="{{ $contrato->subtotal && $contrato->iva ? round(($contrato->iva / $contrato->subtotal) * 100, 2) : '16' }}"
                                                       step="0.01"
                                                       placeholder="16.00"
                                                       min="0"
                                                       max="100">
                                                <span class="input-group-text">%</span>
                                            </div>
                                            <div class="help-text">Ingrese el porcentaje de IVA a aplicar</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="subtotal" class="form-label-custom">
                                                Subtotal
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="subtotal" 
                                                       name="subtotal" 
                                                       value="{{ old('subtotal', $contrato->subtotal) }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
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
                                                       value="{{ old('iva', $contrato->iva) }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0"
                                                       readonly
                                                       style="background-color: #f8f9fa;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
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
                                                       value="{{ old('total', $contrato->total) }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0"
                                                       readonly
                                                       style="background-color: #f8f9fa;">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="monto_anticipo" class="form-label-custom">
                                                Anticipo
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="monto_anticipo" 
                                                       name="monto_anticipo" 
                                                       value="{{ old('monto_anticipo', $contrato->monto_anticipo) }}"
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
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="duracion" class="form-label-custom">
                                                Duración
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="duracion" 
                                                   name="duracion" 
                                                   value="{{ old('duracion', $contrato->duracion) }}"
                                                   placeholder="Ej: 12 meses, 6 semanas">
                                            <div class="help-text">Tiempo estimado para la obra</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="fecha_contrato" class="form-label-custom">
                                                Fecha del Contrato
                                            </label>
                                            <input type="date" 
                                                   class="form-control form-control-custom" 
                                                   id="fecha_contrato" 
                                                   name="fecha_contrato" 
                                                   value="{{ old('fecha_contrato', $contrato->fecha_contrato ? $contrato->fecha_contrato->format('Y-m-d') : '') }}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="fecha_inicio_obra" class="form-label-custom">
                                                Fecha Inicio Obra
                                            </label>
                                            <input type="date" 
                                                   class="form-control form-control-custom" 
                                                   id="fecha_inicio_obra" 
                                                   name="fecha_inicio_obra" 
                                                   value="{{ old('fecha_inicio_obra', $contrato->fecha_inicio_obra ? $contrato->fecha_inicio_obra->format('Y-m-d') : '') }}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="fecha_terminacion_obra" class="form-label-custom">
                                                Fecha Terminación Obra
                                            </label>
                                            <input type="date" 
                                                   class="form-control form-control-custom" 
                                                   id="fecha_terminacion_obra" 
                                                   name="fecha_terminacion_obra" 
                                                   value="{{ old('fecha_terminacion_obra', $contrato->fecha_terminacion_obra ? $contrato->fecha_terminacion_obra->format('Y-m-d') : '') }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="form-group-custom">
                                            <label for="observaciones" class="form-label-custom">
                                                Observaciones
                                            </label>
                                            <textarea class="form-control form-control-custom" 
                                                      id="observaciones" 
                                                      name="observaciones" 
                                                      rows="3"
                                                      placeholder="Notas adicionales, especificaciones técnicas, condiciones especiales...">{{ old('observaciones', $contrato->observaciones) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sección 6: Datos Bancarios -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-university me-2"></i>
                                    Datos Bancarios
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="banco" class="form-label-custom">
                                                Banco
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="banco" 
                                                   name="banco" 
                                                   value="{{ old('banco', $contrato->banco) }}"
                                                   placeholder="Nombre del banco">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="no_cuenta" class="form-label-custom">
                                                Número de Cuenta
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="no_cuenta" 
                                                   name="no_cuenta" 
                                                   value="{{ old('no_cuenta', $contrato->no_cuenta) }}"
                                                   placeholder="Número de cuenta bancaria">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="sucursal" class="form-label-custom">
                                                Sucursal
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="sucursal" 
                                                   name="sucursal" 
                                                   value="{{ old('sucursal', $contrato->sucursal) }}"
                                                   placeholder="Sucursal bancaria">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="clabe_interbancaria" class="form-label-custom">
                                                CLABE Interbancaria
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="clabe_interbancaria" 
                                                   name="clabe_interbancaria" 
                                                   value="{{ old('clabe_interbancaria', $contrato->clabe_interbancaria) }}"
                                                   placeholder="CLABE interbancaria (18 dígitos)"
                                                   maxlength="18">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group-custom">
                                            <label for="mail_facturas" class="form-label-custom">
                                                Email para Facturas
                                            </label>
                                            <input type="email" 
                                                   class="form-control form-control-custom" 
                                                   id="mail_facturas" 
                                                   name="mail_facturas" 
                                                   value="{{ old('mail_facturas', $contrato->mail_facturas) }}"
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
                                    <a href="{{ route('contratos.index') }}" class="btn btn-cancel btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-submit btn-primary">
                                        <i class="fas fa-save me-1"></i> Actualizar Contrato
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
        // Variables globales para el mapa
        let map;
        let marker;
        let geocoder;
        let defaultLat = {{ $contrato->latitud ?: '19.432608' }};
        let defaultLng = {{ $contrato->longitud ?: '-99.133209' }};
        
        // Función para inicializar el mapa
        function initMap() {
            // Coordenadas iniciales
            const initialLocation = { 
                lat: parseFloat(defaultLat), 
                lng: parseFloat(defaultLng) 
            };
            
            // Crear mapa
            map = new google.maps.Map(document.getElementById('map'), {
                center: initialLocation,
                zoom: {{ $contrato->latitud ? '14' : '10' }},
                mapTypeId: 'roadmap'
            });
            
            // Inicializar geocoder
            geocoder = new google.maps.Geocoder();
            
            // Si hay coordenadas existentes, poner marcador
            if (parseFloat(defaultLat) && parseFloat(defaultLng)) {
                const existingLocation = { lat: parseFloat(defaultLat), lng: parseFloat(defaultLng) };
                
                marker = new google.maps.Marker({
                    position: existingLocation,
                    map: map,
                    draggable: true,
                    animation: google.maps.Animation.DROP
                });
                
                // Escuchar cuando se arrastra el marcador
                marker.addListener('dragend', function(event) {
                    updateCoordinates(event.latLng.lat(), event.latLng.lng());
                });
                
                map.setCenter(existingLocation);
                map.setZoom(16);
                updateCoordinates(defaultLat, defaultLng);
            }
            
            // Escuchar clics en el mapa
            map.addListener('click', function(event) {
                placeMarker(event.latLng);
            });
        }
        
        // Función para colocar marcador
        function placeMarker(location) {
            if (marker) {
                marker.setPosition(location);
            } else {
                marker = new google.maps.Marker({
                    position: location,
                    map: map,
                    draggable: true,
                    animation: google.maps.Animation.DROP
                });
                
                // Escuchar cuando se arrastra el marcador
                marker.addListener('dragend', function(event) {
                    updateCoordinates(event.latLng.lat(), event.latLng.lng());
                });
            }
            
            updateCoordinates(location.lat(), location.lng());
        }
        
        // Función para actualizar coordenadas
        function updateCoordinates(lat, lng) {
            document.getElementById('latitud').value = lat;
            document.getElementById('longitud').value = lng;
            document.getElementById('latitud_display').value = lat.toFixed(6);
            document.getElementById('longitud_display').value = lng.toFixed(6);
        }
        
        // Función para calcular IVA y Total
        function calcularMontos() {
            const subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
            const porcentajeIva = parseFloat(document.getElementById('porcentaje_iva').value) || 0;
            
            // Calcular IVA
            const ivaCalculado = (subtotal * porcentajeIva) / 100;
            
            // Calcular Total
            const totalCalculado = subtotal + ivaCalculado;
            
            // Actualizar campos
            document.getElementById('iva').value = ivaCalculado.toFixed(2);
            document.getElementById('total').value = totalCalculado.toFixed(2);
        }
        
        // Escuchar cambios en subtotal y porcentaje IVA
        document.addEventListener('DOMContentLoaded', function() {
            const subtotalInput = document.getElementById('subtotal');
            const porcentajeIvaInput = document.getElementById('porcentaje_iva');
            
            if (subtotalInput && porcentajeIvaInput) {
                subtotalInput.addEventListener('input', calcularMontos);
                porcentajeIvaInput.addEventListener('input', calcularMontos);
                
                // Calcular valores iniciales si hay datos pre-cargados
                if (subtotalInput.value || porcentajeIvaInput.value) {
                    calcularMontos();
                }
            }
            
            // Validar que el porcentaje IVA esté entre 0 y 100
            if (porcentajeIvaInput) {
                porcentajeIvaInput.addEventListener('change', function() {
                    if (this.value < 0) {
                        this.value = 0;
                    } else if (this.value > 100) {
                        this.value = 100;
                    }
                    calcularMontos();
                });
            }
            
            // Remover el atributo name del campo porcentaje_iva para que no se envíe al servidor
            if (porcentajeIvaInput) {
                porcentajeIvaInput.removeAttribute('name');
            }
            
            // Validar fechas
            const inicioObra = document.getElementById('fecha_inicio_obra');
            const finObra = document.getElementById('fecha_terminacion_obra');
            
            if (inicioObra && finObra) {
                inicioObra.addEventListener('change', function() {
                    if (this.value && finObra.value && this.value > finObra.value) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Fecha inválida',
                            text: 'La fecha de inicio no puede ser posterior a la fecha de terminación'
                        });
                        this.value = '';
                    }
                });
                
                finObra.addEventListener('change', function() {
                    if (this.value && inicioObra.value && this.value < inicioObra.value) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Fecha inválida',
                            text: 'La fecha de terminación no puede ser anterior a la fecha de inicio'
                        });
                        this.value = '';
                    }
                });
            }
        });
    </script>
</body>
</html>