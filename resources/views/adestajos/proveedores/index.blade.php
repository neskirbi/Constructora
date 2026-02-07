<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Proveedores</title>
    <style>
        .proveedor-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 15px;
            border-left: 4px solid #0d6efd;
            transition: transform 0.2s;
            height: 100%;
        }
        .proveedor-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }
        .proveedor-header {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
            background: #f8fafc;
            border-radius: 10px 10px 0 0;
        }
        .proveedor-body {
            padding: 15px;
        }
        .proveedor-footer {
            padding: 10px 15px;
            background: #f8fafc;
            border-top: 1px solid #f1f5f9;
            border-radius: 0 0 10px 10px;
            text-align: right;
        }
        .proveedor-icono {
            width: 50px;
            height: 50px;
            background: #0d6efd;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-right: 15px;
            float: left;
        }
        .proveedor-info {
            overflow: hidden;
        }
        .proveedor-clave {
            background: #6c757d;
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 5px;
        }
        .proveedor-nombre {
            color: #212529;
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 5px;
            line-height: 1.3;
        }
        .proveedor-detalle {
            color: #6c757d;
            font-size: 0.85rem;
            margin-bottom: 3px;
        }
        .proveedor-detalle i {
            width: 16px;
            margin-right: 5px;
        }
        .proveedor-etiquetas {
            margin-top: 10px;
        }
        .proveedor-clasificacion {
            background: #e9ecef;
            color: #495057;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            display: inline-block;
            margin-right: 5px;
            margin-bottom: 5px;
        }
        .proveedor-especialidad {
            background: #d1ecf1;
            color: #0c5460;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            display: inline-block;
            margin-bottom: 5px;
        }
        .badge-estatus {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            float: right;
            margin-top: 5px;
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
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #dee2e6;
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
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-truck me-2 text-primary"></i>
                                    Proveedores
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

                            @if($proveedores->count() > 0)
                                <div class="row">
                                    @foreach($proveedores as $proveedor)
                                    <div class="col-md-6">
                                        <div class="proveedor-card">
                                            <div class="proveedor-header">
                                                <div class="proveedor-icono">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div class="proveedor-info">
                                                    <div class="d-flex justify-content-between">
                                                        <span class="proveedor-clave">{{ $proveedor->clave }}</span>
                                                        @php
                                                            $estatusClass = '';
                                                            if($proveedor->estatus == 'Activo') $estatusClass = 'estatus-activo';
                                                            elseif($proveedor->estatus == 'Inactivo') $estatusClass = 'estatus-inactivo';
                                                            elseif($proveedor->estatus == 'Suspendido') $estatusClass = 'estatus-suspendido';
                                                        @endphp
                                                        <span class="badge-estatus {{ $estatusClass }}">
                                                            {{ $proveedor->estatus }}
                                                        </span>
                                                    </div>
                                                    <div class="proveedor-nombre">
                                                        {{ $proveedor->nombre }}
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="proveedor-body">
                                                <div class="proveedor-detalle">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    {{ Str::limit($proveedor->calle, 50) }}
                                                </div>
                                                
                                                <div class="proveedor-detalle">
                                                    <i class="fas fa-phone"></i>
                                                    {{ $proveedor->telefono }}
                                                </div>
                                                
                                                <div class="proveedor-etiquetas">
                                                    <span class="proveedor-clasificacion">
                                                        {{ $proveedor->clasificacion }}
                                                    </span>
                                                    <span class="proveedor-especialidad">
                                                        {{ $proveedor->especialidad }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="proveedor-footer">
                                               <a href="{{ route('proveedoresds.show', $proveedor->id) }}" 
                                                       class="btn btn-outline-info ">
                                                        <i class="fas fa-eye"></i> Revisar
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-danger "
                                                            onclick="confirmDelete('{{ $proveedor->id }}', '{{ $proveedor->nombre }}')">
                                                        <i class="fas fa-trash"></i> Eliminar
                                                    </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <h5 class="mb-3">No se encontraron proveedores</h5>
                                    @if($search)
                                    <a href="{{ route('proveedoresds.index') }}" class="btn btn-outline-primary btn-sm">
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
            form.action = '{{ route("proveedoresds.destroy", "") }}/' + id;
            
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