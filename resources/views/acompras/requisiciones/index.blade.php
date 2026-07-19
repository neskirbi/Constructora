<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Requisiciones</title>
    <style>
        .grupo-contrato {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background: white;
            transition: all 0.3s;
            cursor: pointer;
        }
        .grupo-contrato:hover {
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .grupo-contrato .header-grupo {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .grupo-contrato .header-grupo .badge-items {
            background: #0d6efd;
            color: white;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        .btn-borrar-grupo {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            font-size: 0.9rem;
            padding: 4px 8px;
            border-radius: 4px;
            transition: all 0.2s;
            opacity: 0.5;
        }
        .btn-borrar-grupo:hover {
            opacity: 1;
            background: #dc3545;
            color: white;
        }
        .resumen-items {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 8px;
        }
    </style>
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
                            <span class="badge bg-primary ms-2" id="contadorItems">{{ $requisiciones->count() }} items</span>
                        </h4>
                        <a href="{{ url('requisiciones/create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Nueva Requisición
                        </a>
                    </div>

                    <!-- Lista de requisiciones agrupadas por contrato -->
                    <div id="listaRequisiciones">
                        @if($requisiciones->count() > 0)
                            @php
                                $agrupado = $requisiciones->groupBy('contrato_id');
                            @endphp

                            @foreach($agrupado as $contratoId => $items)
                                @php
                                    $primerItem = $items->first();
                                @endphp
                                <div class="grupo-contrato" onclick="window.location='{{ url('requisiciones/show/' . $contratoId) }}'">
                                    <div class="header-grupo">
                                        <div>
                                            <strong>
                                                @if($contratoId && $primerItem->contrato)
                                                    <i class="fas fa-file-contract me-1"></i>
                                                    {{ $primerItem->contrato->refinterna }}
                                                @else
                                                    <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                                                    Sin contrato
                                                @endif
                                            </strong>
                                            <span class="badge-items ms-2">{{ $items->count() }} items</span>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-success me-3">
                                                ${{ number_format($items->sum('total'), 2) }}
                                            </span>
                                            <button class="btn-borrar-grupo" onclick="event.stopPropagation(); borrarGrupo('{{ $contratoId }}')" title="Eliminar requisición">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="resumen-items">
                                        @foreach($items->take(3) as $item)
                                            <span class="badge bg-light text-dark me-1">{{ $item->clave }}</span>
                                        @endforeach
                                        @if($items->count() > 3)
                                            <span class="badge bg-secondary">+{{ $items->count() - 3 }} más</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
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
        function borrarGrupo(contratoId) {
            if (confirm('¿Eliminar todas las requisiciones de este contrato?')) {
                $.ajax({
                    url: '{{ url("requisiciones/borrar-grupo") }}/' + contratoId,
                    type: 'POST',
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