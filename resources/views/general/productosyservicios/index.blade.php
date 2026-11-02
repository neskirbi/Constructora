<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Productos y Servicios</title>
    <style>
        .producto-card {
            border: 1px solid #e3e6ea;
            border-radius: 10px;
            transition: box-shadow .15s ease;
        }
        .producto-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,.06);
        }
        .producto-card .label {
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #8a94a6;
            font-weight: 600;
            margin-bottom: 2px;
        }
        .producto-card .value {
            font-size: .9rem;
            color: #2c3340;
            word-break: break-word;
        }
        .producto-card .precio {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0d6efd;
        }
        .producto-card .costo {
            font-size: 1rem;
            font-weight: 600;
            color: #198754;
        }
        .desc-clamp {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            font-size: .85rem;
            color: #6c757d;
        }
        .card-actions .btn { border-radius: 6px; }
    </style>
</head>
<body>
    <div class="main-container">
        @include('toast.toasts')
        @if(Guard() == 'adestajos')
            @include('adestajos.sidebar')
        @elseif(Guard() == 'acompras')
            @include('acompras.sidebar')
        @else
        @endif
        
        <main class="main-content" id="mainContent">
            @if(Guard() == 'adestajos')
                @include('adestajos.navbar')
            @elseif(Guard() == 'acompras')
                @include('acompras.navbar')
            @else
            @endif

            <div class="content-area">
                <div class="container-fluid py-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-boxes me-2 text-primary"></i>
                                    Productos y Servicios
                                </h5>
                                <a href="{{ route('productosyservicios.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Nuevo Producto/Servicio
                                </a>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <!-- Barra de búsqueda -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <form method="GET" action="{{ route('productosyservicios.index') }}" class="input-group">
                                        <input type="text" 
                                               name="search" 
                                               class="form-control" 
                                               placeholder="Buscar por clave o descripción..." 
                                               value="{{ $search ?? '' }}">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        @if($search)
                                        <a href="{{ route('productosyservicios.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-times"></i>
                                        </a>
                                        @endif
                                    </form>
                                </div>
                                <div class="col-md-6 text-end">
                                    <span class="text-muted small">
                                        Total: {{ $productos->total() }} registro(s)
                                    </span>
                                </div>
                            </div>

                            <!-- Una tarjeta por fila -->
                            <div class="row g-3">
                                @forelse($productos as $producto)
                                <div class="col-12">
                                    <div class="card producto-card">
                                        <div class="card-body">
                                            <div class="row g-3 align-items-start">

                                                <!-- Columna 1: Clave, unidades, estatus, descripción -->
                                                <div class="col-md-3">
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <span class="fw-bold text-primary">{{ $producto->clave }}</span>
                                                        <span class="badge bg-secondary">{{ $producto->unidades }}</span>
                                                        @if($producto->estatus_ejecucion == 'ejecutado')
                                                            <span class="badge bg-success">Ejecutado</span>
                                                        @else
                                                            <span class="badge bg-warning text-dark">Por ejecutar</span>
                                                        @endif
                                                    </div>
                                                    <div class="desc-clamp" title="{{ $producto->descripcion }}">
                                                        {{ $producto->descripcion }}
                                                    </div>
                                                </div>

                                                <!-- Columna 2: Proveedor y Contrato -->
                                                <div class="col-md-3">
                                                    <div class="mb-2">
                                                        <div class="label">Proveedor</div>
                                                        <div class="value">
                                                            @if($producto->id_proveedor && isset($proveedores[$producto->id_proveedor]))
                                                                {{ $proveedores[$producto->id_proveedor]->clave }} - {{ $proveedores[$producto->id_proveedor]->nombre }}
                                                            @else
                                                                <span class="text-muted">—</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="label">Contrato</div>
                                                        <div class="value">
                                                            @if($producto->id_contrato && isset($contratos[$producto->id_contrato]))
                                                                {{ $contratos[$producto->id_contrato]->consecutivo }} - {{ $contratos[$producto->id_contrato]->refinterna ?? '' }}
                                                            @else
                                                                <span class="text-muted">—</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Columna 3: Precios -->
                                                <div class="col-md-2">
                                                    <div class="mb-2">
                                                        <div class="label">Precio</div>
                                                        <div class="precio">${{ number_format($producto->precio ?? 0, 2) }}</div>
                                                    </div>
                                                    <div>
                                                        <div class="label">Último costo</div>
                                                        <div class="costo">${{ number_format($producto->ult_costo ?? 0, 2) }}</div>
                                                    </div>
                                                </div>

                                                <!-- Columna 4: Documentos -->
                                                <div class="col-md-2">
                                                    <div class="label mb-1">Documentos</div>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        @if($producto->archivo_pdf_1)
                                                            <a href="{{ asset($producto->archivo_pdf_1) }}" 
                                                               target="_blank"
                                                               class="btn btn-sm btn-outline-danger">
                                                                <i class="fas fa-file-pdf me-1"></i> Cédula
                                                            </a>
                                                        @endif
                                                        @if($producto->archivo_pdf_2)
                                                            <a href="{{ asset($producto->archivo_pdf_2) }}" 
                                                               target="_blank"
                                                               class="btn btn-sm btn-outline-danger">
                                                                <i class="fas fa-file-pdf me-1"></i> APU
                                                            </a>
                                                        @endif
                                                        @if(!$producto->archivo_pdf_1 && !$producto->archivo_pdf_2)
                                                            <span class="text-muted small">Sin documentos</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Columna 5: Acciones -->
                                                <div class="col-md-2">
                                                    <div class="label mb-1">Acciones</div>
                                                    <div class="d-flex flex-column gap-1">
                                                        <a href="{{ url('productosyservicios') }}/{{ $producto->id }}"
                                                           class="btn btn-sm btn-outline-info">
                                                            <i class="fas fa-eye me-1"></i> Revisar
                                                        </a>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger"
                                                                onclick="confirmDelete('{{ $producto->id }}', '{{ $producto->clave }}')">
                                                            <i class="fas fa-trash-alt me-1"></i> Eliminar
                                                        </button>
                                                    </div>
                                                    <small class="text-muted d-block mt-1">
                                                        @if($producto->updated_at)
                                                            <i class="far fa-clock me-1"></i>
                                                            {{ $producto->updated_at->diffForHumans() }}
                                                        @endif
                                                    </small>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12 text-center py-5">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No hay productos o servicios registrados</h5>
                                    <p class="text-muted mb-4">
                                        @if($search)
                                        No hay resultados para tu búsqueda "{{ $search }}"
                                        @else
                                        El catálogo de productos y servicios está vacío
                                        @endif
                                    </p>
                                </div>
                                @endforelse
                            </div>

                            <!-- Paginación -->
                            @if($productos->hasPages())
                            <div class="mt-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted small">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Mostrando {{ $productos->firstItem() }} a {{ $productos->lastItem() }} de {{ $productos->total() }} registros
                                    </div>
                                    <div>
                                        {{ $productos->appends(request()->query())->links('pagination::bootstrap-4') }}
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal de confirmación para eliminar -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-body text-center p-4">
                    <div class="mb-4">
                        <div class="rounded-circle bg-danger bg-opacity-10 d-inline-flex p-3">
                            <i class="fas fa-exclamation-triangle fa-4x text-danger"></i>
                        </div>
                    </div>
                    
                    <h4 class="fw-bold mb-2">¿Confirmar eliminación?</h4>
                    
                    <p class="text-muted mb-4" id="deleteMessage">
                        ¿Estás seguro de que deseas eliminar el producto/servicio <strong id="productoNombre"></strong>?
                    </p>
                    
                    <div class="alert alert-warning bg-warning bg-opacity-10 border-0 mb-4 py-2">
                        <i class="fas fa-info-circle me-1"></i>
                        <small>Esta acción no se puede deshacer.</small>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light flex-fill py-2" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </button>
                        
                        <form id="deleteForm" method="POST" class="flex-fill">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100 py-2">
                                <i class="fas fa-trash-alt me-1"></i> Sí, eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('footer')
    
   <script>
        function confirmDelete(id, nombre) {
            document.getElementById('productoNombre').textContent = nombre;
            
            const form = document.getElementById('deleteForm');
            form.action = '{{ route("productosyservicios.destroy", "") }}/' + id;
            
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            @if(request()->has('search'))
            document.querySelector('input[name="search"]').focus();
            @endif
        });
    </script>
</body>
</html>