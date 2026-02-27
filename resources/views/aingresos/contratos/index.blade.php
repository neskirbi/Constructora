<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Contratos</title>
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
                            <h1 class="h3 mb-1 text-gray-800"><i class="fas fa-file-contract me-2"></i> Contratos</h1>
                            <p class="text-muted mb-0">Gestión de contratos de obra</p>
                        </div>
                        <div>
                            <button class="btn btn-primary" onclick="window.location.href='{{ route('contratos.create') }}'">
                                <i class="fas fa-plus me-1"></i> Agregar Contrato
                            </button>
                        </div>
                    </div>
                    
                    <!-- Filtros -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <form action="{{ route('contratos.index') }}" method="GET" class="row g-3">
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" 
                                            class="form-control border-start-0" 
                                            name="search" 
                                            placeholder="Buscar por obra, contrato, cliente, ubicación..." 
                                            value="{{ request('search') }}">
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
                    </div>
                    
                    <!-- Lista de Contratos en Tarjetas -->
                    @if($contratos->count() > 0)
                        @foreach($contratos as $contrato)
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <!-- Primera fila: Consecutivo, Ref. Interna, No. Contrato -->
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
                                
                                <!-- Segunda fila: Cliente (solo, para texto largo) -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <small class="text-muted d-block">Cliente</small>
                                        <span class="fw-bold" title="{{ $contrato->cliente ?? 'No especificado' }}">
                                            {{ $contrato->cliente ?? 'No especificado' }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Tercera fila: Obra (solo, para texto largo) -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <small class="text-muted d-block">Obra</small>
                                        <span class="fw-bold" title="{{ $contrato->obra ?? 'Sin nombre de obra' }}">
                                            {{ $contrato->obra ?? 'Sin nombre de obra' }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Cuarta fila: Datos adicionales (Frente, Duración) -->
                                <div class="row mb-3">
                                    <div class="col-md-6 mb-2 mb-md-0">
                                        <small class="text-muted d-block">Frente</small>
                                        <span title="{{ $contrato->frente ?? 'No especificado' }}">
                                            {{ $contrato->frente ?? 'No especificado' }}
                                        </span>
                                    </div>
                                   
                                </div>
                                
                                
                                <!-- Quinta fila: Fechas y Duración -->
                                <div class="row mb-4">
                                    <div class="col-md-4 mb-2 mb-md-0">
                                        <small class="text-muted d-block">Fecha Contrato</small>
                                        <span>
                                            @if($contrato->fecha_contrato)
                                                {{ date('d/m/Y', strtotime($contrato->fecha_contrato)) }}
                                            @else
                                                No definida
                                            @endif
                                        </span>
                                    </div>
                                    <div class="col-md-3 mb-2 mb-md-0">
                                        <small class="text-muted d-block">Inicio Obra</small>
                                        <span>
                                            @if($contrato->fecha_inicio_obra)
                                                {{ date('d/m/Y', strtotime($contrato->fecha_inicio_obra)) }}
                                            @else
                                                No definida
                                            @endif
                                        </span>
                                    </div>
                                    <div class="col-md-3 mb-2 mb-md-0">
                                        <small class="text-muted d-block">Fin Obra</small>
                                        <span>
                                            @if($contrato->fecha_terminacion_obra)
                                                {{ date('d/m/Y', strtotime($contrato->fecha_terminacion_obra)) }}
                                            @else
                                                No definida
                                            @endif
                                        </span>
                                    </div>
                                    <div class="col-md-2">
                                        <small class="text-muted d-block">Duración</small>
                                        <span>{{ $contrato->duracion ?? 'No especificada' }}</span>
                                    </div>
                                </div>
                                
                                <!-- Montos en tarjetas -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-4">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body text-center">
                                                <div class="mb-2">
                                                    <i class="fas fa-file-invoice-dollar fa-2x text-secondary"></i>
                                                </div>
                                                <h6 class="text-muted mb-2">Subtotal</h6>
                                                <h5 class="fw-bold mb-0">${{ number_format($contrato->subtotal ?? 0, 2) }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body text-center">
                                                <div class="mb-2">
                                                    <i class="fas fa-hand-holding-usd fa-2x text-secondary"></i>
                                                </div>
                                                <h6 class="text-muted mb-2">IVA</h6>
                                                <h5 class="fw-bold mb-0">${{ number_format($contrato->iva ?? 0, 2) }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($contrato->total)
                                    <div class="col-md-4">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body text-center">
                                                <div class="mb-2">
                                                    <i class="fas fa-chart-line fa-2x text-secondary"></i>
                                                </div>
                                                <h6 class="text-muted mb-2">Total</h6>
                                                <h5 class="fw-bold mb-0">${{ number_format($contrato->total ?? 0, 2) }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                
                                <!-- Botones de acción -->
                                <div class="d-flex justify-content-end gap-2">
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
                    @else
                        <!-- Estado vacío -->
                        <div class="text-center py-5 bg-light rounded">
                            <div class="mb-3">
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
        });
    </script>
</body>
</html>