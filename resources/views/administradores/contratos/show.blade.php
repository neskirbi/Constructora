<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Ver Contrato</title>
    
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
        
        .info-value {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            color: #212529;
            min-height: 38px;
        }
        
        .info-value-empty {
            color: #6c757d;
            font-style: italic;
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
        @include('administradores.sidebar')
        
        <!-- Contenido principal -->
        <main class="main-content" id="mainContent">
            @include('administradores.navbar')

            <!-- Área de contenido -->
            <div class="content-area">
                <!-- Título y botón -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                       
                    </div>
                    <div>
                        <a href="{{ route('acontratos.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Regresar
                        </a>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-1 text-gray-800">Contrato: {{ $contrato->contrato_no }}</h1>
                        <p class="text-muted mb-0">{{$contrato->obra}}</p>
                    </div>
                   
                </div>
                
                <!-- Formulario de visualización -->
                <div class="card card-formulario">
                    <div class="card-header card-header-form">
                        <h5 class="mb-0">
                            <i class="fas fa-file-contract me-2 text-primary"></i>
                            Información del Contrato
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Sección 1: Información General -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-info-circle me-2"></i>
                                Información General
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Consecutivo
                                        </label>
                                        <div class="info-value @if(empty($contrato->consecutivo)) info-value-empty @endif">
                                            {{ $contrato->consecutivo ?: 'No especificado' }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Nombre de la Obra
                                        </label>
                                        <div class="info-value @if(empty($contrato->obra)) info-value-empty @endif">
                                            {{ $contrato->obra ?: 'No especificado' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Referencia interna
                                        </label>
                                        <div class="info-value @if(empty($contrato->refinterna)) info-value-empty @endif">
                                            {{ $contrato->refinterna ?: 'No especificado' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Número de Contrato
                                        </label>
                                        <div class="info-value @if(empty($contrato->contrato_no)) info-value-empty @endif">
                                            {{ $contrato->contrato_no ?: 'No especificado' }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Empresa/Organización
                                        </label>
                                        <div class="info-value @if(empty($contrato->empresa)) info-value-empty @endif">
                                            {{ $contrato->empresa ?: 'No especificado' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Frente de Trabajo
                                        </label>
                                        <div class="info-value @if(empty($contrato->frente)) info-value-empty @endif">
                                            {{ $contrato->frente ?: 'No especificado' }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Gerencia Responsable
                                        </label>
                                        <div class="info-value @if(empty($contrato->gerencia)) info-value-empty @endif">
                                            {{ $contrato->gerencia ?: 'No especificado' }}
                                        </div>
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
                                        <label class="form-label-custom">
                                            Nombre del Cliente
                                        </label>
                                        <div class="info-value @if(empty($contrato->cliente)) info-value-empty @endif">
                                            {{ $contrato->cliente ?: 'No especificado' }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Lugar del Proyecto
                                        </label>
                                        <div class="info-value @if(empty($contrato->lugar)) info-value-empty @endif">
                                            {{ $contrato->lugar ?: 'No especificado' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Razón Social
                                        </label>
                                        <div class="info-value @if(empty($contrato->razon_social)) info-value-empty @endif">
                                            {{ $contrato->razon_social ?: 'No especificado' }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            RFC
                                        </label>
                                        <div class="info-value @if(empty($contrato->rfc)) info-value-empty @endif">
                                            {{ $contrato->rfc ?: 'No especificado' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Representante Legal
                                        </label>
                                        <div class="info-value @if(empty($contrato->representante_legal)) info-value-empty @endif">
                                            {{ $contrato->representante_legal ?: 'No especificado' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Puesto Representante Legal
                                        </label>
                                        <div class="info-value @if(empty($contrato->puesto_representante_legal)) info-value-empty @endif">
                                            {{ $contrato->puesto_representante_legal ?: 'No especificado' }}
                                        </div>
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
                                            Ubicación en Mapa
                                        </label>
                                        <div id="map" class="map-container"></div>
                                        <div class="map-coordinates">
                                            Coordenadas:
                                        </div>
                                        <div class="coordinates-display">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-2">
                                                        <label class="form-label-custom small">Latitud:</label>
                                                        <div class="info-value @if(empty($contrato->latitud)) info-value-empty @endif">
                                                            {{ $contrato->latitud ? number_format($contrato->latitud, 8) : 'No especificada' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-2">
                                                        <label class="form-label-custom small">Longitud:</label>
                                                        <div class="info-value @if(empty($contrato->longitud)) info-value-empty @endif">
                                                            {{ $contrato->longitud ? number_format($contrato->longitud, 8) : 'No especificada' }}
                                                        </div>
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
                                        <label class="form-label-custom">
                                            Calle y Número
                                        </label>
                                        <div class="info-value @if(empty($contrato->calle_numero)) info-value-empty @endif">
                                            {{ $contrato->calle_numero ?: 'No especificado' }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Colonia
                                        </label>
                                        <div class="info-value @if(empty($contrato->colonia)) info-value-empty @endif">
                                            {{ $contrato->colonia ?: 'No especificado' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Municipio/Alcaldía
                                        </label>
                                        <div class="info-value @if(empty($contrato->alcaldia_municipio)) info-value-empty @endif">
                                            {{ $contrato->alcaldia_municipio ?: 'No especificado' }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Entidad
                                        </label>
                                        <div class="info-value @if(empty($contrato->entidad)) info-value-empty @endif">
                                            {{ $contrato->entidad ?: 'No especificado' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Código Postal
                                        </label>
                                        <div class="info-value @if(empty($contrato->codigo_postal)) info-value-empty @endif">
                                            {{ $contrato->codigo_postal ?: 'No especificado' }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Teléfono
                                        </label>
                                        <div class="info-value @if(empty($contrato->telefono)) info-value-empty @endif">
                                            {{ $contrato->telefono ?: 'No especificado' }}
                                        </div>
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
                                <!-- Columna Izquierda -->
                                <div class="col-md-6">
                                   
                                    
                                    
                                </div>
                                
                                <!-- Columna Derecha -->
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Subtotal
                                        </label>
                                        <div class="info-value @if(empty($contrato->subtotal)) info-value-empty @endif">
                                            ${{ number_format($contrato->subtotal ?? 0, 2) }}
                                        </div>
                                    </div>
                                    
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            % IVA
                                        </label>
                                        <div class="info-value">
                                            @php
                                                $porcentajeIvaCalculado = 16;
                                                if(($contrato->subtotal ?? 0) > 0 && ($contrato->iva ?? 0) > 0) {
                                                    $porcentajeIvaCalculado = (($contrato->iva ?? 0) / $contrato->subtotal) * 100;
                                                }
                                            @endphp
                                            {{ number_format($porcentajeIvaCalculado, 2) }}%
                                        </div>
                                    </div>
                                    
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            IVA
                                        </label>
                                        <div class="info-value @if(empty($contrato->iva)) info-value-empty @endif">
                                            ${{ number_format($contrato->iva ?? 0, 2) }}
                                        </div>
                                    </div>
                                    
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Total
                                        </label>
                                        <div class="info-value @if(empty($contrato->total)) info-value-empty @endif">
                                            ${{ number_format($contrato->total ?? 0, 2) }}
                                        </div>
                                    </div>

                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            % Anticipo
                                        </label>
                                        <div class="info-value @if(is_null($contrato->porcentaje_anticipo)) info-value-empty @endif">
                                            @if(!is_null($contrato->porcentaje_anticipo))
                                                {{ number_format($contrato->porcentaje_anticipo, 2) }}%
                                            @else
                                                No especificado
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Anticipo
                                        </label>
                                        <div class="info-value @if(empty($contrato->monto_anticipo)) info-value-empty @endif">
                                            ${{ number_format($contrato->monto_anticipo ?? 0, 2) }}
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
                                        <label class="form-label-custom">
                                            Duración
                                        </label>
                                        <div class="info-value @if(empty($contrato->duracion)) info-value-empty @endif">
                                            {{ $contrato->duracion ?: 'No especificada' }}
                                        </div>
                                        <div class="help-text">Tiempo estimado para la obra</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Fecha del Contrato
                                        </label>
                                        <div class="info-value @if(empty($contrato->fecha_contrato)) info-value-empty @endif">
                                            {{ $contrato->fecha_contrato ? \Carbon\Carbon::parse($contrato->fecha_contrato)->format('d/m/Y') : 'No especificada' }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Fecha Inicio Obra
                                        </label>
                                        <div class="info-value @if(empty($contrato->fecha_inicio_obra)) info-value-empty @endif">
                                            {{ $contrato->fecha_inicio_obra ? \Carbon\Carbon::parse($contrato->fecha_inicio_obra)->format('d/m/Y') : 'No especificada' }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Fecha Terminación Obra
                                        </label>
                                        <div class="info-value @if(empty($contrato->fecha_terminacion_obra)) info-value-empty @endif">
                                            {{ $contrato->fecha_terminacion_obra ? \Carbon\Carbon::parse($contrato->fecha_terminacion_obra)->format('d/m/Y') : 'No especificada' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Observaciones
                                        </label>
                                        <div class="info-value @if(empty($contrato->observaciones)) info-value-empty @endif">
                                            {{ $contrato->observaciones ?: 'Sin observaciones' }}
                                        </div>
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
                                        <label class="form-label-custom">
                                            Banco
                                        </label>
                                        <div class="info-value @if(empty($contrato->banco)) info-value-empty @endif">
                                            {{ $contrato->banco ?: 'No especificado' }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Número de Cuenta
                                        </label>
                                        <div class="info-value @if(empty($contrato->no_cuenta)) info-value-empty @endif">
                                            {{ $contrato->no_cuenta ?: 'No especificado' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Sucursal
                                        </label>
                                        <div class="info-value @if(empty($contrato->sucursal)) info-value-empty @endif">
                                            {{ $contrato->sucursal ?: 'No especificado' }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            CLABE Interbancaria
                                        </label>
                                        <div class="info-value @if(empty($contrato->clabe_interbancaria)) info-value-empty @endif">
                                            {{ $contrato->clabe_interbancaria ?: 'No especificado' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Email para Facturas
                                        </label>
                                        <div class="info-value @if(empty($contrato->mail_facturas)) info-value-empty @endif">
                                            {{ $contrato->mail_facturas ?: 'No especificado' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- SECCIÓN 7: AMPLIACIONES DE MONTO -->
                        <div class="form-section">
                        <h5 class="section-title">
                            <i class="fas fa-dollar-sign me-2"></i>
                            Ampliaciones de Monto
                        </h5>
                        
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="fas fa-list me-2"></i>Historial de Ampliaciones de Monto</h6>
                            </div>
                            <div class="card-body">
                                @if($ampliacionesMonto->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Fecha de Registro</th>
                                                    <th class="text-end">Subtotal</th>
                                                    <th class="text-center">IVA %</th>
                                                    <th class="text-end">IVA Monto</th>
                                                    <th class="text-end">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($ampliacionesMonto as $index => $amp)
                                                    @php
                                                        // Calcular el monto del IVA basado en el porcentaje
                                                        $montoIVA = $amp->subtotal * ($amp->iva / 100);
                                                        $totalConIVA = $amp->subtotal + $montoIVA;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($amp->created_at)->format('d/m/Y H:i') }}</td>
                                                        <td class="text-end">${{ number_format($amp->subtotal, 2) }}</td>
                                                        <td class="text-center">{{ number_format($amp->iva, 2) }}%</td>
                                                        <td class="text-end">${{ number_format($montoIVA, 2) }}</td>
                                                        <td class="text-end">${{ number_format($totalConIVA, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                                
                                                <!-- Totales acumulados -->
                                                @php
                                                    $totalSubtotal = $ampliacionesMonto->sum('subtotal');
                                                    $totalMontoIVA = $ampliacionesMonto->sum(function($amp) {
                                                        return $amp->subtotal * ($amp->iva / 100);
                                                    });
                                                    $totalTotal = $ampliacionesMonto->sum(function($amp) {
                                                        return $amp->subtotal + ($amp->subtotal * ($amp->iva / 100));
                                                    });
                                                @endphp
                                                @if($ampliacionesMonto->count() > 0)
                                                <tr class="table-info fw-bold">
                                                    <td colspan="2" class="text-end">TOTAL ACUMULADO:</td>
                                                    <td class="text-end">${{ number_format($totalSubtotal, 2) }}</td>
                                                    <td class="text-center">-</td>
                                                    <td class="text-end">${{ number_format($totalMontoIVA, 2) }}</td>
                                                    <td class="text-end">${{ number_format($totalTotal, 2) }}</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <!-- Resumen de montos actuales del contrato -->
                                    <div class="alert alert-info mt-3 mb-0">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <small>Subtotal actual del contrato:</small><br>
                                                <strong>${{ number_format($contrato->subtotal ?? 0, 2) }}</strong>
                                            </div>
                                            <div class="col-md-4">
                                                <small>IVA actual del contrato (Monto):</small><br>
                                                <strong>${{ number_format($contrato->iva ?? 0, 2) }}</strong>
                                            </div>
                                            <div class="col-md-4">
                                                <small>Total actual del contrato:</small><br>
                                                <strong>${{ number_format($contrato->total ?? 0, 2) }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        No hay ampliaciones de monto registradas para este contrato.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                        <!-- SECCIÓN 8: AMPLIACIONES DE TIEMPO -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-clock me-2"></i>
                                Ampliaciones de Tiempo
                            </h5>
                            
                            <div class="card">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0"><i class="fas fa-history me-2"></i>Historial de Ampliaciones de Tiempo</h6>
                                </div>
                                <div class="card-body">
                                    @if($ampliacionesTiempo->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Fecha de Registro</th>
                                                        <th>Fecha Anterior</th>
                                                        <th>Nueva Fecha de Terminación</th>
                                                        <th>Días Ampliados</th>
                                                    </tr>
                                                </thead>
                                                <tbody><?php $frechaAtn = $contrato->fecha_terminacion_obra?>
                                                    @foreach($ampliacionesTiempo as $index => $amp)
                                                        <?php 
                                                            $fechaAmpliacion = \Carbon\Carbon::parse($amp->fecha_terminacion_obra);
                                                            $diasAmpliados = \Carbon\Carbon::parse($frechaAtn)->diffInDays($fechaAmpliacion);
                                                        ?>
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($amp->created_at)->format('d/m/Y H:i') }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($frechaAtn)->format('d/m/Y') }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($amp->fecha_terminacion_obra)->format('d/m/Y') }}</td>
                                                            <td class="text-center">
                                                                @if($diasAmpliados > 0)
                                                                    <span class="badge bg-success">{{ $diasAmpliados }} días</span>
                                                                @else
                                                                    <span class="badge bg-secondary">0 días</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <?php $frechaAtn = $amp->fecha_terminacion_obra?>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <!-- Información de fechas actuales -->
                                        <div class="alert alert-warning mt-3 mb-0">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <i class="fas fa-calendar-alt me-2"></i>
                                                    <strong>Fecha original de terminación:</strong><br>
                                                    {{ $contrato->fecha_terminacion_obra ? \Carbon\Carbon::parse($contrato->fecha_terminacion_obra)->format('d/m/Y') : 'No definida' }}
                                                </div>
                                                <div class="col-md-6">
                                                    <i class="fas fa-calendar-check me-2"></i>
                                                    <strong>Fecha actual de terminación:</strong><br>
                                                    @if($ampliacionesTiempo->isNotEmpty())
                                                        {{ \Carbon\Carbon::parse($ult_fecha->fecha_terminacion_obra)->format('d/m/Y') }}
                                                        <small class="text-muted">(Última ampliación)</small>
                                                    @else
                                                        {{ $contrato->fecha_terminacion_obra ? \Carbon\Carbon::parse($contrato->fecha_terminacion_obra)->format('d/m/Y') : 'No definida' }}
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            @if($ampliacionesTiempo->count() > 0)
                                                @php
                                                    $totalDiasAmpliados = 0;
                                                    $fechaOriginal = $contrato->fecha_terminacion_obra 
                                                        ? \Carbon\Carbon::parse($contrato->fecha_terminacion_obra) 
                                                        : null;
                                                    $fechaActual = $ampliacionesTiempo->first()->fecha_terminacion_obra 
                                                        ? \Carbon\Carbon::parse($ult_fecha->fecha_terminacion_obra) 
                                                        : null;
                                                    
                                                    if ($fechaOriginal && $fechaActual) {
                                                        $totalDiasAmpliados = $fechaOriginal->diffInDays($fechaActual);
                                                    }
                                                @endphp
                                                <div class="mt-2">
                                                    <strong>Total de días ampliados:</strong> 
                                                    <span class="badge bg-primary">{{ $totalDiasAmpliados }} días</span>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-info-circle me-2"></i>
                                            No hay ampliaciones de tiempo registradas para este contrato.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
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
        let defaultLat = {{ $contrato->latitud ? number_format($contrato->latitud, 8) : '19.432608' }};
        let defaultLng = {{ $contrato->longitud ? number_format($contrato->longitud, 8) : '-99.133209' }};
        
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
            
            // Si hay coordenadas existentes, poner marcador
            if ({{ $contrato->latitud ? 'true' : 'false' }} && {{ $contrato->longitud ? 'true' : 'false' }}) {
                const existingLocation = { lat: parseFloat(defaultLat), lng: parseFloat(defaultLng) };
                
                marker = new google.maps.Marker({
                    position: existingLocation,
                    map: map,
                    draggable: false,
                    animation: google.maps.Animation.DROP
                });
                
                map.setCenter(existingLocation);
                map.setZoom(16);
            }
        }
    </script>
</body>
</html>