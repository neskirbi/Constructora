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
        
        .status-verificado {
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
        
        /* Estilos de secciones que me pediste */
        .form-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #426ec1;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .info-item {
            padding: 0.75rem 0;
        }
        
        .info-item .info-label {
            font-size: 0.8rem;
            color: #6c757d;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }
        
        .info-text {
            font-size: 0.95rem;
            color: #212529;
            margin: 0;
            padding: 0.5rem 0;
        }
        
        .info-value {
            font-weight: 500;
            color: #212529;
        }
        
        .info-value.monto {
            color: #198754;
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            .form-section {
                padding: 1rem;
            }
            
            .card-header-form {
                padding: 1rem;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        @include('toast.toasts')
        @include('administradores.sidebar')
        
        <!-- Contenido principal -->
        <main class="main-content" id="mainContent">
            @include('administradores.navbar')

            <!-- Área de contenido -->
            <div class="content-area">
                <!-- Título y botón -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-1 text-gray-800">Ver Ingreso</h1>
                        <p class="text-muted mb-0">ID: {{ $ingreso->id }}</p>
                    </div>
                    <div>
                        <a href="{{ route('aingresos.index') }}" class="btn btn-outline-secondary">
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
                                    echo '<span class="status-badge status-verificado ms-2">Verificado (2)</span>';
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
                            
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Contrato</div>
                                    <p class="info-text">
                                        @if($contrato)
                                            <strong>{{ $contrato->contrato_no ?? 'N/A' }}</strong>
                                            <br>
                                            <span class="text-muted" style="font-size: 0.9rem;">
                                                {{ $contrato->obra ?? '' }}
                                                <br>
                                                Cliente: {{ $contrato->cliente ?? 'Sin cliente' }}
                                            </span>
                                        @else
                                            <span class="text-danger">Contrato no encontrado</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sección 2: Información Básica -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-info-circle me-2"></i>
                                Información Básica
                            </h5>
                            
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Estimación No.</div>
                                    <p class="info-text info-value">{{ $ingreso->no_estimacion ?? 'N/A' }}</p>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Periodo Del</div>
                                    <p class="info-text info-value">
                                        {{ $ingreso->periodo_del ? date('d/m/Y', strtotime($ingreso->periodo_del)) : 'No especificado' }}
                                    </p>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Periodo Al</div>
                                    <p class="info-text info-value">
                                        {{ $ingreso->periodo_al ? date('d/m/Y', strtotime($ingreso->periodo_al)) : 'No especificado' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sección 3: Montos de la Estimación -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-calculator me-2"></i>
                                Montos de la Estimación
                            </h5>
                            
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Importe de Estimación</div>
                                    <p class="info-text info-value monto">${{ number_format($ingreso->importe_estimacion ?? 0, 2) }}</p>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">IVA</div>
                                    <p class="info-text info-value monto">${{ number_format($ingreso->iva ?? 0, 2) }}</p>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Total con IVA</div>
                                    <p class="info-text info-value monto">${{ number_format($ingreso->total_estimacion_con_iva ?? 0, 2) }}</p>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Retenciones/Sanciones</div>
                                    <p class="info-text info-value">${{ number_format($ingreso->retenciones_o_sanciones ?? 0, 2) }}</p>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Estimado - Deducciones</div>
                                    <p class="info-text info-value monto">${{ number_format($ingreso->estimado_menos_deducciones ?? 0, 2) }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sección 4: Facturación -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-file-invoice-dollar me-2"></i>
                                Facturación
                            </h5>
                            
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Factura</div>
                                    <p class="info-text info-value">{{ $ingreso->factura ?? 'No especificado' }}</p>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Fecha Factura</div>
                                    <p class="info-text info-value">
                                        {{ $ingreso->fecha_factura ? date('d/m/Y', strtotime($ingreso->fecha_factura)) : 'No especificado' }}
                                    </p>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Importe Facturado</div>
                                    <p class="info-text info-value monto">${{ number_format($ingreso->importe_facturado ?? 0, 2) }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sección 5: Deducciones Específicas -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-minus-circle me-2"></i>
                                Deducciones Específicas
                            </h5>
                            
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">3.5% Cargos Adicionales</div>
                                    <p class="info-text info-value">${{ number_format($ingreso->cargos_adicionales_3_5 ?? 0, 2) }}</p>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Retención 5 al Millar</div>
                                    <p class="info-text info-value">${{ number_format($ingreso->retencion_5_al_millar ?? 0, 2) }}</p>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Sanción Atraso Presentación</div>
                                    <p class="info-text info-value">${{ number_format($ingreso->sancion_atrazo_presentacion_estimacion ?? 0, 2) }}</p>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Sanción Atraso de Obra</div>
                                    <p class="info-text info-value">${{ number_format($ingreso->sancion_atraso_de_obra ?? 0, 2) }}</p>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Sanción Obra Mal Ejecutada</div>
                                    <p class="info-text info-value">${{ number_format($ingreso->sancion_por_obra_mal_ejecutada ?? 0, 2) }}</p>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Retención Atraso Programa</div>
                                    <p class="info-text info-value">${{ number_format($ingreso->retencion_por_atraso_en_programa_obra ?? 0, 2) }}</p>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Amortización Anticipo</div>
                                    <p class="info-text info-value">${{ number_format($ingreso->amortizacion_anticipo ?? 0, 2) }}</p>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Amortización con IVA</div>
                                    <p class="info-text info-value">${{ number_format($ingreso->amortizacion_con_iva ?? 0, 2) }}</p>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Total Deducciones</div>
                                    <p class="info-text info-value monto">${{ number_format($ingreso->total_deducciones ?? 0, 2) }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sección 6: Cobros -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-money-bill-wave me-2"></i>
                                Cobros
                            </h5>
                            
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Líquido a Cobrar</div>
                                    <p class="info-text info-value monto">${{ number_format($ingreso->liquido_a_cobrar ?? 0, 2) }}</p>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Líquido Cobrado</div>
                                    <p class="info-text info-value monto">${{ number_format($ingreso->liquido_cobrado ?? 0, 2) }}</p>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Fecha Cobro</div>
                                    <p class="info-text info-value">
                                        {{ $ingreso->fecha_cobro ? date('d/m/Y', strtotime($ingreso->fecha_cobro)) : 'No especificado' }}
                                    </p>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">POR COBRAR</div>
                                    <p class="info-text info-value">${{ number_format($ingreso->por_cobrar ?? 0, 2) }}</p>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">POR FACTURAR</div>
                                    <p class="info-text info-value">${{ number_format($ingreso->por_facturar ?? 0, 2) }}</p>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Por Estimar</div>
                                    <p class="info-text info-value">${{ number_format($ingreso->por_estimar ?? 0, 2) }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sección 7: Avance de Obra -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-chart-line me-2"></i>
                                Avance de Obra
                            </h5>
                            
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Avance Obra Estimación (%)</div>
                                    <p class="info-text info-value">{{ number_format($ingreso->avance_obra_estimacion ?? 0, 2) }}%</p>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Avance Obra Real (%)</div>
                                    <p class="info-text info-value">{{ number_format($ingreso->avance_obra_real ?? 0, 2) }}%</p>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">% Avance Financiero</div>
                                    <p class="info-text info-value">{{ number_format($ingreso->porcentaje_avance_financiero ?? 0, 2) }}%</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sección 8: Estado -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-info-circle me-2"></i>
                                Estado
                            </h5>
                            
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Status</div>
                                    <p class="info-text info-value">{{ ucfirst($ingreso->status ?? 'No especificado') }}</p>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Fecha Creación</div>
                                    <p class="info-text info-value">
                                        {{ $ingreso->created_at ? date('d/m/Y H:i', strtotime($ingreso->created_at)) : 'No especificado' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Botones de acción -->
                        <div class="d-flex justify-content-between pt-3 border-top">
                            <a href="{{ route('aingresos.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Volver al listado
                            </a>
                            
                            @if($ingreso->verificado == 1)
                            <div>
                                <!-- Botón Rechazar -->
                                <button type="button" class="btn btn-outline-danger me-2" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#rechazarModal">
                                    <i class="fas fa-times me-1"></i> Rechazar
                                </button>
                                
                                <!-- Botón Verificar -->
                                <button type="button" class="btn btn-success" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#verificarModal">
                                    <i class="fas fa-check me-1"></i> Verificar
                                </button>
                            </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </main>
    </div>

   <!-- Modal Rechazar -->
    <div class="modal fade" id="rechazarModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 bg-light">
                    <h5 class="modal-title text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Confirmar Rechazo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>
                        <h5 class="mb-2">¿Rechazar este ingreso?</h5>
                        <p class="text-muted mb-0">
                            Estimación: <strong>{{ $ingreso->no_estimacion ?? 'N/A' }}</strong>
                        </p>
                        <p class="text-danger small mt-2">
                            <i class="fas fa-info-circle me-1"></i>
                            El estado cambiará a "Rechazado"
                        </p>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </button>
                    <form method="POST" action="{{ route('aingresos.update', $ingreso->id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="verificado" value="0">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-ban me-1"></i> Sí, Rechazar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Verificar -->
    <div class="modal fade" id="verificarModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 bg-light">
                    <h5 class="modal-title text-success">
                        <i class="fas fa-check-circle me-2"></i>
                        Confirmar Verificación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5 class="mb-2">¿Verificar este ingreso?</h5>
                        <p class="text-muted mb-0">
                            Estimación: <strong>{{ $ingreso->no_estimacion ?? 'N/A' }}</strong>
                        </p>
                        <p class="text-success small mt-2">
                            <i class="fas fa-info-circle me-1"></i>
                            El estado cambiará a "Verificado"
                        </p>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </button>
                    <form method="POST" action="{{ route('aingresos.update', $ingreso->id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="verificado" value="2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-1"></i> Sí, Verificar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('footer')
</body>
</html>