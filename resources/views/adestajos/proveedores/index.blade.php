<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Proveedores</title>
    <style>
        .badge-estatus {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
        }
        .estatus-activo {
            background-color: #d4edda;
            color: #155724;
        }
        .estatus-inactivo {
            background-color: #f8d7da;
            color: #721c24;
        }
        .estatus-suspendido {
            background-color: #fff3cd;
            color: #856404;
        }
        .table-actions {
            white-space: nowrap;
            width: 120px;
        }
    </style>
</head>
<body>
    <div class="main-container">
        @include('toast.toasts')
        @include('adestajos.sidebar')
        
        <main class="main-content" id="mainContent">
            @include('adestajos.navbar')

            <div class="content-area">
                <div class="container-fluid py-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-truck me-2 text-primary"></i>
                                    Lista de Proveedores
                                </h5>
                                <a href="{{ route('proveedoresds.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus me-1"></i> Nuevo Proveedor
                                </a>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <form method="GET" action="{{ route('proveedoresds.index') }}">
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
                                            <a href="{{ route('proveedoresds.index') }}" class="btn btn-outline-secondary btn-sm">
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

                            <div class="table-responsive">
                                <table class="table table-hover table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Clave</th>
                                            <th>Nombre</th>
                                            <th>Teléfono</th>
                                            <th>Clasificación</th>
                                            <th>Especialidad</th>
                                            <th>Estatus</th>
                                            <th class="text-end">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($proveedores as $proveedor)
                                        <tr>
                                            <td>
                                                <span class="badge bg-secondary">{{ $proveedor->clave }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ $proveedor->nombre }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $proveedor->calle }}</small>
                                            </td>
                                            <td>{{ $proveedor->telefono }}</td>
                                            <td>{{ $proveedor->clasificacion }}</td>
                                            <td>{{ $proveedor->especialidad }}</td>
                                            <td>
                                                @php
                                                    $estatusClass = '';
                                                    if($proveedor->estatus == 'Activo') $estatusClass = 'estatus-activo';
                                                    elseif($proveedor->estatus == 'Inactivo') $estatusClass = 'estatus-inactivo';
                                                    elseif($proveedor->estatus == 'Suspendido') $estatusClass = 'estatus-suspendido';
                                                @endphp
                                                <span class="badge-estatus {{ $estatusClass }}">
                                                    {{ $proveedor->estatus }}
                                                </span>
                                            </td>
                                            <td class="table-actions text-end">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('proveedoresds.show', $proveedor->id) }}" 
                                                       class="btn btn-outline-info" 
                                                       data-bs-toggle="tooltip" 
                                                       title="Ver detalle">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-outline-danger" 
                                                            onclick="confirmDelete('{{ $proveedor->id }}', '{{ $proveedor->nombre }}')"
                                                            data-bs-toggle="tooltip" 
                                                            title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-inbox fa-2x mb-3"></i>
                                                    <p>No se encontraron proveedores</p>
                                                    @if($search)
                                                    <a href="{{ route('proveedoresds.index') }}" class="btn btn-sm btn-outline-primary">
                                                        Mostrar todos
                                                    </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if($proveedores->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
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
            form.action = '{{ route("proveedoresds.destroy", "") }}/' + id;
            
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            @if(request()->has('search'))
            document.querySelector('input[name="search"]').focus();
            @endif
            
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
</body>
</html>