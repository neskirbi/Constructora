<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Productos y Servicios</title>
</head>
<body>
    <div class="main-container">
        @include('toast.toasts')
        @if(Guard() == 'adestajos')
            @include('adestajos.sidebar')
        @elseif(Guard() == 'acompras')
            @include('acompras.sidebar')
        @else
            <!-- Opcional: sidebar por defecto o nada -->
        @endif
        
        <main class="main-content" id="mainContent">
            @if(Guard() == 'adestajos')
                @include('adestajos.navbar')
            @elseif(Guard() == 'acompras')
                @include('acompras.navbar')
            @else
                <!-- Opcional: sidebar por defecto o nada -->
            @endif

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
                                    <!-- Solo texto, sin acciones -->
                                </div>
                            </div>

                            <!-- Vista de LISTA (tabla) -->
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Clave</th>
                                            <th>Descripción</th>
                                            <th>Unidades</th>
                                            <th class="text-end">Último costo</th>
                                            <th class="text-center">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($productos as $producto)
                                        <tr>
                                            <!-- Clave sin diseño especial -->
                                            <td>{{ $producto->clave }}</td>
                                            <td>{{ $producto->descripcion }}</td>
                                            <td>{{ $producto->unidades }}</td>
                                            <td class="text-end fw-bold text-success">${{ number_format($producto->ult_costo, 2) }}</td>
                                            <td class="text-center">
                                                 <button type="button" 
                                                        class="btn btn-sm btn-outline-danger flex-fill"
                                                        onclick="confirmDelete('{{ $producto->id }}', '{{ $producto->clave }}')">
                                                    <i class="fas fa-trash-alt me-1"></i>Eliminar
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">No hay productos o servicios registrados</h5>
                                                <p class="text-muted mb-4">
                                                    @if($search)
                                                    No hay resultados para tu búsqueda "{{ $search }}"
                                                    @else
                                                    El catálogo de productos y servicios está vacío
                                                    @endif
                                                </p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
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

    @include('footer')
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(request()->has('search'))
            document.querySelector('input[name="search"]').focus();
            @endif
        });
    </script>
</body>
</html>