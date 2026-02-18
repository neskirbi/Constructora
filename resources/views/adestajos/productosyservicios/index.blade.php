<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Productos y Servicios</title>
</head>
<body>
    <div class="main-container">
        @include('toast.toasts')
        @include('adestajos.sidebar')
        
        <main class="main-content" id="mainContent">
            @include('adestajos.navbar')

            <div class="content-area">
                <div class="container-fluid py-4">
                    <!-- Card principal -->
                    <div class="card shadow-sm mb-4">
                        <!-- Header -->
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
                        
                        <!-- Body -->
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
                                    <span class="text-muted">
                                        <i class="fas fa-cubes me-1"></i>
                                        Total: {{ $productos->total() }} registro(s)
                                    </span>
                                </div>
                            </div>

                            <!-- Grid de productos -->
                            <div class="row">
                                @forelse($productos as $producto)
                                <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                                    <div class="card h-100 shadow-sm">
                                        <!-- Header del card -->
                                        <div class="card-header bg-white py-3 border-bottom">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                                        <i class="fas fa-tag me-1"></i>
                                                        {{ $producto->clave }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Body del card -->
                                        <div class="card-body">
                                            <p class="fw-bold mb-3">{{ $producto->descripcion }}</p>
                                            
                                            <div class="row g-2 mb-3">
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Unidades</small>
                                                    <span class="fw-semibold">{{ $producto->unidades }}</span>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Último costo</small>
                                                    <span class="fw-bold text-success">${{ number_format($producto->ult_costo, 2) }}</span>
                                                </div>
                                            </div>
                                            
                                            <!-- Fechas -->
                                            <div class="d-flex justify-content-between border-top pt-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    {{ $producto->created_at->format('d/m/Y') }}
                                                </small>
                                                @if($producto->created_at != $producto->updated_at)
                                                <small class="text-muted">
                                                    <i class="fas fa-edit me-1"></i>
                                                    {{ $producto->updated_at->format('d/m/Y') }}
                                                </small>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Footer con botones de acción rápida -->
                                        <div class="card-footer bg-white border-top-0 pt-0">
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('productosyservicios.show', $producto->id) }}" 
                                                   class="btn btn-sm btn-outline-info flex-fill">
                                                    <i class="fas fa-eye me-1"></i>Ver
                                                </a>
                                                
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger flex-fill"
                                                        onclick="confirmDelete('{{ $producto->id }}', '{{ $producto->clave }}')">
                                                    <i class="fas fa-trash-alt me-1"></i>Eliminar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12">
                                    <div class="text-center py-5">
                                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No hay productos o servicios registrados</h5>
                                        <p class="text-muted mb-4">
                                            @if($search)
                                            No hay resultados para tu búsqueda "{{ $search }}"
                                            @else
                                            Comienza creando tu primer producto o servicio
                                            @endif
                                        </p>
                                        @if($search)
                                        <a href="{{ route('productosyservicios.index') }}" class="btn btn-outline-primary">
                                            <i class="fas fa-list me-1"></i> Ver todos
                                        </a>
                                        @else
                                        <a href="{{ route('productosyservicios.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-1"></i> Crear nuevo
                                        </a>
                                        @endif
                                    </div>
                                </div>
                                @endforelse
                            </div>

                            <!-- Paginación -->
                            @if($productos->hasPages())
                            <div class="card-footer bg-white border-top mt-4">
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

    <!-- Modal de confirmación para eliminar (bonito) -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-body text-center p-4">
                    <!-- Ícono de advertencia animado -->
                    <div class="mb-4">
                        <div class="rounded-circle bg-danger bg-opacity-10 d-inline-flex p-3">
                            <i class="fas fa-exclamation-triangle fa-4x text-danger"></i>
                        </div>
                    </div>
                    
                    <!-- Título -->
                    <h4 class="fw-bold mb-2">¿Confirmar eliminación?</h4>
                    
                    <!-- Mensaje dinámico -->
                    <p class="text-muted mb-4" id="deleteMessage">
                        ¿Estás seguro de que deseas eliminar el producto/servicio <strong id="productoNombre"></strong>?
                    </p>
                    
                    <!-- Advertencia -->
                    <div class="alert alert-warning bg-warning bg-opacity-10 border-0 mb-4 py-2">
                        <i class="fas fa-info-circle me-1"></i>
                        <small>Esta acción no se puede deshacer.</small>
                    </div>
                    
                    <!-- Botones -->
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
            // Establecer el nombre en el mensaje
            document.getElementById('productoNombre').textContent = nombre;
            
            // Actualizar la acción del formulario
            const form = document.getElementById('deleteForm');
            form.action = '{{ route("productosyservicios.destroy", "") }}/' + id;
            
            // Mostrar el modal
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