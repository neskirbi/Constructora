<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Ver Ingreso</title>
    
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
            background-color: #f8f9fa;
            opacity: 1;
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
            background-color: #e9ecef;
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
        
        .info-display-text {
            margin: 0;
            color: #212529;
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
                        <h1 class="h3 mb-1 text-gray-800">Ver Ingreso</h1>
                        <p class="text-muted mb-0">ID: {{ $ingreso->id }}</p>
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
                            <span class="text-muted">
                                <i class="fas fa-eye me-1"></i> Modo solo lectura
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Formulario de solo lectura -->
                <div class="card card-formulario">
                    <div class="card-header card-header-form">
                        <h5 class="mb-0">
                            <i class="fas fa-eye me-2 text-primary"></i>
                            Información del Ingreso
                        </h5>
                    </div>
                    <div class="card-body">
                        
                        <!-- Sección 1: Información del Contrato -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-file-contract me-2"></i>
                                Información del Contrato
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Contrato
                                        </label>
                                        <div class="info-display">
                                            @if($ingreso->contrato)
                                                <p class="info-display-text mb-0">
                                                    <strong>{{ $ingreso->contrato->contrato_no }}</strong> - {{ $ingreso->contrato->obra }}
                                                    <br>
                                                    <small class="text-muted">Cliente: {{ $ingreso->contrato->cliente }}</small>
                                                </p>
                                            @else
                                                <span class="text-danger">Contrato no encontrado</span>
                                            @endif
                                        </div>
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
                                        <label class="form-label-custom">
                                            Estimación No.
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-custom" 
                                               value="{{ $ingreso->estimacion }}"
                                               readonly>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Área
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-custom" 
                                               value="{{ $ingreso->area ?? 'No especificado' }}"
                                               readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Periodo Del
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-custom" 
                                               value="{{ $ingreso->periodo_del ? $ingreso->periodo_del->format('d/m/Y') : 'No especificado' }}"
                                               readonly>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Periodo Al
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-custom" 
                                               value="{{ $ingreso->periodo_al ? $ingreso->periodo_al->format('d/m/Y') : 'No especificado' }}"
                                               readonly>
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
                                        <label class="form-label-custom">
                                            Importe de Estimación
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="text" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   value="{{ number_format($ingreso->importe_de_estimacion ?? 0, 2) }}"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            IVA
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="text" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   value="{{ number_format($ingreso->iva ?? 0, 2) }}"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Total con IVA
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="text" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   value="{{ number_format($ingreso->total_estimacion_con_iva ?? 0, 2) }}"
                                                   readonly>
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
                                        <label class="form-label-custom">
                                            Retenciones/Sanciones
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="text" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   value="{{ number_format($ingreso->retenciones_o_sanciones ?? 0, 2) }}"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Cargos Adicionales 35%
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="text" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   value="{{ number_format($ingreso->cargos_adicionales_35_porciento ?? 0, 2) }}"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Retención 5‰
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="text" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   value="{{ number_format($ingreso->retencion_5_al_millar ?? 0, 2) }}"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Sanción Atraso Presentación
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="text" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   value="{{ number_format($ingreso->sancion_atraso_presentacion_estimacion ?? 0, 2) }}"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Sanción Atraso de Obra
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="text" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   value="{{ number_format($ingreso->sancion_atraso_de_obra ?? 0, 2) }}"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Sanción Obra Mal Ejecutada
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="text" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   value="{{ number_format($ingreso->sancion_por_obra_mal_ejecutada ?? 0, 2) }}"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Retención Atraso Programa
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="text" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   value="{{ number_format($ingreso->retencion_por_atraso_en_programa_de_obra ?? 0, 2) }}"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Amortización Anticipo
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="text" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   value="{{ number_format($ingreso->amortizacion_anticipo ?? 0, 2) }}"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Amortización con IVA
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="text" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   value="{{ number_format($ingreso->amortizacion_con_iva ?? 0, 2) }}"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Total Deducciones
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="text" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   value="{{ number_format($ingreso->total_deducciones ?? 0, 2) }}"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Estimado - Deducciones
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="text" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   value="{{ number_format($ingreso->estimado_menos_deducciones ?? 0, 2) }}"
                                                   readonly>
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
                                        <label class="form-label-custom">
                                            No. de Factura
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-custom" 
                                               value="{{ $ingreso->factura ?? 'No especificado' }}"
                                               readonly>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Fecha Factura
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-custom" 
                                               value="{{ $ingreso->fecha_factura ? $ingreso->fecha_factura->format('d/m/Y') : 'No especificado' }}"
                                               readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Fecha Elaboración
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-custom" 
                                               value="{{ $ingreso->fecha_elaboracion ? $ingreso->fecha_elaboracion->format('d/m/Y') : 'No especificado' }}"
                                               readonly>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Importe Facturado
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="text" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   value="{{ number_format($ingreso->importe_facturado ?? 0, 2) }}"
                                                   readonly>
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
                                        <label class="form-label-custom">
                                            Líquido a Cobrar
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="text" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   value="{{ number_format($ingreso->liquido_a_cobrar ?? 0, 2) }}"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Líquido Cobrado
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="text" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   value="{{ number_format($ingreso->liquido_cobrado ?? 0, 2) }}"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Por Cobrar
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="text" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   value="{{ number_format($ingreso->por_cobrar ?? 0, 2) }}"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Fecha de Cobro
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-custom" 
                                               value="{{ $ingreso->fecha_cobro ? $ingreso->fecha_cobro->format('d/m/Y') : 'No especificado' }}"
                                               readonly>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Estado
                                        </label>
                                        @php
                                            $status = $ingreso->status ?? 'pendiente';
                                            $statusText = 'Pendiente';
                                            $statusClass = 'badge bg-warning';
                                            
                                            if($status == 'pagado') {
                                                $statusText = 'Pagado';
                                                $statusClass = 'badge bg-success';
                                            } elseif($status == 'parcial') {
                                                $statusText = 'Parcial';
                                                $statusClass = 'badge bg-info';
                                            } elseif($status == 'cancelado') {
                                                $statusText = 'Cancelado';
                                                $statusClass = 'badge bg-danger';
                                            }
                                        @endphp
                                        <div class="info-display">
                                            <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Verificado
                                        </label>
                                        <div class="info-display">
                                            @php
                                                $verificado = $ingreso->verificado ?? 1;
                                                if($verificado == 1) {
                                                    echo '<span class="badge bg-warning">Pendiente (1)</span>';
                                                } elseif($verificado == 0) {
                                                    echo '<span class="badge bg-danger">Rechazado (0)</span>';
                                                } elseif($verificado == 2) {
                                                    echo '<span class="badge bg-success">Aprobado (2)</span>';
                                                }
                                            @endphp
                                        </div>
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
                                        <label class="form-label-custom">
                                            Avance Estimación (%)
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <input type="text" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   value="{{ number_format($ingreso->avance_obra_estimacion ?? 0, 2) }}"
                                                   readonly>
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Avance Real (%)
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <input type="text" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   value="{{ number_format($ingreso->avance_obra_real ?? 0, 2) }}"
                                                   readonly>
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Avance Financiero (%)
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <input type="text" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   value="{{ number_format($ingreso->porcentaje_avance_financiero ?? 0, 2) }}"
                                                   readonly>
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
                                        <label class="form-label-custom">
                                            Por Facturar
                                        </label>
                                        <div class="input-group input-group-custom">
                                            <span class="input-group-text">$</span>
                                            <input type="text" 
                                                   class="form-control form-control-custom numeric-input" 
                                                   value="{{ number_format($ingreso->por_facturar ?? 0, 2) }}"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Solo botón de regreso -->
                        <div class="d-flex justify-content-end pt-3 border-top">
                            <a href="{{ route('ingresos.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Volver al listado
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @include('footer')
</body>
</html>