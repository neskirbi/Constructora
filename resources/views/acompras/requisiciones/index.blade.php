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
                            <span class="badge bg-primary ms-2">{{ $requisiciones->count() }} requisiciones</span>
                        </h4>
                        <a href="{{ url('requisiciones/create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Nueva Requisición
                        </a>
                    </div>

                    <!-- Lista de requisiciones -->
                    <div id="listaRequisiciones">
                        @if($requisiciones->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th># Requisición</th>
                                            <th>Frente</th>
                                            <th>Empresa</th>
                                            <th>Contrato</th>
                                            <th>Items</th>
                                            <th>Total</th>
                                            <th>Fecha</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($requisiciones as $requisicion)
                                        <tr>
                                            <td>
                                                <a href="{{ url('requisiciones/show/' . $requisicion->id) }}">
                                                    {{ $requisicion->consecutivo ?? 'N/A' }}
                                                </a>
                                            </td>
                                            <td>{{ $requisicion->frente ?? 'N/A' }}</td>
                                            <td>{{ $requisicion->empresa ?? 'N/A' }}</td>
                                            <td>
                                                @if($requisicion->contrato)
                                                    {{ $requisicion->contrato->refinterna }}
                                                @else
                                                    <span class="text-muted">Sin contrato</span>
                                                @endif
                                            </td>
                                            <td>{{ $requisicion->detalles->count() ?? 0 }}</td>
                                            <td>${{ number_format($requisicion->detalles->sum('total') ?? 0, 2) }}</td>
                                            <td>{{ $requisicion->created_at ? \Carbon\Carbon::parse($requisicion->created_at)->format('d/m/Y') : 'N/A' }}</td>
                                            <td>
                                                <a href="{{ url('requisiciones/show/' . $requisicion->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button class="btn btn-sm btn-danger" onclick="eliminarRequisicion('{{ $requisicion->id }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list" style="font-size: 4rem; color: #dee2e6;"></i>
                            <h5 class="mt-3 text-muted">No hay requisiciones</h5>
                            <p class="text-muted">Crea una nueva requisición desde el botón "Nueva Requisición".</p>
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