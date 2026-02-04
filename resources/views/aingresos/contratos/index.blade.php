<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Contratos</title>
    
    <!-- Estilos personalizados -->
    <style>
        .card-contrato {
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            margin-bottom: 20px;
            height: 100%;
        }
        
        .card-contrato:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
        
        .info-label {
            font-weight: 600;
            color: #495057;
            min-width: 120px;
        }
        
        .search-container {
            background: #fff;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }
        
        .stats-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            margin-bottom: 1rem;
        }
        
        .stats-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #0d6efd;
        }
        
        .stats-label {
            font-size: 0.875rem;
            color: #6c757d;
        }
        
        .monto-total {
            font-size: 1.25rem;
            font-weight: bold;
            color: #198754;
        }
        
        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
            background: #f8f9fa;
            border-radius: 10px;
            border: 2px dashed #dee2e6;
        }
        
        .badge-estado {
            background-color: #6c757d;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
        }
        
        .obra-nombre {
            font-size: 1.25rem;
            font-weight: 600;
            color: #002153;
            margin-bottom: 0.5rem;
            cursor: pointer;
            position: relative;
        }
        
        .info-row {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-bottom: 1rem;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .info-item {
            flex: 1;
            min-width: 200px;
        }
        
        .info-title {
            font-weight: 600;
            color: #495057;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }
        
        .info-value {
            color: #212529;
            font-size: 0.95rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: pointer;
            position: relative;
        }
        
        .info-value:hover::after {
            content: attr(data-fulltext);
            position: absolute;
            bottom: 100%;
            left: 0;
            background: #333;
            color: white;
            padding: 0.5rem;
            border-radius: 4px;
            font-size: 0.875rem;
            white-space: normal;
            max-width: 300px;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .actions-container {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
            padding-top: 1rem;
            border-top: 1px solid #e0e0e0;
        }
        
        .obra-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
        }
        
        .contrato-badge {
            background-color: #426ec1;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .monto-container {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.75rem;
        }
        
        .monto-item {
            display: flex;
            flex-direction: column;
        }
        
        .monto-label {
            font-size: 0.75rem;
            color: #6c757d;
        }
        
        .monto-valor {
            font-weight: 600;
            color: #198754;
        }
        
        .monto-anticipo {
            color: #0d6efd;
        }
        
        @media (max-width: 768px) {
            .info-row {
                gap: 1rem;
            }
            
            .info-item {
                min-width: 150px;
                flex: 0 0 calc(50% - 0.5rem);
            }
            
            .obra-header {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .actions-container {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .monto-container {
                flex-wrap: wrap;
            }
        }
        
        @media (max-width: 576px) {
            .info-item {
                flex: 0 0 100%;
            }
        }
        
        .tooltip-inner {
            max-width: 300px;
            text-align: left;
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
                <div class="container-fluid py-4">
                    <!-- Título y botón -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h1 class="h3 mb-1 text-gray-800"><i class="fas fa-file-contract me'2"></i> Contratos</h1>
                            <p class="text-muted mb-0">Gestión de contratos de obra</p>
                        </div>
                        <div>
                            <button class="btn btn-primary" onclick="window.location.href='{{ route('contratos.create') }}'">
                                <i class="fas fa-plus me-1"></i> Agregar Contrato
                            </button>
                        </div>
                    </div>
                    
                    
                    
                    <!-- Filtros -->
                    <div class="search-container">
                        <form action="{{ route('contratos.index') }}" method="GET" class="row">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" 
                                        class="form-control border-start-0" 
                                        name="search" 
                                        placeholder="Buscar por obra, contrato, cliente, ubicación..." 
                                        value="{{ request('search') }}"
                                        title="Buscar en: obra, número de contrato, cliente, ubicación, empresa">
                                    @if(request()->has('search') && !empty(request('search')))
                                    <a href="{{ route('contratos.index', array_merge(request()->except('search'), ['search' => ''])) }}" 
                                    class="input-group-text bg-light text-danger" 
                                    title="Limpiar búsqueda">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i> Buscar
                                    </button>
                                </div>
                            </div>
                        </form>
                        
                        @if(request()->has('search'))
                        <div class="mt-3">
                            <small class="text-muted">
                                Resultados para: <strong>"{{ request('search') }}"</strong>
                            </small>
                            <a href="{{ route('contratos.index') }}" class="btn btn-sm btn-outline-danger ms-3">
                                <i class="fas fa-times me-1"></i> Limpiar búsqueda
                            </a>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Lista de Contratos en Tarjetas -->
                    @if($contratos->count() > 0)
                        @foreach($contratos as $contrato)
                        <div class="card card-contrato">
                            <div class="card-body">
                                <!-- Encabezado con nombre de obra y número de contrato -->
                                <div class="obra-header">
                                    <div class="flex-grow-1">
                                        <div class="obra-nombre" 
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="top" 
                                            title="{{ $contrato->obra ?? 'Sin nombre de obra' }}">
                                            {{ Str::limit($contrato->obra ?? 'Sin nombre de obra', 100) }}
                                        </div>
                                        <div class="contrato-badge d-inline-block">
                                            <i class="fas fa-file-contract me-1"></i>
                                            {{ $contrato->contrato_no ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Fila de información (debajo del nombre) -->
                                <div class="info-row">
                                    <div class="info-item">
                                        <div class="info-title">Cliente</div>
                                        <div class="info-value" 
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="top" 
                                            title="{{ $contrato->cliente ?? 'No especificado' }}">
                                            {{ Str::limit($contrato->cliente ?? 'No especificado', 40) }}
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-title">Empresa</div>
                                        <div class="info-value" 
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="top" 
                                            title="{{ $contrato->empresa ?? 'No especificado' }}">
                                            {{ Str::limit($contrato->empresa ?? 'No especificado', 40) }}
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-title">Ubicación</div>
                                        <div class="info-value" 
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="top" 
                                            title="{{ $contrato->lugar ?? 'No especificada' }}">
                                            {{ Str::limit($contrato->lugar ?? 'No especificada', 40) }}
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-title">Fecha Contrato</div>
                                        <div class="info-value">
                                            @if($contrato->fecha_contrato)
                                                {{ date('d/m/Y', strtotime($contrato->fecha_contrato)) }}
                                            @else
                                                No definida
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Fila adicional con más información -->
                                <div class="info-row">
                                    <div class="info-item">
                                        <div class="info-title">Inicio Obra</div>
                                        <div class="info-value">
                                            @if($contrato->fecha_inicio_obra)
                                                {{ date('d/m/Y', strtotime($contrato->fecha_inicio_obra)) }}
                                            @else
                                                No definida
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-title">Fin Obra</div>
                                        <div class="info-value">
                                            @if($contrato->fecha_terminacion_obra)
                                                {{ date('d/m/Y', strtotime($contrato->fecha_terminacion_obra)) }}
                                            @else
                                                No definida
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-title">Duración</div>
                                        <div class="info-value">
                                            {{ $contrato->duracion ?? 'No especificada' }}
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-title">Frente</div>
                                        <div class="info-value" 
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="top" 
                                            title="{{ $contrato->frente ?? 'No especificado' }}">
                                            {{ Str::limit($contrato->frente ?? 'No especificado', 30) }}
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Montos en tarjetas profesionales -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body text-center p-4">
                                                <div class="mb-3">
                                                    <i class="fas fa-file-invoice-dollar fa-2x" style="color: #282828;"></i>
                                                </div>
                                                <h6 class="text-muted mb-2">Subtotal</h6>
                                                <h3 class="fw-bold mb-0 text-dark">${{ number_format($contrato->subtotal ?? 0, 2) }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body text-center p-4">
                                                <div class="mb-3">
                                                    <i class="fas fa-hand-holding-usd fa-2x" style="color: #282828;"></i>
                                                </div>
                                                <h6 class="text-muted mb-2">IVA</h6>
                                                <h3 class="fw-bold mb-0 text-dark">${{ number_format($contrato->iva ?? 0, 2) }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($contrato->total)
                                    <div class="col-md-4">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body text-center p-4">
                                                <div class="mb-3">
                                                    <i class="fas fa-chart-line fa-2x" style="color: #282828;"></i>
                                                </div>
                                                <h6 class="text-muted mb-2">Total</h6>
                                                <h3 class="fw-bold mb-0 text-dark">${{ number_format($contrato->total ?? 0, 2) }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                
                                <!-- Botones de acción -->
                                <div class="actions-container">
                                    <a href="{{ route('contratos.show', $contrato->id) }}" 
                                    class="btn btn-primary"
                                    title="Ver detalles">
                                        <i class="fas fa-eye me-1"></i> Ver
                                    </a>
                                    <button onclick="confirmDelete('{{ $contrato->id }}', '{{ addslashes($contrato->obra ?? 'Contrato') }}')" 
                                            class="btn btn-danger"
                                            title="Eliminar contrato">
                                        <i class="fas fa-trash me-1"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        <!-- Paginación -->
                        <div class="pagination-container">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">
                                        Mostrando {{ $contratos->firstItem() }} - {{ $contratos->lastItem() }} de {{ $contratos->total() }} registros
                                    </small>
                                </div>
                                <nav aria-label="Page navigation">
                                    {{ $contratos->appends(request()->query())->links('pagination::bootstrap-4') }}
                                </nav>
                            </div>
                        </div>
                    @else
                        <!-- Estado vacío -->
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-file-contract fa-4x text-muted"></i>
                            </div>
                            <h4 class="text-muted mb-3">No se encontraron contratos</h4>
                            <p class="text-muted mb-4">
                                @if(request()->has('search'))
                                No hay resultados que coincidan con tu búsqueda.
                                @else
                                No hay contratos registrados aún.
                                @endif
                            </p>
                            @if(request()->has('search'))
                            <a href="{{ route('contratos.index') }}" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-times me-1"></i> Limpiar búsqueda
                            </a>
                            @endif
                            <button class="btn btn-primary" onclick="window.location.href='{{ route('contratos.create') }}'">
                                <i class="fas fa-plus me-1"></i> Agregar primer contrato
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    @include('footer')
    
    <!-- Modal de confirmación para eliminar -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar el contrato <strong id="obraNombre"></strong>?</p>
                    <p class="text-danger small mb-0">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i> Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Función para confirmar eliminación
        function confirmDelete(id, nombre) {
            document.getElementById('obraNombre').textContent = nombre;
            
            const form = document.getElementById('deleteForm');
            form.action = '{{url("/contratos")}}/'+id;
            
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }
        
        // Auto-focus en campo de búsqueda si hay parámetros
        document.addEventListener('DOMContentLoaded', function() {
            @if(request()->has('search'))
            document.querySelector('input[name="search"]').focus();
            @endif
            
            // Inicializar tooltips de Bootstrap
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
</body>
</html>