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
                                <!-- Vista de LISTA (tabla) en lugar de tarjetas -->
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Clave</th>
                                                <th>Nombre</th>
                                                <th>Teléfono</th>
                                                <th>Dirección</th>
                                                <th>Clasificación</th>
                                                <th>Especialidad</th>
                                                <th>Estatus</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($proveedores as $proveedor)
                                            <tr>
                                                <td><span class="fw-semibold">{{ $proveedor->clave }}</span></td>
                                                <td>{{ $proveedor->nombre }}</td>
                                                <td>{{ $proveedor->telefono }}</td>
                                                <td>{{ Str::limit($proveedor->calle, 30) }}</td>
                                                <td>{{ $proveedor->clasificacion }}</td>
                                                <td>{{ $proveedor->especialidad }}</td>
                                                <td>
                                                    @if($proveedor->estatus == 'Activo')
                                                        <span class="badge bg-success">{{ $proveedor->estatus }}</span>
                                                    @elseif($proveedor->estatus == 'Inactivo')
                                                        <span class="badge bg-danger">{{ $proveedor->estatus }}</span>
                                                    @elseif($proveedor->estatus == 'Suspendido')
                                                        <span class="badge bg-warning text-dark">{{ $proveedor->estatus }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center gap-1">
                                                        <a href="{{ route('aproveedoresds.show', $proveedor->id) }}" 
                                                           class="btn btn-outline-info btn-xs" title="Ver detalles">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Totales compactos (opcional) -->
                                <div class="mt-2 small text-muted">
                                    <i class="fas fa-check-circle text-success me-1"></i> Activos: {{ $proveedores->where('estatus', 'Activo')->count() }} |
                                    <i class="fas fa-exclamation-circle text-warning me-1"></i> Suspendidos: {{ $proveedores->where('estatus', 'Suspendido')->count() }} |
                                    <i class="fas fa-times-circle text-danger me-1"></i> Inactivos: {{ $proveedores->where('estatus', 'Inactivo')->count() }}
                                </div>
                            @else
                                <!-- Estado vacío -->
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
                                   {{ $proveedores->appends(request()->query())->links('pagination::bootstrap-4') }}
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


<!--
solo admin puede borrar 
-->