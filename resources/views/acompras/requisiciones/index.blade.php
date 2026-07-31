<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Requisiciones</title>
</head>
<body>
    <div class="main-container">
        @include('toast.toasts')
        @include('acompras.sidebar')
        
        <main class="main-content" id="mainContent">
            @include('acompras.navbar')

            <div class="content-area">
                <div class="container-fluid py-4">
                    <!-- Título -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4>
                            <i class="fas fa-clipboard-list text-primary me-2"></i>
                            Requisiciones
                            <span class="badge bg-primary ms-2">{{ $requisiciones->total() }} requisiciones</span>
                        </h4>
                        <a href="{{ url('requisiciones/create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Nueva Requisición
                        </a>
                    </div>

                    <!-- Barra de búsqueda -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <form method="GET" action="{{ url('requisiciones') }}" class="d-flex">
                                <input type="text" name="search" class="form-control" placeholder="Buscar por #, frente, empresa, contrato..." value="{{ $search ?? '' }}">
                                <button class="btn btn-primary ms-2" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if(!empty($search))
                                <a href="{{ url('requisiciones') }}" class="btn btn-secondary ms-2">
                                    <i class="fas fa-times"></i>
                                </a>
                                @endif
                            </form>
                        </div>
                        <div class="col-md-6 text-end">
                            <span class="text-muted">
                                <i class="fas fa-list-check me-1"></i>
                                Mostrando {{ $requisiciones->firstItem() }} a {{ $requisiciones->lastItem() }} de {{ $requisiciones->total() }} requisiciones
                            </span>
                        </div>
                    </div>

                    <!-- Lista de requisiciones en tarjetas -->
                    <div id="listaRequisiciones">
                        @if($requisiciones->count() > 0)
                            @foreach($requisiciones as $requisicion)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0">
                                            <a href="{{ url('requisiciones/show/' . $requisicion->id) }}" class="text-decoration-none">
                                                #{{ $requisicion->consecutivo ?? 'N/A' }}
                                            </a>
                                        </h5>
                                        <div>
                                            <small class="text-muted">
                                                <i class="far fa-calendar-alt me-1"></i>
                                                {{ $requisicion->created_at ? \Carbon\Carbon::parse($requisicion->created_at)->format('d/m/Y') : 'N/A' }}
                                            </small>
                                            @if($requisicion->procesada == 1)
                                                <span class="badge bg-success ms-2">Procesada</span>
                                            @else
                                                <span class="badge bg-warning text-dark ms-2">Pendiente</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <small class="text-muted d-block">Frente</small>
                                            <span>{{ $requisicion->frente ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted d-block">Empresa</small>
                                            <span>{{ $requisicion->empresa ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted d-block">Contrato</small>
                                            <span>
                                                @if($requisicion->contrato)
                                                    {{ $requisicion->contrato->consecutivo }}
                                                @else
                                                    <span class="text-muted">Sin contrato</span>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted d-block">Partida</small>
                                            <span>{{ $requisicion->partida ?? 'N/A' }}</span>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                        <div>
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-box me-1"></i>
                                                {{ $requisicion->detalles->count() ?? 0 }} items
                                            </span>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="{{ url('requisiciones/show/' . $requisicion->id) }}" 
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Ver
                                            </a>
                                            
                                            @if($requisicion->procesada != 1)
                                                <a href="{{ url('requisiciones/solicitud-cotizacion/' . $requisicion->id) }}" 
                                                   class="btn btn-sm btn-success" 
                                                   target="_blank">
                                                    <i class="fas fa-file-pdf"></i> Solicitar Cotización
                                                </a>
                                                
                                                <button class="btn btn-sm btn-danger" 
                                                        onclick="eliminarRequisicion('{{ $requisicion->id }}')">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </button>
                                            @else
                                                <span class="text-muted align-self-center">
                                                    <i class="fas fa-check-circle text-success"></i> Procesada
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            <!-- Paginación -->
                            @if($requisiciones->hasPages())
                            <nav aria-label="Page navigation" class="mt-4">
                                {{ $requisiciones->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </nav>
                            @endif
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list" style="font-size: 4rem; color: #dee2e6;"></i>
                            <h5 class="mt-3 text-muted">
                                @if(!empty($search))
                                    No se encontraron resultados para "{{ $search }}"
                                @else
                                    No hay requisiciones
                                @endif
                            </h5>
                            <p class="text-muted">
                                @if(!empty($search))
                                    <a href="{{ url('requisiciones') }}" class="btn btn-outline-primary mt-3">
                                        <i class="fas fa-times me-1"></i> Limpiar búsqueda
                                    </a>
                                @else
                                    Crea una nueva requisición desde el botón "Nueva Requisición".
                                @endif
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>

    @include('footer')

    <script>
        function eliminarRequisicion(id) {
            if (confirm('¿Eliminar esta requisición?')) {
                $.ajax({
                    url: '{{ url("requisiciones/eliminar-requisicion") }}/' + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    },
                    error: function() {
                        alert('Error al eliminar');
                    }
                });
            }
        }
    </script>
</body>
</html>