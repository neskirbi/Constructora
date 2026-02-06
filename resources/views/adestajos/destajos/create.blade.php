<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Nuevo Ingreso</title>
    
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
                        <h1 class="h3 mb-1 text-gray-800">Nuevo Ingreso</h1>
                        <p class="text-muted mb-0">Registrar nuevo ingreso de contrato</p>
                    </div>
                    <div>
                        <a href="{{ route('ingresos.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Regresar
                        </a>
                    </div>
                </div>
                
                <!-- Formulario de creación -->
                <div class="card card-formulario">
                    <div class="card-header card-header-form">
                        <h5 class="mb-0">
                            <i class="fas fa-plus-circle me-2 text-success"></i>
                            Registrar Nuevo Ingreso
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('ingresos.store') }}" method="POST" id="ingresoForm">
                            @csrf
                            
                            <!-- Sección 1: Información del Contrato -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-file-contract me-2"></i>
                                    Información del Contrato
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group-custom">
                                            <label for="id_contrato" class="form-label-custom required-label">
                                                Contrato
                                            </label>
                                            <select class="form-control form-control-custom" 
                                                    id="id_contrato" 
                                                    name="id_contrato" 
                                                    value="{{ old('id_contrato') }}"
                                                    required>
                                                <option value="">Seleccionar contrato...</option>
                                                @foreach($contratos as $contrato)
                                                <option value="{{ $contrato->id }}" {{ old('id_contrato') == $contrato->id ? 'selected' : '' }}>
                                                    {{ $contrato->contrato_no }} - {{ Str::limit($contrato->obra, 50) }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('id_contrato')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="no_estimacion" class="form-label-custom required-label">
                                                No. de Estimación
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="no_estimacion" 
                                                   name="no_estimacion" 
                                                   value="{{ old('no_estimacion') }}"
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
                                                   value="{{ old('periodo_del') }}">
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
                                                   class="form-control form-control-custom" 
                                                   id="periodo_al" 
                                                   name="periodo_al" 
                                                   value="{{ old('periodo_al') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sección 2: Montos de la Estimación -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-calculator me-2"></i>
                                    Montos de la Estimación
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-4">
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
                                                       value="{{ old('importe_estimacion') }}"
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
                                            <label for="total_estimacion_con_iva" class="form-label-custom">
                                                Total Estimación con IVA
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="total_estimacion_con_iva" 
                                                       name="total_estimacion_con_iva" 
                                                       value="{{ old('total_estimacion_con_iva') }}"
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
                                            <label for="retenciones_o_sanciones" class="form-label-custom">
                                                Retenciones o Sanciones
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="retenciones_o_sanciones" 
                                                       name="retenciones_o_sanciones" 
                                                       value="{{ old('retenciones_o_sanciones') }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0">
                                            </div>
                                        </div>
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
                                                       value="{{ old('estimado_menos_deducciones') }}"
                                                       step="0.01"
                                                       placeholder="0.00">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sección 3: Avance de Obra -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-chart-line me-2"></i>
                                    Avance de Obra
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="avance_obra_estimacion" class="form-label-custom">
                                                Avance Obra Estimación (%)
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="avance_obra_estimacion" 
                                                       name="avance_obra_estimacion" 
                                                       value="{{ old('avance_obra_estimacion') }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="avance_obra_real" class="form-label-custom">
                                                Avance Obra Real (%)
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="avance_obra_real" 
                                                       name="avance_obra_real" 
                                                       value="{{ old('avance_obra_real') }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="porcentaje_avance_financiero" class="form-label-custom">
                                                % Avance Financiero
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="porcentaje_avance_financiero" 
                                                       name="porcentaje_avance_financiero" 
                                                       value="{{ old('porcentaje_avance_financiero') }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sección 4: Deducciones Específicas -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-minus-circle me-2"></i>
                                    Deducciones Específicas
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="cargos_adicionales_3_5" class="form-label-custom">
                                                3.5% Cargos Adicionales
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="cargos_adicionales_3_5" 
                                                       name="cargos_adicionales_3_5" 
                                                       value="{{ old('cargos_adicionales_3_5') }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="retencion_5_al_millar" class="form-label-custom">
                                                Retención 5 al Millar
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="retencion_5_al_millar" 
                                                       name="retencion_5_al_millar" 
                                                       value="{{ old('retencion_5_al_millar') }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0">
                                            </div>
                                        </div>
                                    </div>
                                    
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
                                                       value="{{ old('sancion_atrazo_presentacion_estimacion') }}"
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
                                            <label for="sancion_atraso_de_obra" class="form-label-custom">
                                                Sanción Atraso de Obra
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="sancion_atraso_de_obra" 
                                                       name="sancion_atraso_de_obra" 
                                                       value="{{ old('sancion_atraso_de_obra') }}"
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
                                                       value="{{ old('sancion_por_obra_mal_ejecutada') }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0">
                                            </div>
                                        </div>
                                    </div>
                                    
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
                                                       value="{{ old('retencion_por_atraso_en_programa_obra') }}"
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
                                            <label for="amortizacion_anticipo" class="form-label-custom">
                                                Amortización Anticipo
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="amortizacion_anticipo" 
                                                       name="amortizacion_anticipo" 
                                                       value="{{ old('amortizacion_anticipo') }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="amortizacion_con_iva" class="form-label-custom">
                                                Amortización con I.V.A.
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="amortizacion_con_iva" 
                                                       name="amortizacion_con_iva" 
                                                       value="{{ old('amortizacion_con_iva') }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="total_deducciones" class="form-label-custom">
                                                Total Deducciones
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="total_deducciones" 
                                                       name="total_deducciones" 
                                                       value="{{ old('total_deducciones') }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sección 5: Facturación -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-file-invoice-dollar me-2"></i>
                                    Facturación
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="factura" class="form-label-custom">
                                                Factura
                                            </label>
                                            <input type="text" 
                                                   class="form-control form-control-custom" 
                                                   id="factura" 
                                                   name="factura" 
                                                   value="{{ old('factura') }}"
                                                   placeholder="Ej: FAC-001">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="fecha_factura" class="form-label-custom">
                                                Fecha Factura
                                            </label>
                                            <input type="date" 
                                                   class="form-control form-control-custom" 
                                                   id="fecha_factura" 
                                                   name="fecha_factura" 
                                                   value="{{ old('fecha_factura') }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-custom">
                                            <label for="importe_facturado" class="form-label-custom">
                                                Importe Facturado
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="importe_facturado" 
                                                       name="importe_facturado" 
                                                       value="{{ old('importe_facturado') }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sección 6: Cobros -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <i class="fas fa-money-bill-wave me-2"></i>
                                    Cobros
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
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="liquido_a_cobrar" 
                                                       name="liquido_a_cobrar" 
                                                       value="{{ old('liquido_a_cobrar') }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0">
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
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="liquido_cobrado" 
                                                       name="liquido_cobrado" 
                                                       value="{{ old('liquido_cobrado') }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0">
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
                                                   value="{{ old('fecha_cobro') }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="por_cobrar" class="form-label-custom">
                                                POR COBRAR
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="por_cobrar" 
                                                       name="por_cobrar" 
                                                       value="{{ old('por_cobrar') }}"
                                                       step="0.01"
                                                       placeholder="0.00">
                                            </div>
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
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="por_facturar" 
                                                       name="por_facturar" 
                                                       value="{{ old('por_facturar') }}"
                                                       step="0.01"
                                                       placeholder="0.00">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group-custom">
                                            <label for="por_estimar" class="form-label-custom">
                                                Por Estimar
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="por_estimar" 
                                                       name="por_estimar" 
                                                       value="{{ old('por_estimar') }}"
                                                       step="0.01"
                                                       placeholder="0.00">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sección 7: Estado -->
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
                                                <option value="pagado" {{ old('status') == 'pagado' ? 'selected' : '' }}>Pagado</option>
                                                <option value="en_tramite" {{ old('status') == 'en_tramite' ? 'selected' : '' }}>En Trámite</option>
                                            </select>
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
                                    <a href="{{ route('ingresos.index') }}" class="btn btn-cancel btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-submit btn-primary">
                                        <i class="fas fa-save me-1"></i> Guardar Ingreso
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
    document.addEventListener('DOMContentLoaded', function() {
        // Campos de DEDUCCIÓN (sanciones/retrociones)
        const deduccionFields = [
            'retenciones_o_sanciones',
            'cargos_adicionales_3_5',
            'retencion_5_al_millar',
            'sancion_atrazo_presentacion_estimacion',
            'sancion_atraso_de_obra',
            'sancion_por_obra_mal_ejecutada',
            'retencion_por_atraso_en_programa_obra'
            // NOTA: amortizacion_anticipo y amortizacion_con_iva NO se incluyen
            // porque no son deducciones por incumplimiento
        ];

        // Campos de AMORTIZACIÓN (parte del pago normal)
        const amortizacionFields = [
            'amortizacion_anticipo',
            'amortizacion_con_iva'
        ];

        // Campo total de deducciones (bloqueado)
        const totalDeduccionesInput = document.getElementById('total_deducciones');
        
        // Hacer el campo de total de deducciones de solo lectura
        if (totalDeduccionesInput) {
            totalDeduccionesInput.readOnly = true;
            totalDeduccionesInput.style.backgroundColor = '#f8f9fa';
            totalDeduccionesInput.style.cursor = 'not-allowed';
        }

        // Función para calcular el total de DEDUCCIONES (solo sanciones/retrociones)
        function calcularTotalDeducciones() {
            let total = 0;
            
            deduccionFields.forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (field && field.value) {
                    total += parseFloat(field.value) || 0;
                }
            });
            
            if (totalDeduccionesInput) {
                // Formatear a 2 decimales
                totalDeduccionesInput.value = total.toFixed(2);
            }
        }

        // Agregar event listeners a los campos de DEDUCCIÓN
        deduccionFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (field) {
                field.addEventListener('input', calcularTotalDeducciones);
                field.addEventListener('blur', function() {
                    if (this.value) {
                        this.value = parseFloat(this.value).toFixed(2);
                    }
                    calcularTotalDeducciones();
                });
            }
        });

        // Calcular inicialmente si hay valores precargados
        calcularTotalDeducciones();
    });
</script>
</body>
</html>