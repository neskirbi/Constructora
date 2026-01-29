<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Ingresos</title>
    
    <style>
        .card-ingreso {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
            overflow: hidden;
            border-left: 4px solid #426ec1;
        }
        
        .card-ingreso:hover {
            box-shadow: 0 6px 16px rgba(0,0,0,0.12);
            transform: translateY(-2px);
        }
        
        .card-ingreso-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid #e0e0e0;
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-ingreso-body {
            padding: 1.5rem;
        }
        
        .ingreso-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #002153;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .ingreso-title i {
            color: #426ec1;
        }
        
        .badge-status {
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        
        .badge-pendiente {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .badge-verificado {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .badge-rechazado {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }
        
        .info-item {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
        }
        
        .info-label {
            font-size: 0.8rem;
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .info-label i {
            font-size: 0.9rem;
            color: #426ec1;
        }
        
        .info-value {
            font-size: 1rem;
            color: #212529;
            font-weight: 500;
            line-height: 1.4;
        }
        
        .info-value.monto {
            font-weight: 600;
            color: #198754;
            font-size: 1.1rem;
        }
        
        .info-value.monto-pequeno {
            font-size: 0.95rem;
            color: #495057;
        }
        
        .search-container {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            margin-bottom: 2rem;
            border: 1px solid #e0e0e0;
        }
        
        .btn-action {
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }
        
        .btn-action.btn-view {
            background-color: #426ec1;
            border-color: #426ec1;
            color: white;
        }
        
        .btn-action.btn-view:hover {
            background-color: #3459a1;
            border-color: #3459a1;
        }
        
        .btn-action.btn-delete {
            background-color: transparent;
            border-color: #dc3545;
            color: #dc3545;
        }
        
        .btn-action.btn-delete:hover {
            background-color: #dc3545;
            color: white;
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            border: 2px dashed #ced4da;
        }
        
        .empty-state-icon {
            font-size: 4rem;
            color: #adb5bd;
            margin-bottom: 1.5rem;
        }
        
        .empty-state-title {
            color: #6c757d;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .empty-state-text {
            color: #868e96;
            margin-bottom: 2rem;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .fecha-badge {
            background: #e7f1ff;
            color: #426ec1;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            border: 1px solid #b6d4fe;
        }
        
        .highlight {
            background-color: #fff9db;
            padding: 2px 4px;
            border-radius: 3px;
            font-weight: 500;
        }
        
        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .card-ingreso-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .ingreso-title {
                font-size: 1rem;
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
                <div class="container-fluid py-4">
                    <!-- Título y botón (estilo original) -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h1 class="h3 mb-1 text-gray-800"><i class="fas fa-money-bill-wave me-2"></i>Ingresos</h1>
                            <p class="text-muted mb-0">Gestión de ingresos por contrato</p>
                        </div>                    
                        <a class="btn btn-primary" href="{{url('ingresos/create')}}">
                            <i class="fas fa-plus me-2"></i>Nuevo Ingreso
                        </a>
                    </div>

                    <!-- Barra de búsqueda (estilo original) -->
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

                    <!-- Lista de ingresos -->
                    @if($ingresos->count() > 0)
                    <div class="row">
                        @foreach($ingresos as $ingreso)
                        @php
                            $verificado = $ingreso->verificado ?? 'pendiente';
                            $statusClass = 'badge-status badge-pendiente';
                            $statusText = 'Pendiente';
                            
                            if($verificado == '2') {
                                $statusClass = 'badge-status badge-verificado';
                                $statusText = 'Verificado';
                            } else if($verificado == '0') {
                                $statusClass = 'badge-status badge-rechazado';
                                $statusText = 'Rechazado';
                            }
                            
                            // Obtener contrato manualmente
                            $contrato = \App\Models\Contrato::find($ingreso->id_contrato);
                            
                            // Formatear fechas
                            $fechaCreacion = $ingreso->created_at ? date('d/m/Y H:i', strtotime($ingreso->created_at)) : 'N/A';
                            $fechaPeriodo = ($ingreso->periodo_del && $ingreso->periodo_al) 
                                ? date('d/m/Y', strtotime($ingreso->periodo_del)) . ' - ' . date('d/m/Y', strtotime($ingreso->periodo_al))
                                : 'No especificado';
                        @endphp
                        
                        <div class="col-12 mb-3">
                            <div class="card card-ingreso">
                                <!-- Header de la tarjeta -->
                                <div class="card-ingreso-header">
                                    <div>
                                        <h2 class="ingreso-title">
                                            <i class="fas fa-file-invoice-dollar"></i>
                                            Estimación: {{ $ingreso->no_estimacion ?? 'N/A' }}
                                            @if($ingreso->factura)
                                            <span class="ms-2" style="font-size: 0.9rem; color: #6c757d;">
                                                <i class="fas fa-receipt me-1"></i>Factura: {{ $ingreso->factura }}
                                            </span>
                                            @endif
                                        </h2>
                                        <div class="mt-2">
                                            <span class="fecha-badge">
                                                <i class="far fa-calendar-alt me-1"></i>
                                                Creado: {{ $fechaCreacion }}
                                            </span>
                                            @if($ingreso->status)
                                            <span class="fecha-badge ms-2" style="background: #e7f5ff; color: #0d6efd;">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Estado: {{ ucfirst($ingreso->status) }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                </div>
                                
                                <!-- Body de la tarjeta -->
                                <div class="card-ingreso-body">
                                    <!-- Información básica -->
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <div class="info-label">Contrato</div>
                                            <div class="info-value">
                                                {{ $contrato->contrato_no ?? 'N/A' }}
                                                @if($contrato->obra)
                                                <div class="text-muted" style="font-size: 0.85rem; margin-top: 2px;">
                                                    {{ Str::limit($contrato->obra, 40) }}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="info-item">
                                            <div class="info-label">Cliente</div>
                                            <div class="info-value">{{ $contrato->cliente ?? 'Sin cliente' }}</div>
                                        </div>
                                        
                                        <div class="info-item">
                                            <div class="info-label">Período</div>
                                            <div class="info-value">
                                                @if($ingreso->periodo_del && $ingreso->periodo_al)
                                                {{ date('d/m/Y', strtotime($ingreso->periodo_del)) }} - 
                                                {{ date('d/m/Y', strtotime($ingreso->periodo_al)) }}
                                                @else
                                                No especificado
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Facturación -->
                                    <div class="info-grid mt-3">
                                        <div class="info-item">
                                            <div class="info-label">Factura</div>
                                            <div class="info-value">
                                                @if($ingreso->factura)
                                                {{ $ingreso->factura }}
                                                @else
                                                <span class="text-muted">Sin factura</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="info-item">
                                            <div class="info-label">Fecha Factura</div>
                                            <div class="info-value">
                                                @if($ingreso->fecha_factura)
                                                {{ date('d/m/Y', strtotime($ingreso->fecha_factura)) }}
                                                @else
                                                <span class="text-muted">No especificada</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="info-item">
                                            <div class="info-label">Importe Facturado</div>
                                            <div class="info-value">
                                                ${{ number_format($ingreso->importe_facturado ?? 0, 2) }}
                                            </div>
                                        </div>
                                    </div>



                                    <!-- Botones -->
                                    <div class="d-flex justify-content-end gap-2 mt-3 pt-3 border-top">
                                        <a href="{{ route('ingresos.show', $ingreso->id) }}" 
                                        class="btn btn-primary ">
                                            <i class="fas fa-eye me-1"></i> Ver
                                        </a>
                                        @if($ingreso->verificado == 1)
                                        <button onclick="confirmDelete('{{ $ingreso->id }}', '{{ addslashes($ingreso->no_estimacion ?? 'Ingreso') }}')" 
                                                class="btn btn-action btn-outline-danger">
                                            <i class="fas fa-trash me-1"></i> Eliminar
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Paginación -->
                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <div>
                            <small class="text-muted">
                                Mostrando <strong>{{ $ingresos->firstItem() }} - {{ $ingresos->lastItem() }}</strong> de 
                                <strong>{{ $ingresos->total() }}</strong> registros
                            </small>
                        </div>
                        <nav aria-label="Page navigation">
                            {{ $ingresos->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </nav>
                    </div>
                    
                    @else
                    <!-- Estado vacío -->
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4 class="empty-state-title">No se encontraron ingresos</h4>
                        <p class="empty-state-text">
                            @if(request()->has('search'))
                            No hay resultados que coincidan con tu búsqueda.
                            @else
                            No hay ingresos registrados aún. Comienza agregando el primer ingreso.
                            @endif
                        </p>
                        @if(request()->has('search'))
                        <a href="{{ route('ingresos.index') }}" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-times me-1"></i> Limpiar búsqueda
                        </a>
                        @endif
                        <a href="{{ route('ingresos.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus me-1"></i> Registrar Primer Ingreso
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <!-- Modal de confirmación para eliminar -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Confirmar eliminación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar el ingreso <strong id="ingresoNombre" class="text-primary"></strong>?</p>
                    <p class="text-danger small mb-0">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        Esta acción no se puede deshacer.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </button>
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
            
            // Inicializar tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
</body>
</html>