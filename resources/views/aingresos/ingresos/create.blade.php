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
                                                       value="{{ old('importe_estimacion') }}"
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
                                                       value="{{ old('iva') }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       min="0"
                                                       noformat>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group-custom">
                                            <label for="importe_estimacion" class="form-label-custom">
                                                Importe IVA
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">$</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="importe_iva" 
                                                       name="importe_iva" 
                                                       value="{{ old('importe_iva') }}"
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
                                                    <input class="form-check-input" type="checkbox" id="aplicar_sicv">
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
                                                    value="{{ old('sicv_cop') }}"
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
                                                    <input class="form-check-input" type="checkbox" id="aplicar_srcop">
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
                                                    value="{{ old('srcop_cdmx') }}"
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
                                                    value="{{ old('retencion_5_al_millar') }}"
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
                                                       value="{{ old('sancion_atrazo_presentacion_estimacion') }}"
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
                                                       value="{{ old('retencion_por_atraso_en_programa_obra') }}"
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
                                                       value="{{ old('retenciones_o_sanciones') }}"
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
                                            <label for="amortizacion_iva" class="form-label-custom">
                                                Amortización I.V.A.
                                            </label>
                                            <div class="input-group input-group-custom">
                                                <span class="input-group-text">%</span>
                                                <input type="number" 
                                                       class="form-control form-control-custom numeric-input" 
                                                       id="amortizacion_iva" 
                                                       name="amortizacion_iva" 
                                                       value="{{ old('amortizacion_iva') }}"
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
                                                       value="{{ old('total_amortizacion') }}"
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
                                                       value="{{ old('estimado_menos_deducciones') }}"
                                                       step="0.01"
                                                       placeholder="0.00"
                                                       readonly>
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
                                                    name="status" 
                                                    readonly>
                                                <option value="">Seleccionar status...</option>
                                                <option value="pagado" {{ old('status') == 'pagado' ? 'selected' : '' }}>Pagado</option>
                                                <option value="en_tramite" {{ old('status') == 'en_tramite' ? 'selected' : 'selected' }}>En Trámite</option>
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
    const totalEstimacion = parseFloat($('#total_estimacion_con_iva').val()) || 0;
    const resultado = totalEstimacion * 0.02; // 2%
    
    $('#sicv_cop').val(resultado.toFixed(2));
    
    calcularRetencionesSanciones();
}

// Función para calcular 1.5% SRCOP
function calcularSRCOP() {
    const totalEstimacion = parseFloat($('#total_estimacion_con_iva').val()) || 0;
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
    var amortizacionConIva = parseFloat($('#amortizacion_iva').val()) || 0;
    
    var total = amortizacionAnticipo + amortizacionConIva;
    
    $('#total_amortizacion').val(total.toFixed(2));
    dispararEventos('#total_amortizacion');
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
   
</body>
</html>

<!--
1.-Se captura el Importe y que se selecciones el % de iva ej. 16% y obtener el importe del iva
2.-1.5% y 2 % se deben de poder seleccionar y calcularse en automatico el monto y las operaciones estan bien 
3.- poder guardar sin los datos de factura y despues poder editar y agregar los datos de la factura 

Se quitan por que se calcularia 
POR COBRAR(Del total del contrato - lo cobrado )

POR FACTURAR es el importe del contrato menos el importe de la factura mas iva (sin deducciones)
quitar por estimar


si seleccionamos un contrato que se cargue la informacion de la ultima factura y que se pueda editar 
-->