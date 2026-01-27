<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Ver/Editar Ingreso</title>
    
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
        
        .form-control-custom:disabled {
            background-color: #e9ecef;
            opacity: 1;
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
        
        .contrato-info {
            background-color: #e7f1ff;
            border: 1px solid #b6d4fe;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .contrato-info-item {
            margin-bottom: 0.5rem;
        }
        
        .contrato-info-label {
            font-weight: 600;
            color: #0d6efd;
            min-width: 120px;
            display: inline-block;
        }
        
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
            display: inline-block;
        }
        
        .status-pendiente {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-aprobado {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-rechazado {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .info-display {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 0.75rem 1rem;
            min-height: 38px;
            display: flex;
            align-items: center;
        }
        
        .form-actions {
            position: sticky;
            bottom: 0;
            background: white;
            padding: 1rem 0;
            border-top: 1px solid #dee2e6;
            margin-top: 2rem;
        }
        
        @media (max-width: 768px) {
            .form-section {
                padding: 1rem;
            }
            
            .card-header-form {
                padding: 1rem;
            }
            
            .form-actions {
                position: static;
                margin-top: 1rem;
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
                        <h1 class="h3 mb-1 text-gray-800">Ingreso</h1>
                    </div>
                    <div>
                        <a href="{{ route('ingresos.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Regresar
                        </a>
                    </div>
                </div>
                
                <!-- Información del estado -->
                <div class="alert alert-info mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-info-circle me-2"></i>
                            Estado de verificación: 
                            @php
                                $verificado = $ingreso->verificado ?? 1;
                                if($verificado == 1) {
                                    echo '<span class="status-badge status-pendiente ms-2">Pendiente (1)</span>';
                                } elseif($verificado == 0) {
                                    echo '<span class="status-badge status-rechazado ms-2">Rechazado (0)</span>';
                                } elseif($verificado == 2) {
                                    echo '<span class="status-badge status-aprobado ms-2">Aprobado (2)</span>';
                                }
                            @endphp
                        </div>
                        <div>
                            @if($ingreso->verificado == 1)
                                <span class="text-success">
                                    <i class="fas fa-edit me-1"></i> Formulario editable
                                </span>
                            @else
                                <span class="text-danger">
                                    <i class="fas fa-lock me-1"></i> Formulario bloqueado
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Formulario de edición -->
                <div class="card card-formulario">
                    <div class="card-header card-header-form">
                        <h5 class="mb-0">
                            <i class="fas fa-edit me-2 text-primary"></i>
                            {{ $ingreso->verificado == 1 ? 'Editar Ingreso' : 'Ver Ingreso' }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('ingresos.update', $ingreso->id) }}" method="POST" id="ingresoForm">
                            @csrf
                            @method('PUT')
                            
                            <!-- Sección 1: Información del Contrato (SOLO LECTURA) -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-file-contract me-2"></i>
                                    Información del Contrato
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group-custom">
                                            <label class="form-label-custom">
                                                Contrato (No editable)
                                            </label>
                                            <div class="info-display">
                                                @if($ingreso->contrato)
                                                    <strong>{{ $ingreso->contrato->contrato_no }}</strong> - {{ $ingreso->contrato->obra }}
                                                    <br>
                                                    <small class="text-muted">Cliente: {{ $ingreso->contrato->cliente }}</small>
                                                @else
                                                    <span class="text-danger">Contrato no encontrado</span>
                                                @endif
                                            </div>
                                            <!-- Campo oculto con el id_contrato actual -->
                                            <input type="hidden" name="id_contrato" value="{{ $ingreso->id_contrato }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sección 2: Información Básica del Ingreso -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Información Básica
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="estimacion" class="form-label-custom required-label">
                                                Estimación No.
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                   id="estimacion" 
                                                   name="estimacion" 
                                                   value="{{ old('estimacion', $ingreso->estimacion) }}"
                                                   placeholder="Ej: EST-001"
                                                   required
                                                   {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                            @error('estimacion')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="area" class="form-label-custom">
                                                Área
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                   id="area" 
                                                   name="area" 
                                                   value="{{ old('area', $ingreso->area) }}"
                                                   placeholder="Ej: Administración, Obra Civil"
                                                   {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="periodo_del" class="form-label-custom">
                                                Periodo Del
                                            </label>
                                            <input type="date" 
                                                   class="form-control form-control-custom {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                   id="periodo_del" 
                                                   name="periodo_del" 
                                                   value="{{ old('periodo_del', $ingreso->periodo_del ? $ingreso->periodo_del->format('Y-m-d') : '') }}"
                                                   {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="periodo_al" class="form-label-custom">
                                                Periodo Al
                                            </label>
                                            <input type="date" 
                                                   class="form-control form-control-custom {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                   id="periodo_al" 
                                                   name="periodo_al" 
                                                   value="{{ old('periodo_al', $ingreso->periodo_al ? $ingreso->periodo_al->format('Y-m-d') : '') }}"
                                                   {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sección 3: Montos de la Estimación -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-calculator me-2"></i>
                                    Montos de la Estimación
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="importe_de_estimacion" class="form-label-custom">
                                                Importe de Estimación
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                       id="importe_de_estimacion" 
                                                       name="importe_de_estimacion" 
                                                       value="{{ old('importe_de_estimacion', $ingreso->importe_de_estimacion) }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0"
                                                       {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
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
                                                       class="form-control form-control-custom numeric-input {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                       id="iva" 
                                                       name="iva" 
                                                       value="{{ old('iva', $ingreso->iva) }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0"
                                                       {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="total_estimacion_con_iva" class="form-label-custom">
                                                Total con IVA
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                       id="total_estimacion_con_iva" 
                                                       name="total_estimacion_con_iva" 
                                                       value="{{ old('total_estimacion_con_iva', $ingreso->total_estimacion_con_iva) }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0"
                                                       {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sección 4: Deducciones -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-minus-circle me-2"></i>
                                    Deducciones
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="retenciones_o_sanciones" class="form-label-custom">
                                                Retenciones/Sanciones
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                       id="retenciones_o_sanciones" 
                                                       name="retenciones_o_sanciones" 
                                                       value="{{ old('retenciones_o_sanciones', $ingreso->retenciones_o_sanciones) }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0"
                                                       {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="cargos_adicionales_35_porciento" class="form-label-custom">
                                                Cargos Adicionales 35%
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                       id="cargos_adicionales_35_porciento" 
                                                       name="cargos_adicionales_35_porciento" 
                                                       value="{{ old('cargos_adicionales_35_porciento', $ingreso->cargos_adicionales_35_porciento) }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0"
                                                       {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="retencion_5_al_millar" class="form-label-custom">
                                                Retención 5‰
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                       id="retencion_5_al_millar" 
                                                       name="retencion_5_al_millar" 
                                                       value="{{ old('retencion_5_al_millar', $ingreso->retencion_5_al_millar) }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0"
                                                       {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="sancion_atraso_presentacion_estimacion" class="form-label-custom">
                                                Sanción Atraso Presentación
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                       id="sancion_atraso_presentacion_estimacion" 
                                                       name="sancion_atraso_presentacion_estimacion" 
                                                       value="{{ old('sancion_atraso_presentacion_estimacion', $ingreso->sancion_atraso_presentacion_estimacion) }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0"
                                                       {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="sancion_atraso_de_obra" class="form-label-custom">
                                                Sanción Atraso de Obra
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                       id="sancion_atraso_de_obra" 
                                                       name="sancion_atraso_de_obra" 
                                                       value="{{ old('sancion_atraso_de_obra', $ingreso->sancion_atraso_de_obra) }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0"
                                                       {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="sancion_por_obra_mal_ejecutada" class="form-label-custom">
                                                Sanción Obra Mal Ejecutada
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                       id="sancion_por_obra_mal_ejecutada" 
                                                       name="sancion_por_obra_mal_ejecutada" 
                                                       value="{{ old('sancion_por_obra_mal_ejecutada', $ingreso->sancion_por_obra_mal_ejecutada) }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0"
                                                       {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="retencion_por_atraso_en_programa_de_obra" class="form-label-custom">
                                                Retención Atraso Programa
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                       id="retencion_por_atraso_en_programa_de_obra" 
                                                       name="retencion_por_atraso_en_programa_de_obra" 
                                                       value="{{ old('retencion_por_atraso_en_programa_de_obra', $ingreso->retencion_por_atraso_en_programa_de_obra) }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0"
                                                       {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="amortizacion_anticipo" class="form-label-custom">
                                                Amortización Anticipo
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                       id="amortizacion_anticipo" 
                                                       name="amortizacion_anticipo" 
                                                       value="{{ old('amortizacion_anticipo', $ingreso->amortizacion_anticipo) }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0"
                                                       {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="amortizacion_con_iva" class="form-label-custom">
                                                Amortización con IVA
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                       id="amortizacion_con_iva" 
                                                       name="amortizacion_con_iva" 
                                                       value="{{ old('amortizacion_con_iva', $ingreso->amortizacion_con_iva) }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0"
                                                       {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="total_deducciones" class="form-label-custom">
                                                Total Deducciones
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                       id="total_deducciones" 
                                                       name="total_deducciones" 
                                                       value="{{ old('total_deducciones', $ingreso->total_deducciones) }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0"
                                                       {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="estimado_menos_deducciones" class="form-label-custom">
                                                Estimado - Deducciones
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                       id="estimado_menos_deducciones" 
                                                       name="estimado_menos_deducciones" 
                                                       value="{{ old('estimado_menos_deducciones', $ingreso->estimado_menos_deducciones) }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sección 5: Facturación y Cobro -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-file-invoice-dollar me-2"></i>
                                    Facturación y Cobro
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="factura" class="form-label-custom">
                                                No. de Factura
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                   id="factura" 
                                                   name="factura" 
                                                   value="{{ old('factura', $ingreso->factura) }}"
                                                   placeholder="Ej: FAC-001"
                                                   {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="fecha_factura" class="form-label-custom">
                                                Fecha Factura
                                            </label>
                                            <input type="date" 
                                                   class="form-control form-control-custom {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                   id="fecha_factura" 
                                                   name="fecha_factura" 
                                                   value="{{ old('fecha_factura', $ingreso->fecha_factura ? $ingreso->fecha_factura->format('Y-m-d') : '') }}"
                                                   {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="fecha_elaboracion" class="form-label-custom">
                                                Fecha Elaboración
                                            </label>
                                            <input type="date" 
                                                   class="form-control form-control-custom {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                   id="fecha_elaboracion" 
                                                   name="fecha_elaboracion" 
                                                   value="{{ old('fecha_elaboracion', $ingreso->fecha_elaboracion ? $ingreso->fecha_elaboracion->format('Y-m-d') : '') }}"
                                                   {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="importe_facturado" class="form-label-custom">
                                                Importe Facturado
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                       id="importe_facturado" 
                                                       name="importe_facturado" 
                                                       value="{{ old('importe_facturado', $ingreso->importe_facturado) }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0"
                                                       {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sección 6: Líquidos y Estado -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-money-bill-wave me-2"></i>
                                    Líquidos y Estado
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="liquido_a_cobrar" class="form-label-custom">
                                                Líquido a Cobrar
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                       id="liquido_a_cobrar" 
                                                       name="liquido_a_cobrar" 
                                                       value="{{ old('liquido_a_cobrar', $ingreso->liquido_a_cobrar) }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0"
                                                       {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="liquido_cobrado" class="form-label-custom">
                                                Líquido Cobrado
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                       id="liquido_cobrado" 
                                                       name="liquido_cobrado" 
                                                       value="{{ old('liquido_cobrado', $ingreso->liquido_cobrado) }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0"
                                                       {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="por_cobrar" class="form-label-custom">
                                                Por Cobrar
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                       id="por_cobrar" 
                                                       name="por_cobrar" 
                                                       value="{{ old('por_cobrar', $ingreso->por_cobrar) }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="fecha_cobro" class="form-label-custom">
                                                Fecha de Cobro
                                            </label>
                                            <input type="date" 
                                                   class="form-control form-control-custom {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                   id="fecha_cobro" 
                                                   name="fecha_cobro" 
                                                   value="{{ old('fecha_cobro', $ingreso->fecha_cobro ? $ingreso->fecha_cobro->format('Y-m-d') : '') }}"
                                                   {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="status" class="form-label-custom">
                                                Estado
                                            </label>
                                            <select class="form-control form-control-custom {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                    id="status" 
                                                    name="status"
                                                    {{ $ingreso->verificado != 1 ? 'disabled' : '' }}>
                                                <option value="pendiente" {{ old('status', $ingreso->status) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                <option value="pagado" {{ old('status', $ingreso->status) == 'pagado' ? 'selected' : '' }}>Pagado</option>
                                                <option value="parcial" {{ old('status', $ingreso->status) == 'parcial' ? 'selected' : '' }}>Parcial</option>
                                                <option value="cancelado" {{ old('status', $ingreso->status) == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                            </select>
                                            @if($ingreso->verificado != 1)
                                                <input type="hidden" name="status" value="{{ $ingreso->status }}">
                                            @endif
                                        </div>
                                    </div>
                                    
                                    
                                </div>
                            </div>
                            
                            <!-- Sección 7: Avance de Obra -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-chart-line me-2"></i>
                                    Avance de Obra
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="avance_obra_estimacion" class="form-label-custom">
                                                Avance Estimación (%)
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                       id="avance_obra_estimacion" 
                                                       name="avance_obra_estimacion" 
                                                       value="{{ old('avance_obra_estimacion', $ingreso->avance_obra_estimacion) }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0"
                                                       max="100"
                                                       {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="avance_obra_real" class="form-label-custom">
                                                Avance Real (%)
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                       id="avance_obra_real" 
                                                       name="avance_obra_real" 
                                                       value="{{ old('avance_obra_real', $ingreso->avance_obra_real) }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0"
                                                       max="100"
                                                       {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="porcentaje_avance_financiero" class="form-label-custom">
                                                Avance Financiero (%)
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                       id="porcentaje_avance_financiero" 
                                                       name="porcentaje_avance_financiero" 
                                                       value="{{ old('porcentaje_avance_financiero', $ingreso->porcentaje_avance_financiero) }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0"
                                                       max="100"
                                                       {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sección 8: Por Facturar -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-file-invoice me-2"></i>
                                    Por Facturar
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="por_facturar" class="form-label-custom">
                                                Por Facturar
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input {{ $ingreso->verificado != 1 ? 'bg-light' : '' }}" 
                                                       id="por_facturar" 
                                                       name="por_facturar" 
                                                       value="{{ old('por_facturar', $ingreso->por_facturar) }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       {{ $ingreso->verificado != 1 ? 'readonly' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Botones de acción -->
                            <div class="form-actions">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted">
                                            @if($ingreso->verificado == 1)
                                                Los campos marcados con <span class="text-danger">*</span> son obligatorios
                                            @else
                                                <i class="fas fa-lock me-1 text-danger"></i> El formulario está bloqueado porque no está pendiente (verificado ≠ 1)
                                            @endif
                                        </small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('ingresos.index') }}" class="btn btn-cancel btn-outline-secondary">
                                            <i class="fas fa-times me-1"></i> Cancelar
                                        </a>
                                        @if($ingreso->verificado == 1)
                                            <button type="submit" class="btn btn-submit btn-primary">
                                                <i class="fas fa-save me-1"></i> Guardar Cambios
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-secondary" disabled>
                                                <i class="fas fa-lock me-1"></i> Solo lectura
                                            </button>
                                        @endif
                                    </div>
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
        // Validar fechas del periodo
        document.addEventListener('DOMContentLoaded', function() {
            const periodoDel = document.getElementById('periodo_del');
            const periodoAl = document.getElementById('periodo_al');
            
            if (periodoDel && periodoAl) {
                periodoDel.addEventListener('change', function() {
                    if (this.value && periodoAl.value && this.value > periodoAl.value) {
                        alert('La fecha "Del" no puede ser posterior a la fecha "Al"');
                        this.value = '';
                    }
                });
                
                periodoAl.addEventListener('change', function() {
                    if (this.value && periodoDel.value && this.value < periodoDel.value) {
                        alert('La fecha "Al" no puede ser anterior a la fecha "Del"');
                        this.value = '';
                    }
                });
            }
            
            // Deshabilitar envío del formulario si no es editable
            const form = document.getElementById('ingresoForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const verificado = {{ $ingreso->verificado }};
                    if (verificado != 1) {
                        e.preventDefault();
                        alert('No se puede editar este ingreso porque no está pendiente (verificado ≠ 1)');
                        return false;
                    }
                });
            }
        });
    </script>
</body>
</html>