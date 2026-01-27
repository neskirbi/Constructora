<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Ingresos</title>
    
    <!-- Estilos personalizados -->
    <style>
        .card-ingreso {
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            margin-bottom: 20px;
            height: 100%;
        }
        
        .card-ingreso:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
        
        .search-container {
            background: #fff;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
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
        
        .ingreso-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
        }
        
        .ingreso-badge {
            background-color: #426ec1;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
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
        }
        
        .monto-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-bottom: 0.75rem;
        }
        
        .monto-item {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            flex: 1;
            min-width: 150px;
        }
        
        .monto-label {
            font-size: 0.75rem;
            color: #6c757d;
        }
        
        .monto-valor {
            font-weight: 600;
            color: #198754;
        }
        
        .actions-container {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
            padding-top: 1rem;
            border-top: 1px solid #e0e0e0;
        }
        
        .periodo-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #002153;
            margin-bottom: 0.5rem;
            cursor: pointer;
            position: relative;
        }
        
        @media (max-width: 768px) {
            .info-row {
                gap: 1rem;
            }
            
            .info-item {
                min-width: 150px;
                flex: 0 0 calc(50% - 0.5rem);
            }
            
            .ingreso-header {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .actions-container {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .monto-container {
                flex-direction: column;
            }
            
            .monto-item {
                min-width: 100%;
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
        
        .tabla-container {
            background: #fff;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
            overflow-x: auto;
        }
        
        .tabla-cabecera {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }
        
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
            background-color: #d1ecf1;
            color: #0c5460;
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
                        <h1 class="h3 mb-1 text-gray-800">Ingresos</h1>
                        <p class="text-muted mb-0">Histórico de ingresos reportados</p>
                    </div>
                    <div>
                        <button class="btn btn-primary" onclick="window.location.href='{{ route('ingresos.create') }}'">
                            <i class="fas fa-plus me-1"></i> Nuevo Ingreso
                        </button>
                    </div>
                </div>
                
                <!-- Filtros -->
                <div class="search-container">
                    <form action="{{ route('ingresos.index') }}" method="GET" class="row">
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
                                <a href="{{ route('ingresos.index', array_merge(request()->except('search'), ['search' => ''])) }}" 
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
                        <a href="{{ route('ingresos.index') }}" class="btn btn-sm btn-outline-danger ms-3">
                            <i class="fas fa-times me-1"></i> Limpiar búsqueda
                        </a>
                    </div>
                    @endif
                </div>
                
                <!-- Tabla de Ingresos -->
                @if($ingresos->count() > 0)
                <div class="tabla-container">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="tabla-cabecera">
                                <tr>
                                    <th width="120">Estimación</th>
                                    <th>Contrato</th>
                                    <th>Área</th>
                                    <th width="150">Periodo</th>
                                    <th width="120">Factura</th>
                                    <th width="120">Importe Estimación</th>
                                    <th width="120">IVA</th>
                                    <th width="120">Total con IVA</th>
                                    <th width="120">Líquido a Cobrar</th>
                                    <th width="100">Estado</th>
                                    <th width="100">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ingresos as $ingreso)
                                <tr>
                                    <td>
                                        <strong>{{ $ingreso->estimacion ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $ingreso->fecha_elaboracion ? date('d/m/Y', strtotime($ingreso->fecha_elaboracion)) : 'Sin fecha' }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($ingreso->contrato)
                                        <span class="d-block">{{ $ingreso->contrato->contrato_no ?? 'N/A' }}</span>
                                        <small class="text-muted">
                                            {{ Str::limit($ingreso->contrato->cliente ?? 'Sin cliente', 30) }}
                                        </small>
                                        @else
                                        <span class="text-danger">Contrato no encontrado</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ Str::limit($ingreso->area ?? 'No especificada', 30) }}
                                    </td>
                                    <td>
                                        @if($ingreso->periodo_del)
                                        <span class="d-block">Del: {{ date('d/m/Y', strtotime($ingreso->periodo_del)) }}</span>
                                        @endif
                                        @if($ingreso->periodo_al)
                                        <span class="d-block">Al: {{ date('d/m/Y', strtotime($ingreso->periodo_al)) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ingreso->factura)
                                        <strong>{{ $ingreso->factura }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $ingreso->fecha_factura ? date('d/m/Y', strtotime($ingreso->fecha_factura)) : 'Sin fecha' }}
                                        </small>
                                        @else
                                        <span class="text-muted">Sin factura</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <strong>${{ number_format($ingreso->importe_de_estimacion ?? 0, 2) }}</strong>
                                    </td>
                                    <td class="text-end">
                                        ${{ number_format($ingreso->iva ?? 0, 2) }}
                                    </td>
                                    <td class="text-end">
                                        <strong style="color: #198754;">${{ number_format($ingreso->total_estimacion_con_iva ?? 0, 2) }}</strong>
                                    </td>
                                    <td class="text-end">
                                        @if($ingreso->liquido_a_cobrar)
                                        <strong style="color: #0d6efd;">${{ number_format($ingreso->liquido_a_cobrar, 2) }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            Cobrado: ${{ number_format($ingreso->liquido_cobrado ?? 0, 2) }}
                                        </small>
                                        @else
                                        <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $verificado = $ingreso->verificado ?? 'pendiente';
                                            $statusClass = 'status-pendiente';
                                            $statusText = 'Pendiente';
                                            
                                            if($verificado == '2') {
                                                $statusClass = 'status-verificado';
                                                $statusText = 'Verificado';
                                            } else
                                            if($verificado == '0') {
                                                $statusClass = 'status-verificado';
                                                $statusText = 'Verificado';
                                            }
                                        @endphp
                                        <span class="status-badge {{ $statusClass }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($ingreso->verificado!=1)
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('ingresos.show', $ingreso->id) }}" 
                                               class="btn btn-sm btn-outline-primary"
                                               title="Ver/Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="confirmDelete('{{ $ingreso->id }}', '{{ addslashes($ingreso->estimacion ?? 'Ingreso') }}')" 
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginación -->
                    <div class="pagination-container mt-4">
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
                </div>
                @else
                    <!-- Estado vacío -->
                    <div class="empty-state">
                        <div class="empty-state-icon">
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
                        <a href="{{ route('ingresos.index') }}" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-times me-1"></i> Limpiar búsqueda
                        </a>
                        @endif
                        <button class="btn btn-primary" onclick="window.location.href='{{ route('ingresos.create') }}'">
                            <i class="fas fa-plus me-1"></i> Registrar primer ingreso
                        </button>
                    </div>
                @endif
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