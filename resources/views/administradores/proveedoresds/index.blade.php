<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Proveedores</title>
</head>
<body>
    <div class="main-container">
        @include('toast.toasts')
        @include('administradores.sidebar')
        
        <main class="main-content" id="mainContent">
            @include('administradores.navbar')

            <div class="content-area">
                <div class="container-fluid py-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-truck me-2 text-primary"></i>
                                    Proveedores
                                </h5>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <form method="GET" action="{{ route('aproveedoresds.index') }}">
                                        <div class="input-group">
                                            <input type="text" 
                                                   name="search" 
                                                   class="form-control form-control-sm" 
                                                   placeholder="Buscar por nombre, clave, teléfono..." 
                                                   value="{{ $search ?? '' }}">
                                            <button class="btn btn-outline-primary btn-sm" type="submit">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            @if($search)
                                            <a href="{{ route('aproveedoresds.index') }}" class="btn btn-outline-secondary btn-sm">
                                                <i class="fas fa-times"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-6 text-end">
                                    <span class="text-muted small">
                                        Total: {{ $proveedores->total() }} proveedor(es)
                                    </span>
                                </div>
                            </div>

                            @if($proveedores->count() > 0)
                                <div class="row">
                                    @foreach($proveedores as $proveedor)
                                    <div class="col-md-6">
                                        <!-- Tarjeta usando solo Bootstrap -->
                                        <div class="card shadow-sm mb-3 h-100">
                                            <!-- Header -->
                                            <div class="card-header bg-light d-flex align-items-center p-3">
                                                <!-- Icono con Bootstrap -->
                                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                                    <i class="fas fa-user text-white fs-4"></i>
                                                </div>
                                                
                                                <!-- Información -->
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <!-- Clave -->
                                                        <span class="badge bg-secondary">{{ $proveedor->clave }}</span>
                                                        
                                                        <!-- Estatus con badges de Bootstrap -->
                                                        @if($proveedor->estatus == 'Activo')
                                                            <span class="badge bg-success">{{ $proveedor->estatus }}</span>
                                                        @elseif($proveedor->estatus == 'Inactivo')
                                                            <span class="badge bg-danger">{{ $proveedor->estatus }}</span>
                                                        @else
                                                            <span class="badge bg-warning text-dark">{{ $proveedor->estatus }}</span>
                                                        @endif
                                                    </div>
                                                    <!-- Nombre -->
                                                    <h6 class="card-title mb-0 mt-1 fw-bold">{{ $proveedor->nombre }}</h6>
                                                </div>
                                            </div>
                                            
                                            <!-- Body -->
                                            <div class="card-body p-3">
                                                <!-- Dirección -->
                                                <p class="card-text text-muted mb-2">
                                                    <i class="fas fa-map-marker-alt me-2"></i>
                                                    {{ Str::limit($proveedor->calle, 50) }}
                                                </p>
                                                
                                                <!-- Teléfono -->
                                                <p class="card-text text-muted mb-3">
                                                    <i class="fas fa-phone me-2"></i>
                                                    {{ $proveedor->telefono }}
                                                </p>
                                                
                                                <!-- Etiquetas -->
                                                <div class="d-flex flex-wrap gap-2">
                                                    <span class="badge bg-light text-dark">
                                                        {{ $proveedor->clasificacion }}
                                                    </span>
                                                    <span class="badge bg-info text-white">
                                                        {{ $proveedor->especialidad }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <!-- Footer -->
                                            <div class="card-footer bg-light py-2">
                                                <div class="text-end">
                                                    <a href="{{ route('aproveedoresds.show', $proveedor->id) }}" 
                                                       class="btn btn-outline-info btn-sm">
                                                        <i class="fas fa-eye me-1"></i> Revisar
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <!-- Estado vacío con Bootstrap -->
                                <div class="text-center py-5">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted mb-3">No se encontraron proveedores</h5>
                                    @if($search)
                                    <a href="{{ route('aproveedoresds.index') }}" class="btn btn-outline-primary btn-sm">
                                        Mostrar todos los proveedores
                                    </a>
                                    @else
                                    <p class="text-muted mb-0">Comienza creando tu primer proveedor</p>
                                    @endif
                                </div>
                            @endif

                            @if($proveedores->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div class="text-muted small">
                                    Mostrando {{ $proveedores->firstItem() }} a {{ $proveedores->lastItem() }} de {{ $proveedores->total() }} registros
                                </div>
                                <div>
                                    {{ $proveedores->links() }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @include('footer')
    
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar el proveedor <strong id="proveedorNombre"></strong>?</p>
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
        function confirmDelete(id, nombre) {
            document.getElementById('proveedorNombre').textContent = nombre;
            
            const form = document.getElementById('deleteForm');
            form.action = '{{ route("aproveedoresds.destroy", "") }}/' + id;
            
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