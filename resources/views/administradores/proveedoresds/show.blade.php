<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Detalles del Proveedor</title>
    <style>
        .detail-card {
            max-width: 800px;
            margin: 0 auto;
        }
        .detail-item {
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .detail-item:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #495057;
            min-width: 150px;
        }
        .detail-value {
            color: #212529;
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .status-active {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .status-inactive {
            background-color: #f8d7da;
            color: #842029;
        }
    </style>
</head>
<body>
    <div class="main-container">
        @include('toast.toasts')
        @include('administradores.sidebar')
        
        <main class="main-content" id="mainContent">
            @include('administradores.navbar')

            <div class="content-area">
                <div class="container-fluid py-4">
                    <div class="card shadow-sm detail-card">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-eye me-2 text-primary"></i>
                                    Detalles del Proveedor
                                </h5>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('aproveedoresds.index') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-arrow-left me-1"></i> Regresar
                                    </a>
                                   
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <!-- Información del Proveedor -->
                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <h6 class="text-muted mb-3">INFORMACIÓN GENERAL</h6>
                                    <div class="detail-item d-flex">
                                        <span class="detail-label">Clave:</span>
                                        <span class="detail-value">{{ $proveedor->clave }}</span>
                                    </div>
                                    <div class="detail-item d-flex">
                                        <span class="detail-label">Nombre:</span>
                                        <span class="detail-value">{{ $proveedor->nombre }}</span>
                                    </div>
                                    <div class="detail-item d-flex">
                                        <span class="detail-label">Dirección:</span>
                                        <span class="detail-value">{{ $proveedor->calle }}</span>
                                    </div>
                                    <div class="detail-item d-flex">
                                        <span class="detail-label">Teléfono:</span>
                                        <span class="detail-value">{{ $proveedor->telefono }}</span>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <h6 class="text-muted mb-3">ESTADO Y ESPECIALIDAD</h6>
                                    <div class="detail-item d-flex">
                                        <span class="detail-label">Estatus:</span>
                                        <span class="detail-value">
                                            @if($proveedor->estatus == 'Activo')
                                                <span class="status-badge status-active">{{ $proveedor->estatus }}</span>
                                            @else
                                                <span class="status-badge status-inactive">{{ $proveedor->estatus }}</span>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="detail-item d-flex">
                                        <span class="detail-label">Clasificación:</span>
                                        <span class="detail-value">{{ $proveedor->clasificacion }}</span>
                                    </div>
                                    <div class="detail-item d-flex">
                                        <span class="detail-label">Especialidad:</span>
                                        <span class="detail-value">{{ $proveedor->especialidad }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Información adicional -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6 class="text-muted mb-3">INFORMACIÓN ADICIONAL</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="detail-item d-flex">
                                                <span class="detail-label">Fecha de creación:</span>
                                                <span class="detail-value">
                                                    {{ $proveedor->created_at ? $proveedor->created_at->format('d/m/Y H:i') : 'No disponible' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="detail-item d-flex">
                                                <span class="detail-label">Última actualización:</span>
                                                <span class="detail-value">
                                                    {{ $proveedor->updated_at ? $proveedor->updated_at->format('d/m/Y H:i') : 'No disponible' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Acciones -->
                            <div class="row mt-4 pt-3 border-top">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <!-- Usando la misma ruta de edición pero con un campo oculto para cambiar solo el estatus -->
                                            @if($proveedor->estatus == 'Activo')
                                                <form action="{{ route('aproveedoresds.update', $proveedor->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="clave" value="{{ $proveedor->clave }}">
                                                    <input type="hidden" name="nombre" value="{{ $proveedor->nombre }}">
                                                    <input type="hidden" name="calle" value="{{ $proveedor->calle }}">
                                                    <input type="hidden" name="telefono" value="{{ $proveedor->telefono }}">
                                                    <input type="hidden" name="clasificacion" value="{{ $proveedor->clasificacion }}">
                                                    <input type="hidden" name="especialidad" value="{{ $proveedor->especialidad }}">
                                                    <input type="hidden" name="estatus" value="Inactivo">
                                                  
                                                </form>
                                            @else
                                                <form action="{{ route('aproveedoresds.update', $proveedor->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="clave" value="{{ $proveedor->clave }}">
                                                    <input type="hidden" name="nombre" value="{{ $proveedor->nombre }}">
                                                    <input type="hidden" name="calle" value="{{ $proveedor->calle }}">
                                                    <input type="hidden" name="telefono" value="{{ $proveedor->telefono }}">
                                                    <input type="hidden" name="clasificacion" value="{{ $proveedor->clasificacion }}">
                                                    <input type="hidden" name="especialidad" value="{{ $proveedor->especialidad }}">
                                                    <input type="hidden" name="estatus" value="Activo">
                                                    <button type="submit" class="btn btn-outline-success btn-sm"
                                                            onclick="return confirm('¿Está seguro de activar este proveedor?')">
                                                        <i class="fas fa-check me-1"></i> Activar
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                        
                                        <div class="d-flex gap-2">
                                          
                                            <form action="{{ route('aproveedoresds.destroy', $proveedor->id) }}" method="POST" class="d-inline" 
                                                  id="deleteForm">
                                                @csrf
                                                @method('DELETE')
                                                
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @include('footer')
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Script para confirmar eliminación
            window.confirmDelete = function() {
                if (confirm('¿Está seguro de eliminar este proveedor? Esta acción no se puede deshacer.')) {
                    document.getElementById('deleteForm').submit();
                }
            };
        });
    </script>
</body>
</html>