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
        
        .info-display {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 0.75rem 1rem;
            min-height: 38px;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
        }
        
        .form-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #426ec1;
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
        
        .resumen-montos {
            background-color: #f0f4f8;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
            border: 1px dashed #426ec1;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 0.75rem;
        }
        
        .info-label {
            font-weight: 600;
            min-width: 180px;
            color: #495057;
        }
        
        .info-value {
            color: #212529;
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
                        <h1 class="h3 mb-1 text-gray-800">Ingreso</h1>
                    </div>
                    <div>
                        <a href="{{ url('ingresos') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Regresar
                        </a>
                    </div>
                </div>
                
                <!-- Información del estado y botones de acción -->
                <div class="alert alert-info mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-info-circle me-2"></i>
                            Estado de verificación:
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="me-3">
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
                </div>
                
                <!-- Formulario de visualización -->
                <div class="card card-formulario">
                    <div class="card-header card-header-form">
                        <h5 class="mb-0">
                            <i class="fas fa-eye me-2 text-primary"></i>
                            Detalle del Ingreso
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
                                <div class="col-md-12" style="display:none;">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">Contrato</label>
                                        <div class="info-display">
                                            @if($contrato)
                                                <strong>{{ $contrato->contrato_no ?? 'N/A' }}</strong> - {{ $contrato->obra ?? '' }}
                                                <br>
                                                <small class="text-muted">Cliente: {{ $contrato->cliente ?? 'Sin cliente' }}</small>
                                            @else
                                                <span class="text-danger">Contrato no encontrado</span>
                                            @endif
                                        </div>
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
                        
                        <!-- Sección 2: Información Básica del Ingreso -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-info-circle me-2"></i>
                                Información Básica
                            </h5>
                          
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">Estimación No.</label>
                                        <div class="info-display">
                                            {{ $ingreso->no_estimacion ?? 'No especificado' }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">Periodo</label>
                                        <div class="info-display">
                                            @if($ingreso->periodo_del && $ingreso->periodo_al)
                                                Del {{ $ingreso->periodo_del->format('d/m/Y') }} al {{ $ingreso->periodo_al->format('d/m/Y') }}
                                            @else
                                                No especificado
                                            @endif
                                        </div>
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
                                <div class="col-md-3">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">Importe de Estimación</label>
                                        <div class="info-display">
                                            ${{ number_format($ingreso->importe_estimacion ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">IVA</label>
                                        <div class="info-display">
                                            {{ number_format($ingreso->iva ?? 0, 2) }}%
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">Importe IVA</label>
                                        <div class="info-display">
                                            ${{ number_format($ingreso->importe_iva ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">Total Estimación con IVA</label>
                                        <div class="info-display">
                                            ${{ number_format($ingreso->total_estimacion_con_iva ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">2.0% SICV - COP</label>
                                        <div class="info-display">
                                            ${{ number_format($ingreso->sicv_cop ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">1.5% SRCOP - CDMX</label>
                                        <div class="info-display">
                                            ${{ number_format($ingreso->srcop_cdmx ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">Retención 5 al Millar</label>
                                        <div class="info-display">
                                            ${{ number_format($ingreso->retencion_5_al_millar ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">Sanción Atraso Presentación</label>
                                        <div class="info-display">
                                            ${{ number_format($ingreso->sancion_atrazo_presentacion_estimacion ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">Sanción Atraso de Obra</label>
                                        <div class="info-display">
                                            ${{ number_format($ingreso->sancion_atraso_de_obra ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">Sanción por Obra Mal Ejecutada</label>
                                        <div class="info-display">
                                            ${{ number_format($ingreso->sancion_por_obra_mal_ejecutada ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">Retención por Atraso</label>
                                        <div class="info-display">
                                            ${{ number_format($ingreso->retencion_por_atraso_en_programa_obra ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <!-- Vacío -->
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">Total Retenciones/Sanciones</label>
                                        <div class="info-display">
                                            ${{ number_format($ingreso->retenciones_o_sanciones ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">
                            
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">Amortización Anticipo</label>
                                        <div class="info-display">
                                            ${{ number_format($ingreso->amortizacion_anticipo ?? 0, 2) }}
                                        </div>
                                    </div>
                               </div>
                                
                                <div class="col-md-2">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">% I.V.A.</label>
                                        <div class="info-display">
                                            %{{ number_format($ingreso->amortizacion_iva ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">Amortización I.V.A.</label>
                                        <div class="info-display">
                                            ${{ number_format($ingreso->amortizacion_anticipo*($ingreso->amortizacion_iva/100) ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">Total Amortización</label>
                                        <div class="info-display">
                                            ${{ number_format($ingreso->total_amortizacion ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <!-- Vacío -->
                                </div>
                                
                                <div class="col-md-4">
                                    <!-- Vacío -->
                                </div>
                               
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">Líquido a Cobrar</label>
                                        <div class="info-display fw-bold text-primary">
                                            ${{ number_format($ingreso->estimado_menos_deducciones ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- SECCIÓN 4: FACTURACIÓN -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-file-invoice-dollar me-2"></i>
                                Facturación
                            </h5>
                                
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">Factura</label>
                                        <div class="info-display">
                                            {{ $ingreso->factura ?? 'No facturado' }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">Fecha Factura</label>
                                        <div class="info-display">
                                            {{ $ingreso->fecha_factura ? $ingreso->fecha_factura->format('d/m/Y') : 'No especificada' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- RESUMEN DE MONTOS DE LA ESTIMACIÓN -->
                            <div class="resumen-montos mt-3">
                                <h6 class="fw-bold mb-3"><i class="fas fa-calculator me-2"></i>Montos de la Estimación</h6>
                                
                                <!-- Fila 1: Importes Básicos -->
                                <div class="row">
                                    <div class="col-md-3 <?php echo ($ingreso->importe_estimacion ?? 0) == 0 ? 'd-none' : ''; ?>">
                                        <small class="text-muted d-block">Importe Estimación</small>
                                        <strong>${{ number_format($ingreso->importe_estimacion ?? 0, 2) }}</strong>
                                    </div>
                                    <div class="col-md-3 <?php echo ($ingreso->iva ?? 0) == 0 ? 'd-none' : ''; ?>">
                                        <small class="text-muted d-block">IVA %</small>
                                        <strong>{{ number_format($ingreso->iva ?? 0, 2) }}%</strong>
                                    </div>
                                    <div class="col-md-3 <?php echo ($ingreso->importe_iva ?? 0) == 0 ? 'd-none' : ''; ?>">
                                        <small class="text-muted d-block">Importe IVA</small>
                                        <strong>${{ number_format($ingreso->importe_iva ?? 0, 2) }}</strong>
                                    </div>
                                    <div class="col-md-3 <?php echo ($ingreso->total_estimacion_con_iva ?? 0) == 0 ? 'd-none' : ''; ?>">
                                        <small class="text-muted d-block">Total con IVA</small>
                                        <strong>${{ number_format($ingreso->total_estimacion_con_iva ?? 0, 2) }}</strong>
                                    </div>
                                </div>
                                
                                <hr class="my-2">
                                
                                <!-- Fila 2: SICV, SRCOP, Retención 5 al Millar -->
                                <div class="row">
                                    <div class="col-md-3 <?php echo ($ingreso->sicv_cop ?? 0) == 0 ? 'd-none' : ''; ?>">
                                        <small class="text-muted d-block">SICV - COP (2%)</small>
                                        <strong>${{ number_format($ingreso->sicv_cop ?? 0, 2) }}</strong>
                                    </div>
                                    <div class="col-md-3 <?php echo ($ingreso->srcop_cdmx ?? 0) == 0 ? 'd-none' : ''; ?>">
                                        <small class="text-muted d-block">SRCOP - CDMX (1.5%)</small>
                                        <strong>${{ number_format($ingreso->srcop_cdmx ?? 0, 2) }}</strong>
                                    </div>
                                    <div class="col-md-3 <?php echo ($ingreso->retencion_5_al_millar ?? 0) == 0 ? 'd-none' : ''; ?>">
                                        <small class="text-muted d-block">Ret. 5 al Millar</small>
                                        <strong>${{ number_format($ingreso->retencion_5_al_millar ?? 0, 2) }}</strong>
                                    </div>
                                </div>
                                
                                <!-- Fila 3: Sanciones -->
                                <div class="row">
                                    <div class="col-md-3 <?php echo ($ingreso->sancion_atrazo_presentacion_estimacion ?? 0) == 0 ? 'd-none' : ''; ?>">
                                        <small class="text-muted d-block">Sanción Atraso Presentación</small>
                                        <strong>${{ number_format($ingreso->sancion_atrazo_presentacion_estimacion ?? 0, 2) }}</strong>
                                    </div>
                                    <div class="col-md-3 <?php echo ($ingreso->sancion_atraso_de_obra ?? 0) == 0 ? 'd-none' : ''; ?>">
                                        <small class="text-muted d-block">Sanción Atraso de Obra</small>
                                        <strong>${{ number_format($ingreso->sancion_atraso_de_obra ?? 0, 2) }}</strong>
                                    </div>
                                    <div class="col-md-3 <?php echo ($ingreso->sancion_por_obra_mal_ejecutada ?? 0) == 0 ? 'd-none' : ''; ?>">
                                        <small class="text-muted d-block">Sanción por Obra Mal Ejecutada</small>
                                        <strong>${{ number_format($ingreso->sancion_por_obra_mal_ejecutada ?? 0, 2) }}</strong>
                                    </div>
                                    <div class="col-md-3 <?php echo ($ingreso->retenciones_o_sanciones ?? 0) == 0 ? 'd-none' : ''; ?>">
                                        <small class="text-muted d-block">Total Ret/Sanc</small>
                                        <strong>${{ number_format($ingreso->retenciones_o_sanciones ?? 0, 2) }}</strong>
                                    </div>
                                </div>
                                
                                <!-- Fila 4: Amortizaciones -->
                                <div class="row mt-2">
                                    <div class="col-md-3 <?php echo ($ingreso->amortizacion_anticipo ?? 0) == 0 ? 'd-none' : ''; ?>">
                                        <small class="text-muted d-block">Amort. Anticipo</small>
                                        <strong>${{ number_format($ingreso->amortizacion_anticipo ?? 0, 2) }}</strong>
                                    </div>
                                    <div class="col-md-3 <?php echo ($ingreso->amortizacion_iva ?? 0) == 0 ? 'd-none' : ''; ?>">
                                        <small class="text-muted d-block">% IVA</small>
                                        <strong>%{{ number_format($ingreso->amortizacion_iva ?? 0, 2) }}</strong>
                                    </div>
                                    <?php 
                                        $amorIva = ($ingreso->amortizacion_anticipo ?? 0) * (($ingreso->amortizacion_iva ?? 0) / 100);
                                    ?>
                                    <div class="col-md-3 <?php echo $amorIva == 0 ? 'd-none' : ''; ?>">
                                        <small class="text-muted d-block">Amort. IVA</small>
                                        <strong>${{ number_format($amorIva, 2) }}</strong>
                                    </div>
                                    <div class="col-md-3 <?php echo ($ingreso->total_amortizacion ?? 0) == 0 ? 'd-none' : ''; ?>">
                                        <small class="text-muted d-block">Total Amort.</small>
                                        <strong>${{ number_format($ingreso->total_amortizacion ?? 0, 2) }}</strong>
                                    </div>
                                </div>
                                
                                <!-- Fila 5: Líquido a Cobrar (siempre visible) -->
                                <div class="row mt-2">
                                    <div class="col-md-9"></div>
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">Líquido a Cobrar</small>
                                        <strong class="text-primary">${{ number_format($ingreso->estimado_menos_deducciones ?? 0, 2) }}</strong>
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
                                        <label class="form-label-custom">Líquido a Cobrar</label>
                                        <div class="info-display">
                                            ${{ number_format($ingreso->estimado_menos_deducciones ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">Líquido Cobrado</label>
                                        <div class="info-display">
                                            ${{ number_format($ingreso->liquido_cobrado ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">Fecha Cobro</label>
                                        <div class="info-display">
                                            {{ $ingreso->fecha_cobro ? $ingreso->fecha_cobro->format('d/m/Y') : 'No cobrado' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">POR COBRAR</label>
                                        <div class="info-display bg-light fw-bold text-danger">
                                            ${{ number_format($ingreso->por_cobrar ?? 0, 2) }}
                                        </div>
                                        <small class="help-text">Líquido a cobrar - Líquido cobrado</small>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">POR FACTURAR</label>
                                        <div class="info-display bg-light fw-bold text-info">
                                            ${{ number_format($ingreso->por_facturar ?? 0, 2) }}
                                        </div>
                                        <small class="help-text">Del monto total del contrato menos lo facturado</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sección 6: Estado -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-info-circle me-2"></i>
                                Estado
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">Status</label>
                                        <div class="info-display">
                                            @if($ingreso->status == 'pagado')
                                                <span class=" ">Pagado</span>
                                            @elseif($ingreso->status == 'en_tramite')
                                                <span class=" ">En Trámite</span>
                                            @else
                                                No especificado
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                        </div>

                        <div class="row justify-content-end"> 
                            @if($ingreso->verificado == 1)
                            <div class="col-auto">
                                <!-- Botón Rechazar -->
                                <button type="button" class="btn btn-outline-danger me-2" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#rechazarModal">
                                    <i class="fas fa-times me-1"></i> Rechazar
                                </button>
                                
                                <!-- Botón Aprobar -->
                                <button type="button" class="btn btn-success" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#aprobarModal">
                                    <i class="fas fa-check me-1"></i> Aprobar
                                </button>
                            </div>

                            @elseif($ingreso->verificado == 0 )
                            <div class="col-auto">
                                <button type="button" class="btn btn-success" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#aprobarModal">
                                    <i class="fas fa-check me-1"></i> Aprobar
                                </button>
                            </div>

                            @elseif($ingreso->verificado == 2)
                            <div class="col-auto">
                                <button type="button" class="btn btn-outline-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#rechazarModal">
                                    <i class="fas fa-times me-1"></i> Rechazar
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

    <!-- Modal Aprobar -->
    <div class="modal fade" id="aprobarModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 bg-light">
                    <h5 class="modal-title text-success">
                        <i class="fas fa-check-circle me-2"></i>
                        Confirmar Aprobación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5 class="mb-2">¿Aprobar este ingreso?</h5>
                        <p class="text-muted mb-0">
                            Estimación: <strong>{{ $ingreso->no_estimacion ?? 'N/A' }}</strong>
                        </p>
                        <p class="text-success small mt-2">
                            <i class="fas fa-info-circle me-1"></i>
                            El estado cambiará a "Aprobado"
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
                            <i class="fas fa-check me-1"></i> Sí, Aprobar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('footer')
</body>
</html>