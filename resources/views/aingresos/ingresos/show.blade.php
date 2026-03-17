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
        
        .resumen-montos {
            background-color: #f0f4f8;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
            border: 1px dashed #426ec1;
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
                        <a href="{{ url('ingresos') }}" class="btn btn-outline-secondary">
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
                        </div>
                        <div>
                            @php
                                $verificado = $ingreso->verificado ?? 1;
                                if($verificado == 1) {
                                    echo '<span class="status-badge status-pendiente">Pendiente</span>';
                                } elseif($verificado == 0) {
                                    echo '<span class="status-badge status-rechazado">Rechazado</span>';
                                } elseif($verificado == 2) {
                                    echo '<span class="status-badge status-aprobado">Aprobado</span>';
                                }
                            @endphp
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
                        <!-- Sección 1: Información del Contrato (SOLO LECTURA) -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-file-contract me-2"></i>
                                Información del Contrato
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-12" style="display:none;">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Contrato (No editable)
                                        </label>
                                        <div class="info-display">
                                            @if($contrato)
                                                <strong>{{ $contrato->contrato_no ?? 'N/A' }}</strong> - {{ $contrato->obra ?? '' }}
                                                <br>
                                                <small class="text-muted">Cliente: {{ $contrato->cliente ?? 'Sin cliente' }}</small>
                                            @else
                                                <span class="text-danger">Contrato no encontrado</span>
                                            @endif
                                        </div>
                                        <!-- Campo oculto con el id_contrato actual -->
                                        <input type="hidden" name="id_contrato" value="{{ $ingreso->id_contrato }}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4 mb-2 mb-md-0">
                                        <small class="text-muted d-block">Consecutivo</small>
                                        <span class="fw-bold">{{ $contrato->consecutivo ?? 'N/A' }}</span>
                                    </div>
                                    <div class="col-md-4 mb-2 mb-md-0">
                                        <small class="text-muted d-block">Ref. Interna</small>
                                        <span>{{ $contrato->refinterna ?? 'N/A' }}</span>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted d-block">No. Contrato</small>
                                        <span>{{ $contrato->contrato_no ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                
                                <!-- Segunda fila: Cliente -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <small class="text-muted d-block">Cliente</small>
                                        <span class="fw-bold" title="{{ $contrato->cliente ?? 'No especificado' }}">
                                            {{ $contrato->cliente ?? 'No especificado' }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Tercera fila: Obra -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <small class="text-muted d-block">Obra</small>
                                        <span class="fw-bold" title="{{ $contrato->obra ?? 'Sin nombre de obra' }}">
                                            {{ $contrato->obra ?? 'Sin nombre de obra' }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Cuarta fila: Frente -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">Frente</small>
                                        <span>{{ $contrato->frente ?? 'No especificado' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <form action="{{ route('ingresos.update', $ingreso->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <!-- Sección 2: Información Básica del Ingreso -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-info-circle me-2"></i>
                                Información Básica
                            </h5>
                          
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label for="no_estimacion" class="form-label-custom required-label">
                                            Estimación No.
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-custom " 
                                               id="no_estimacion" 
                                               name="no_estimacion" 
                                               value="{{ old('no_estimacion', $ingreso->no_estimacion) }}"
                                               placeholder="Ej: EST-001"
                                               required>
                                        @error('no_estimacion')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label for="periodo_del" class="form-label-custom">
                                            Periodo Del
                                        </label>
                                        <input type="date" 
                                               class="form-control form-control-custom" 
                                               id="periodo_del" 
                                               name="periodo_del" 
                                              value="{{ old('periodo_del', $ingreso->periodo_del ? $ingreso->periodo_del->format('Y-m-d') : '') }}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label for="periodo_al" class="form-label-custom">
                                            Periodo Al
                                        </label>
                                        <input type="date" 
                                               class="form-control form-control-custom " 
                                               id="periodo_al" 
                                               name="periodo_al" 
                                              value="{{ old('periodo_al', optional($ingreso->periodo_al)->format('Y-m-d')) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sección 3: Montos de la Estimación (COMPLETA como en el original) -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-calculator me-2"></i>
                                Montos de la Estimación
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group-custom">
                                        <label for="importe_estimacion" class="form-label-custom">
                                            Importe de Estimación
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="number" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   id="importe_estimacion" 
                                                   name="importe_estimacion" 
                                                   value="{{ old('importe_estimacion', $ingreso->importe_estimacion) }}"
                                                   step="0.01"
                                                   placeholder="0.00"
                                                   min="0">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="form-group-custom">
                                        <label for="iva" class="form-label-custom">
                                            IVA
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">%</span>
                                            <input type="number" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   id="iva" 
                                                   name="iva" 
                                                   value="{{ old('iva', $ingreso->iva) }}"
                                                   step="0.01"
                                                   placeholder="0.00"
                                                   min="0"
                                                   noformat>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group-custom">
                                        <label for="importe_iva" class="form-label-custom">
                                            Importe IVA
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="number" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   id="importe_iva" 
                                                   name="importe_iva" 
                                                   value="{{ old('importe_iva', $ingreso->importe_iva) }}"
                                                   step="0.01"
                                                   placeholder="0.00"
                                                   min="0"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label for="total_estimacion_con_iva" class="form-label-custom">
                                            Total Estimación con IVA
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="number" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   id="total_estimacion_con_iva" 
                                                   name="total_estimacion_con_iva" 
                                                   value="{{ old('total_estimacion_con_iva', $ingreso->total_estimacion_con_iva) }}"
                                                   step="0.01"
                                                   placeholder="0.00"
                                                   min="0"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Separador 1 -->
                            <hr class="my-4">

                             <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <label for="sicv_cop" class="form-label-custom mb-0">
                                                2.0% SICV - COP
                                            </label>
                                            <div class="form-check mb-0">
                                                <input class="form-check-input" type="checkbox" id="aplicar_sicv" {{ $ingreso->sicv_cop > 0 ? 'checked' : '' }}>
                                                <label class="form-check-label small" for="aplicar_sicv">
                                                    Aplicar
                                                </label>
                                            </div>
                                        </div>
                                        <div class="input-group input-group-custom mt-1">
                                            <span class="input-group-text">$</span>
                                            <input type="number" 
                                                class="form-control form-control-custom numeric-input" 
                                                id="sicv_cop" 
                                                name="sicv_cop" 
                                                value="{{ old('sicv_cop', $ingreso->sicv_cop) }}"
                                                step="0.01"
                                                placeholder="0.00"
                                                min="0" readonly>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <label for="srcop_cdmx" class="form-label-custom mb-0">
                                                1.5% SRCOP - CDMX
                                            </label>
                                            <div class="form-check mb-0">
                                                <input class="form-check-input" type="checkbox" id="aplicar_srcop" {{ $ingreso->srcop_cdmx > 0 ? 'checked' : '' }}>
                                                <label class="form-check-label small" for="aplicar_srcop">
                                                    Aplicar
                                                </label>
                                            </div>
                                        </div>
                                        <div class="input-group input-group-custom mt-1">
                                            <span class="input-group-text">$</span>
                                            <input type="number" 
                                                class="form-control form-control-custom numeric-input" 
                                                id="srcop_cdmx" 
                                                name="srcop_cdmx" 
                                                value="{{ old('srcop_cdmx', $ingreso->srcop_cdmx) }}"
                                                step="0.01"
                                                placeholder="0.00"
                                                min="0" readonly>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <label for="retencion_5_al_millar" class="form-label-custom mb-0">
                                                Retención 5 al Millar
                                            </label>
                                        </div>
                                        <div class="input-group input-group-custom mt-1">
                                            <span class="input-group-text">$</span>
                                            <input type="number" 
                                                class="form-control form-control-custom numeric-input" 
                                                id="retencion_5_al_millar" 
                                                name="retencion_5_al_millar" 
                                                value="{{ old('retencion_5_al_millar', $ingreso->retencion_5_al_millar) }}"
                                                step="0.01"
                                                placeholder="0.00"
                                                min="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label for="sancion_atrazo_presentacion_estimacion" class="form-label-custom">
                                            Sanción Atraso Presentación Estimación
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="number" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   id="sancion_atrazo_presentacion_estimacion" 
                                                   name="sancion_atrazo_presentacion_estimacion" 
                                                   value="{{ old('sancion_atrazo_presentacion_estimacion', $ingreso->sancion_atrazo_presentacion_estimacion) }}"
                                                   step="0.01"
                                                   placeholder="0.00"
                                                   min="0">
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
                                                   class="form-control form-control-custom numeric-input" 
                                                   id="sancion_atraso_de_obra" 
                                                   name="sancion_atraso_de_obra" 
                                                   value="{{ old('sancion_atraso_de_obra', $ingreso->sancion_atraso_de_obra) }}"
                                                   step="0.01"
                                                   placeholder="0.00"
                                                   min="0">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label for="sancion_por_obra_mal_ejecutada" class="form-label-custom">
                                            Sanción por Obra Mal Ejecutada
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="number" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   id="sancion_por_obra_mal_ejecutada" 
                                                   name="sancion_por_obra_mal_ejecutada" 
                                                   value="{{ old('sancion_por_obra_mal_ejecutada', $ingreso->sancion_por_obra_mal_ejecutada) }}"
                                                   step="0.01"
                                                   placeholder="0.00"
                                                   min="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label for="retencion_por_atraso_en_programa_obra" class="form-label-custom">
                                            Retención por Atraso en Programa de Obra
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="number" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   id="retencion_por_atraso_en_programa_obra" 
                                                   name="retencion_por_atraso_en_programa_obra" 
                                                   value="{{ old('retencion_por_atraso_en_programa_obra', $ingreso->retencion_por_atraso_en_programa_obra) }}"
                                                   step="0.01"
                                                   placeholder="0.00"
                                                   min="0">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label for="retenciones_o_sanciones" class="form-label-custom">
                                            Retenciones o Sanciones
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="number" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   id="retenciones_o_sanciones" 
                                                   name="retenciones_o_sanciones" 
                                                   value="{{ old('retenciones_o_sanciones', $ingreso->retenciones_o_sanciones) }}"
                                                   step="0.01"
                                                   placeholder="0.00"
                                                   min="0"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Separador 2 -->
                            <hr class="my-4">
                            
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group-custom">
                                        <label for="amortizacion_anticipo" class="form-label-custom">
                                            Amortización Anticipo
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="number" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   id="amortizacion_anticipo" 
                                                   name="amortizacion_anticipo" 
                                                   value="{{ old('amortizacion_anticipo', $ingreso->amortizacion_anticipo) }}"
                                                   step="0.01"
                                                   placeholder="0.00"
                                                   min="0">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="form-group-custom">
                                        <label for="amortizacion_iva" class="form-label-custom">
                                            % I.V.A.
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">%</span>
                                            <input type="number" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   id="amortizacion_iva" 
                                                   name="amortizacion_iva" 
                                                   value="{{ old('amortizacion_iva', $ingreso->amortizacion_iva) }}"
                                                   step="0.01"
                                                   placeholder="0.00"
                                                   min="0">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                        <div class="form-group-custom">
                                            <label for="amor_iva" class="form-label-custom">
                                                Amortización I.V.A.
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="amor_iva" 
                                                       name="amor_iva" 
                                                       value="{{ old('amor_iva', $ultimoIngreso->amor_iva ?? 0) }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0">
                                            </div>
                                        </div>
                                    </div>

                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label for="total_amortizacion" class="form-label-custom">
                                            Total Amortización
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="number" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   id="total_amortizacion" 
                                                   name="total_amortizacion" 
                                                   value="{{ old('total_amortizacion', $ingreso->total_amortizacion) }}"
                                                   step="0.01"
                                                   placeholder="0.00"
                                                   min="0"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Separador 3 -->
                            <hr class="my-4">
                            
                            <div class="row">
                                <div class="col-md-4">
                                    
                                </div>
                                
                                <div class="col-md-4">
                                   
                                </div>
                               
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label for="estimado_menos_deducciones" class="form-label-custom">
                                            Estimado menos Deducciones
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="number" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   id="estimado_menos_deducciones" 
                                                   name="estimado_menos_deducciones" 
                                                   value="{{ old('estimado_menos_deducciones', $ingreso->estimado_menos_deducciones) }}"
                                                   step="0.01"
                                                   placeholder="0.00"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <!-- Botón guardar facturación -->
                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Actualizar Información
                                </button>
                            </div>
                            
                        </div>
                        </form>
                          <form action="{{ route('ingresos.update.facturacion', $ingreso->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                        <!-- SECCIÓN 4: FACTURACIÓN (EDITABLE) -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-file-invoice-dollar me-2"></i>
                                Facturación
                            </h5>
                          
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="factura" class="form-label-custom">
                                                Factura
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="factura" 
                                                   name="factura" 
                                                   value="{{ old('factura', $ingreso->factura) }}"
                                                   placeholder="Ej: FAC-001">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="fecha_factura" class="form-label-custom">
                                                Fecha Factura
                                            </label>
                                            <input type="date" 
                                                   class="form-control form-control-custom" 
                                                   id="fecha_factura" 
                                                   name="fecha_factura" 
                                                   value="{{ old('fecha_factura', optional($ingreso->fecha_factura)->format('Y-m-d')) }}">
                                        </div>
                                    </div>
                                </div>
                                
                       
                            
                            <!-- RESUMEN DE MONTOS DE LA ESTIMACIÓN (debajo de facturación) -->
                            <div class="resumen-montos mt-3">
                                <h6 class="fw-bold mb-3"><i class="fas fa-calculator me-2"></i>Montos de la Estimación</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">Importe Estimación</small>
                                        <strong>${{ number_format($ingreso->importe_estimacion ?? 0, 2) }}</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">IVA %</small>
                                        <strong>{{ number_format($ingreso->iva ?? 0, 2) }}%</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">Importe IVA</small>
                                        <strong>${{ number_format($ingreso->importe_iva ?? 0, 2) }}</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">Total con IVA</small>
                                        <strong>${{ number_format($ingreso->total_estimacion_con_iva ?? 0, 2) }}</strong>
                                    </div>
                                </div>
                                <hr class="my-2">
                                <div class="row">
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">SICV - COP (2%)</small>
                                        <strong>${{ number_format($ingreso->sicv_cop ?? 0, 2) }}</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">SRCOP - CDMX (1.5%)</small>
                                        <strong>${{ number_format($ingreso->srcop_cdmx ?? 0, 2) }}</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">Ret. 5 al Millar</small>
                                        <strong>${{ number_format($ingreso->retencion_5_al_millar ?? 0, 2) }}</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">Total Ret/Sanc</small>
                                        <strong>${{ number_format($ingreso->retenciones_o_sanciones ?? 0, 2) }}</strong>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">Amort. Anticipo</small>
                                        <strong>${{ number_format($ingreso->amortizacion_anticipo ?? 0, 2) }}</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">Amort. IVA</small>
                                        <strong>${{ number_format($ingreso->amortizacion_iva ?? 0, 2) }}</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">Total Amort.</small>
                                        <strong>${{ number_format($ingreso->total_amortizacion ?? 0, 2) }}</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">Líquido a Cobrar</small>
                                        <strong class="text-primary">${{ number_format($ingreso->estimado_menos_deducciones ?? $ingreso->estimado_menos_deducciones ?? 0, 2) }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- SECCIÓN 5: COBROS -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-money-bill-wave me-2"></i>
                                Cobros
                            </h5>
                            
                           
                                
                              
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="liquido_a_cobrar_display" class="form-label-custom">
                                                Líquido a Cobrar
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                    class="form-control form-control-custom numeric-input" 
                                                    id="liquido_a_cobrar_display" 
                                                   value="{{ old('estimado_menos_deducciones', $ingreso->estimado_menos_deducciones) }}"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="liquido_cobrado" class="form-label-custom required-label">
                                                Líquido Cobrado
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="liquido_cobrado" 
                                                       name="liquido_cobrado" 
                                                       value="{{ old('liquido_cobrado', $ingreso->liquido_cobrado) }}"
                                                       step="0.01"
                                                       min="0"
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="fecha_cobro" class="form-label-custom">
                                                Fecha Cobro
                                            </label>
                                            <input type="date" 
                                                   class="form-control form-control-custom" 
                                                   id="fecha_cobro" 
                                                   name="fecha_cobro" 
                                                    value="{{ old('fecha_cobro', optional($ingreso->fecha_cobro)->format('Y-m-d')) }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="por_cobrar" class="form-label-custom">
                                                POR COBRAR
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input bg-light fw-bold text-danger" 
                                                       id="por_cobrar" 
                                                       name="por_cobrar" 
                                                       value="{{ old('por_cobrar', $ingreso->por_cobrar) }}"
                                                       step="0.01"
                                                       readonly>
                                            </div>
                                            <small class="help-text">Líquido a cobrar - Líquido cobrado</small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="por_facturar" class="form-label-custom">
                                                POR FACTURAR
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input bg-light fw-bold text-info" 
                                                       id="por_facturar" 
                                                       name="por_facturar" 
                                                       value="{{ old('por_facturar', $ingreso->por_facturar) }}"
                                                       step="0.01"
                                                       readonly>
                                            </div>
                                            <small class="help-text">Del monto total del contrato menos lo facturado</small>
                                        </div>
                                    </div>
                                </div>
                             
                            
                        </div>
                        
                        <!-- Sección 8: Estado -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-info-circle me-2"></i>
                                Estado
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label for="status" class="form-label-custom">
                                            Status
                                        </label>
                                        <select class="form-control form-control-custom" 
                                                id="status" 
                                                name="status">
                                            <option value="">Seleccionar status...</option>
                                            <option value="pagado" {{ old('status', $ingreso->status) == 'pagado' ? 'selected' : '' }}>Pagado</option>
                                            <option value="en_tramite" {{ old('status', $ingreso->status) == 'en_tramite' ? 'selected' : '' }}>En Trámite</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Botón guardar cobros -->
                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Actualizar Facturación
                                </button>
                                
                            </div>
                               
                        </div>
                         </form>
                        <!-- Botones de acción generales (opcional) -->
                        
                    </div>
                </div>
            </div>
        </main>
    </div>

    @include('footer')
    
    <script>
// Función para calcular importe del IVA y total de estimación
function calcularImporteIVAYTotal() {
    // Obtener valores numéricos reales
    const importeEstimacion = parseFloat($('#importe_estimacion').val()) || 0;
    const iva = parseFloat($('#iva').val()) || 0;
    
    // Calcular importe del IVA
    const importeIVACalculado = importeEstimacion * (iva / 100);
    
    // Calcular total = importe + importe IVA
    const totalCalculado = importeEstimacion + importeIVACalculado;
    
    // Asignar valores
    $('#importe_iva').val(importeIVACalculado.toFixed(2));
    $('#total_estimacion_con_iva').val(totalCalculado.toFixed(2));
    
    // Disparar eventos
    dispararEventos('#importe_iva');
    dispararEventos('#total_estimacion_con_iva');
    
    // Recalcular porcentajes si los checkboxes están activos
    if ($('#aplicar_sicv').is(':checked')) {
        calcularSICV();
    }
    if ($('#aplicar_srcop').is(':checked')) {
        calcularSRCOP();
    } else {
        calcularRetencionesSanciones();
    }
}

// Función para calcular 2% SICV
function calcularSICV() {
    const totalEstimacion = parseFloat($('#importe_estimacion').val()) || 0;
    const resultado = totalEstimacion * 0.02; // 2%
    
    $('#sicv_cop').val(resultado.toFixed(2));
    
    calcularRetencionesSanciones();
}

// Función para calcular 1.5% SRCOP
function calcularSRCOP() {
    const totalEstimacion = parseFloat($('#importe_estimacion').val()) || 0;
    const resultado = totalEstimacion * 0.015; // 1.5%
    
    $('#srcop_cdmx').val(resultado.toFixed(2));
    
    calcularRetencionesSanciones();
}


// Función para calcular Retenciones o Sanciones
function calcularRetencionesSanciones() {
    // Lista de IDs de los campos a sumar
    var camposRetenciones = [
        'sicv_cop',
        'srcop_cdmx',
        'retencion_5_al_millar',
        'sancion_atrazo_presentacion_estimacion',
        'sancion_atraso_de_obra',
        'sancion_por_obra_mal_ejecutada',
        'retencion_por_atraso_en_programa_obra'
    ];
    
    var total = 0;
    
    // Sumar todos los valores
    $.each(camposRetenciones, function(index, id) {
        var valor = parseFloat($('#' + id).val()) || 0;
        total += valor;
    });
    
    // Actualizar el campo de retenciones o sanciones
    $('#retenciones_o_sanciones').val(total.toFixed(2));
    dispararEventos('#retenciones_o_sanciones');
    
    // Calcular estimado menos deducciones
    calcularEstimadoMenosDeducciones();
}

// Función para calcular Total Amortización
function calcularTotalAmortizacion() {
    var amortizacionAnticipo = parseFloat($('#amortizacion_anticipo').val()) || 0;
    var amortizacionIva = parseFloat($('#amortizacion_iva').val()) || 0;
    
    // Calcular el IVA de la amortización (amortizacion_anticipo * (amortizacion_iva/100))
    var ivaCalculado = amortizacionAnticipo * (amortizacionIva / 100);
    
    // Calcular el total (amortizacion_anticipo + ivaCalculado)
    var total = amortizacionAnticipo + ivaCalculado;
    
    // Asignar valores
    $('#amor_iva').val(ivaCalculado.toFixed(2));
    $('#total_amortizacion').val(total.toFixed(2));
    
    // Disparar eventos
    dispararEventos('#amor_iva');
    dispararEventos('#total_amortizacion');
    
    // Calcular estimado menos deducciones
    calcularEstimadoMenosDeducciones();
}

// Función para calcular Estimado menos Deducciones
function calcularEstimadoMenosDeducciones() {
    var totalEstimacionConIva = parseFloat($('#total_estimacion_con_iva').val()) || 0;
    var retencionesSanciones = parseFloat($('#retenciones_o_sanciones').val()) || 0;
    var totalAmortizacion = parseFloat($('#total_amortizacion').val()) || 0;
    
    var resultado = totalEstimacionConIva - retencionesSanciones - totalAmortizacion;
    
    $('#estimado_menos_deducciones').val(resultado.toFixed(2));
    dispararEventos('#estimado_menos_deducciones');
}

// Función para disparar eventos
function dispararEventos(selector) {
    var input = document.querySelector(selector);
    if (input) {
        input.dispatchEvent(new Event('input', { bubbles: true }));
        input.dispatchEvent(new Event('keyup', { bubbles: true }));
        input.dispatchEvent(new Event('change', { bubbles: true }));
    }
}

// Inicializar eventos
$(document).ready(function() {
    // Evento para importe estimación e IVA
    $('#importe_estimacion, #iva').on('input', function() {
        calcularImporteIVAYTotal();
    });
    
    // Eventos para checkboxes (dentro del document ready)
    $('#aplicar_sicv').on('change', function() {
        if ($(this).is(':checked')) {
            calcularSICV();
        } else {
            $('#sicv_cop').val('0.00');
            calcularRetencionesSanciones();
        }
        dispararEventos('#sicv_cop');// Si los pongo se dispara el evento pero el checkbox no se queda checked
    });

    $('#aplicar_srcop').on('change', function() {
        if ($(this).is(':checked')) {
            calcularSRCOP();
        } else {
            $('#srcop_cdmx').val('0.00');
            calcularRetencionesSanciones();
        }
        dispararEventos('#srcop_cdmx');// Si los pongo se dispara el evento pero el checkbox no se queda checked
    });
    
  
   
    
    // Campos para calcular retenciones
    var camposRetenciones = [
        'retencion_5_al_millar',
        'sancion_atrazo_presentacion_estimacion',
        'sancion_atraso_de_obra',
        'sancion_por_obra_mal_ejecutada',
        'retencion_por_atraso_en_programa_obra'
    ];
    
    $.each(camposRetenciones, function(index, id) {
        $('#' + id).on('input', function() {
            calcularRetencionesSanciones();
        });
    });
    
    // Campos para amortización
    $('#amortizacion_anticipo, #amortizacion_iva').on('input', function() {
        calcularTotalAmortizacion();
    });
    
    // Validar IVA
    $('#iva').on('change', function() {
        let valor = parseFloat($(this).val()) || 0;
        if (valor < 0) $(this).val(0);
        if (valor > 100) $(this).val(100);
    });
    
    // Calcular valores iniciales
    setTimeout(function() {
        calcularImporteIVAYTotal();
        calcularRetencionesSanciones();
        calcularTotalAmortizacion();
        calcularEstimadoMenosDeducciones();
    }, 200);
});
</script>


<script>
// Función para calcular Por Cobrar (Líquido a cobrar - Líquido cobrado)
$(document).ready(function() {
    // Obtener el valor del líquido a cobrar desde el modelo
    const liquidoACobrar = {{ $ingreso->estimado_menos_deducciones ?? 0 }};
    
    // Función para calcular y actualizar el campo Por Cobrar
    $('#liquido_cobrado').on('input', function() {
        const liquidoCobrado = parseFloat($('#liquido_cobrado').val()) || 0;
        const porCobrar = liquidoACobrar - liquidoCobrado;
        $('#por_cobrar').val(porCobrar.toFixed(2));
        dispararEventos('#por_cobrar');
    });
    
    
     //$('#liquido_cobrado').on('input', calcularPorCobrar);`
    
    // Calcular el valor inicial al cargar la página
    calcularPorCobrar();
});
</script>
</body>
</html>

<!--

3.- poder guardar sin los datos de factura y despues poder editar y agregar los datos de la factura 

2.- Liquido cobrado es Estimado menos Deducciones , quitar (Importe Facturado, es lo mismo que se calcula del importe menos deducciones )
a) liquido cobrado se ingresa manual 
b)POR COBRAR el el liquido a cobrar - liquido cobrado

3.- POR FACTURAR , se debe de calcular del monto del contrato 

revisar el status que al final quede pagado
quitar la leyenda de formulario editable
-->