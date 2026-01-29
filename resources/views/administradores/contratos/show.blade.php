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
                        <h1 class="h3 mb-1 text-gray-800">{{$contrato->obra}}</h1>
                        <p class="text-muted mb-0">Contrato: {{ $contrato->contrato_no }}</p>
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
                        
                        <!-- Sección 4: Montos Financieros -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-dollar-sign me-2"></i>
                                Montos Financieros
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Concepto
                                        </label>
                                        <div class="info-value @if(empty($contrato->concepto)) info-value-empty @endif">
                                            {{ $contrato->concepto ?: 'No especificado' }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Subtotal
                                        </label>
                                        <div class="info-value @if(empty($contrato->subtotal)) info-value-empty @endif">
                                            ${{ number_format($contrato->subtotal ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            IVA
                                        </label>
                                        <div class="info-value @if(empty($contrato->iva)) info-value-empty @endif">
                                            ${{ number_format($contrato->iva ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Total
                                        </label>
                                        <div class="info-value @if(empty($contrato->total)) info-value-empty @endif">
                                            ${{ number_format($contrato->total ?? 0, 2) }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group-custom">
                                        <label class="form-label-custom">
                                            Monto Anticipo
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