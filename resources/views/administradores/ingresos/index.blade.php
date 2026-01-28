<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Ingresos</title>
</head>
<style>
     .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
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
</style>
<body>
    <div class="main-container">
        @include('toast.toasts')
        @include('administradores.sidebar')
        
        <!-- Contenido principal -->
        <main class="main-content" id="mainContent">
            @include('administradores.navbar')

            <!-- Área de contenido -->
            <div class="content-area">
                <div class="container-fluid py-4">
                <!-- Título y botón -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-1 text-gray-800">
                        <i class="fas fa-users me-2"></i>Ingresos
                    </h1>
                    <!--<a class="btn btn-primary" href="{{url('ingresos/create')}}">
                        <i class="fas fa-plus me-2"></i>Nuevo Ingreso
                    </a>-->
                </div>

                <!-- Filtros -->
               <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="search-container">
                                <form action="{{ route('aingresos.index') }}" method="GET" class="row">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-search text-muted"></i>
                                            </span>
                                            <input type="text" 
                                                class="form-control border-start-0" 
                                                name="search" 
                                                placeholder="Buscar por estimación, factura, contrato..." 
                                                value="{{ request('search') }}"
                                                title="Buscar en: estimación, factura, área, contrato">
                                            @if(request()->has('search') && !empty(request('search')))
                                            <a href="{{ route('aingresos.index', array_merge(request()->except('search'), ['search' => ''])) }}" 
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
                                    <a href="{{ route('aingresos.index') }}" class="btn btn-sm btn-outline-danger ms-3">
                                        <i class="fas fa-times me-1"></i> Limpiar búsqueda
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                 <div class="card">
                    <div class="card-body p-0">
                        <!-- Vista de tarjetas (una por fila) -->
                        @if($ingresos->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($ingresos as $ingreso)
                            @php
                                $verificado = $ingreso->verificado ?? 'pendiente';
                                $statusClass = 'status-badge status-pendiente';
                                $statusText = 'Pendiente';
                                
                                if($verificado == '2') {
                                    $statusClass = 'status-badge status-verificado';
                                    $statusText = 'Verificado';
                                } else if($verificado == '0') {
                                     $statusClass = 'status-badge status-rechazado';
                                    $statusText = 'Rechazado';
                                }
                            @endphp
                            
                            <div class="list-group-item p-0 border-bottom">
                                <div class="card border-0">
                                    <div class="card-body">
                                        <!-- Fila 1: Cabecera -->
                                        <div class="row mb-3 align-items-center">
                                            <div class="col-md-8">
                                                <h6 class="card-title mb-1 text-primary">
                                                    {{ $ingreso->estimacion ?? 'N/A' }}
                                                </h6>
                                                <p class="text-muted mb-0 small">
                                                    <i class="far fa-calendar-alt me-1"></i>
                                                    {{ $ingreso->fecha_elaboracion ? date('d/m/Y', strtotime($ingreso->fecha_elaboracion)) : 'Sin fecha' }}
                                                </p>
                                            </div>
                                            <div class="col-md-4 text-md-end">
                                                <span class="badge {{ $statusClass }} border px-3 py-1">
                                                    {{ $statusText }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- Fila 2: Información básica -->
                                        <div class="row mb-3">
                                            <div class="col-md-3 mb-2">
                                                <small class="text-muted d-block">Contrato</small>
                                                <div class="d-flex align-items-center">
                                                    @if($ingreso->contrato)
                                                    <span class="d-block">
                                                        {{ $ingreso->contrato->contrato_no ?? 'N/A' }}
                                                    </span>
                                                    @else
                                                    <span class="text-danger">No encontrado</span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3 mb-2">
                                                <small class="text-muted d-block">Cliente</small>
                                                <span class="d-block">
                                                    {{ $ingreso->contrato->cliente ?? 'Sin cliente' }}
                                                </span>
                                            </div>
                                            
                                            <div class="col-md-3 mb-2">
                                                <small class="text-muted d-block">Área</small>
                                                <span class="d-block">{{ $ingreso->area ?? 'No especificada' }}</span>
                                            </div>
                                            
                                            <div class="col-md-3 mb-2">
                                                <small class="text-muted d-block">Período</small>
                                                <span class="d-block">
                                                    @if($ingreso->periodo_del && $ingreso->periodo_al)
                                                    {{ date('d/m/Y', strtotime($ingreso->periodo_del)) }} - 
                                                    {{ date('d/m/Y', strtotime($ingreso->periodo_al)) }}
                                                    @else
                                                    No especificado
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- Fila 3: Montos discretos -->
                                        <div class="row mb-3">
                                            <div class="col-md-2 mb-2">
                                                <small class="text-muted d-block">Factura</small>
                                                <span class="d-block">
                                                    @if($ingreso->factura)
                                                    {{ $ingreso->factura }}
                                                    @else
                                                    <span class="text-muted">Sin factura</span>
                                                    @endif
                                                </span>
                                            </div>
                                            
                                            <div class="col-md-2 mb-2">
                                                <small class="text-muted d-block">Importe</small>
                                                <span class="d-block">
                                                    ${{ number_format($ingreso->importe_de_estimacion ?? 0, 2) }}
                                                </span>
                                            </div>
                                            
                                            <div class="col-md-2 mb-2">
                                                <small class="text-muted d-block">IVA</small>
                                                <span class="d-block">
                                                    ${{ number_format($ingreso->iva ?? 0, 2) }}
                                                </span>
                                            </div>
                                            
                                            <div class="col-md-2 mb-2">
                                                <small class="text-muted d-block">Total con IVA</small>
                                                <span class="d-block text-success">
                                                    ${{ number_format($ingreso->total_estimacion_con_iva ?? 0, 2) }}
                                                </span>
                                            </div>
                                            
                                            <div class="col-md-4 mb-2">
                                                <small class="text-muted d-block">Líquido a Cobrar</small>
                                                <span class="d-block">
                                                    ${{ number_format($ingreso->liquido_a_cobrar ?? 0, 2) }}
                                                 
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- Fila 4: Botones al final -->
                                        <div class="row mt-3 pt-3 border-top">
                                            <div class="col-12 text-end">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('aingresos.show', $ingreso->id) }}" 
                                                       class="btn btn-outline-primary btn-sm"
                                                       title="Ver">
                                                        <i class="fas fa-eye me-1"></i> Ver
                                                    </a>
                                                    @if($ingreso->verificado == 1)
                                                    <button onclick="confirmDelete('{{ $ingreso->id }}', '{{ addslashes($ingreso->estimacion ?? 'Ingreso') }}')" 
                                                            class="btn btn-outline-danger btn-sm"
                                                            title="Eliminar">
                                                        <i class="fas fa-trash me-1"></i> Eliminar
                                                    </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- Paginación -->
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">
                                        Mostrando {{ $ingresos->firstItem() }} - {{ $ingresos->lastItem() }} de {{ $ingresos->total() }} registros
                                    </small>
                                </div>
                                <nav aria-label="Page navigation">
                                    {{ $ingresos->appends(request()->query())->links('pagination::bootstrap-4') }}
                                </nav>
                            </div>
                        </div>
                        @else
                            <!-- Estado vacío -->
                            <div class="text-center py-5">
                                <div class="empty-state-icon mb-4">
                                    <i class="fas fa-chart-line fa-4x text-muted"></i>
                                </div>
                                <h4 class="text-muted mb-3">No se encontraron ingresos</h4>
                                <p class="text-muted mb-4">
                                    @if(request()->has('search'))
                                    No hay resultados que coincidan con tu búsqueda.
                                    @else
                                    No hay ingresos registrados aún.
                                    @endif
                                </p>
                                @if(request()->has('search'))
                                <a href="{{ route('aingresos.index') }}" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-times me-1"></i> Limpiar búsqueda
                                </a>
                                @endif
                                <button class="btn btn-primary" onclick="window.location.href='{{ route('aingresos.create') }}'">
                                    <i class="fas fa-plus me-1"></i> Registrar primer ingreso
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
                
            </div>
        </main>
    </div>

   
    
    <!-- Modal de confirmación para eliminar -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar el ingreso <strong id="ingresoNombre"></strong>?</p>
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
     @include('footer')
    <script>
        // Función para confirmar eliminación
        function confirmDelete(id, nombre) {
            document.getElementById('ingresoNombre').textContent = nombre;
            
            const form = document.getElementById('deleteForm');
            form.action = '{{url("/ingresos")}}/'+id;
            
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