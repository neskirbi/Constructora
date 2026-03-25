<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Contratos</title>
</head>
<body>
    <div class="main-container">
        @include('toast.toasts')
        @include('administradores.sidebar')
        
        <!-- Contenido principal -->
        <main class="main-content" id="mainContent">
            @include('administradores.navbar')

            <!-- Área de contenido -->
            <div class="content-area">
                <div class="container-fluid py-4">
                    <!-- Título y botón -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h1 class="h3 mb-1 text-gray-800"><i class="fas fa-file-contract me-2"></i> Contratos</h1>
                            <p class="text-muted mb-0">Gestión de contratos de obra</p>
                        </div>
                        
                    </div>
                    
                    <!-- Filtros -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <form action="{{ route('acontratos.index') }}" method="GET" class="row g-3">
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" 
                                            class="form-control border-start-0" 
                                            name="search" 
                                            placeholder="Buscar por obra, contrato, cliente, ubicación..." 
                                            value="{{ request('search') }}">
                                        @if(request()->has('search') && !empty(request('search')))
                                        <a href="{{ route('acontratos.index', array_merge(request()->except('search'), ['search' => ''])) }}" 
                                        class="input-group-text bg-light text-danger" 
                                        title="Limpiar búsqueda">
                                            <i class="fas fa-times"></i>
                                        </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search me-1"></i> Buscar
                                        </button>
                                    </div>
                                </div>
                            </form>
                            
                            @if(request()->has('search'))
                            <div class="mt-3">
                                <small class="text-muted">
                                    Resultados para: <strong>"{{ request('search') }}"</strong>
                                </small>
                                <a href="{{ route('acontratos.index') }}" class="btn btn-sm btn-outline-danger ms-3">
                                    <i class="fas fa-times me-1"></i> Limpiar búsqueda
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Lista de Contratos en Tarjetas -->
                    @if($contratos->count() > 0)
                        @foreach($contratos as $contrato)
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <!-- Primera fila: Consecutivo, Ref. Interna, No. Contrato -->
                                <div class="row mb-3">
                                    <div class="col-md-3 mb-2 mb-md-0">
                                        <small class="text-muted d-block">Consecutivo</small>
                                        <span class="fw-bold">{{ $contrato->consecutivo ?? 'N/A' }}</span>
                                    </div>
                                    <div class="col-md-3 mb-2 mb-md-0">
                                        <small class="text-muted d-block">Ref. Interna</small>
                                        <span>{{ $contrato->refinterna ?? 'N/A' }}</span>
                                    </div>
                                    <div class="col-md-3 mb-2 mb-md-0">
                                        <small class="text-muted d-block">No. Contrato</small>
                                        <span>{{ $contrato->contrato_no ?? 'N/A' }}</span>
                                    </div>
                                    <div class="col-md-3 mb-2 mb-md-0">
                                        <small class="text-muted d-block">Empresa</small>
                                        <span>{{ $contrato->empresa ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                
                                <!-- Segunda fila: Cliente -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <small class="text-muted d-block">Cliente</small>
                                        <span class="fw-bold" title="{{ $contrato->cliente ?? 'No especificado' }}">
                                            {{ $contrato->cliente ?? 'No especificado' }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Tercera fila: Obra -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <small class="text-muted d-block">Obra</small>
                                        <span class="fw-bold" title="{{ $contrato->obra ?? 'Sin nombre de obra' }}">
                                            {{ $contrato->obra ?? 'Sin nombre de obra' }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Cuarta fila: Frente -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">Frente</small>
                                        <span>{{ $contrato->frente ?? 'No especificado' }}</span>
                                    </div>
                                </div>
                                
                                <!-- Fechas con duración total -->
                                <div class="row mb-4">
                                    <div class="col-md-3 mb-2 mb-md-0">
                                        <small class="text-muted d-block">Fecha Contrato</small>
                                        <span>
                                            @if($contrato->fecha_contrato)
                                                {{ date('d/m/Y', strtotime($contrato->fecha_contrato)) }}
                                            @else
                                                No definida
                                            @endif
                                        </span>
                                    </div>
                                    <div class="col-md-3 mb-2 mb-md-0">
                                        <small class="text-muted d-block">Inicio Obra</small>
                                        <span>
                                            @if($contrato->fecha_inicio_obra)
                                                {{ date('d/m/Y', strtotime($contrato->fecha_inicio_obra)) }}
                                            @else
                                                No definida
                                            @endif
                                        </span>
                                    </div>
                                    <div class="col-md-3 mb-2 mb-md-0">
                                        <small class="text-muted d-block">Fin Obra</small>
                                        <span>
                                            @if($contrato->fecha_ampliada ?? $contrato->fecha_terminacion_obra)
                                                {{ date('d/m/Y', strtotime($contrato->fecha_ampliada ?? $contrato->fecha_terminacion_obra)) }}
                                            @else
                                                No definida
                                            @endif
                                        </span>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">Duración Total</small>
                                        <span class="fw-bold">
                                            @php
                                                $fechaInicio = $contrato->fecha_inicio_obra;
                                                $fechaFin = $contrato->fecha_ampliada ?? $contrato->fecha_terminacion_obra;
                                                
                                                if ($fechaInicio && $fechaFin) {
                                                    $diasDuracion = \Carbon\Carbon::parse($fechaInicio)->diffInDays(\Carbon\Carbon::parse($fechaFin));
                                                    echo $diasDuracion . ' días';
                                                } else {
                                                    echo $contrato->duracion ?? 'No especificada';
                                                }
                                            @endphp
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Montos sin iconos -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-3">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body text-center">
                                                <h6 class="text-muted mb-2">Subtotal</h6>
                                                <h5 class="fw-bold mb-0">${{ number_format($contrato->subtotal ?? 0, 2) }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body text-center">
                                                <h6 class="text-muted mb-2">IVA</h6>
                                                <h5 class="fw-bold mb-0">${{ number_format($contrato->iva ?? 0, 2) }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body text-center">
                                                <h6 class="text-muted mb-2">Total</h6>
                                                <h5 class="fw-bold mb-0">${{ number_format($contrato->total ?? 0, 2) }}</h5>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body text-center">
                                                <h6 class="text-muted mb-2">Total con Convenio</h6>
                                                <h5 class="fw-bold mb-0">
                                                    ${{ number_format(($contrato->total ?? 0) + ($contrato->totalTotalAmpliaciones ?? 0), 2) }}
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- DESGLOSE DE CONVENIOS (AMPLIACIONES) -->
                                @if($contrato->ampliacionesTiempo->count() > 0 || $contrato->ampliacionesMonto->count() > 0)
                                <div class="mt-4 pt-3 border-top">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-file-contract me-2"></i>Convenios / Ampliaciones
                                    </h6>
                                    
                                    <div class="row">
                                        <!-- Ampliaciones de Tiempo -->
                                        @if($contrato->ampliacionesTiempo->count() > 0)
                                        <div class="col-md-6 mb-3">
                                            <div class="card border-0 bg-light">
                                                <div class="card-header bg-transparent border-0 pt-3">
                                                    <h6 class="mb-0"><i class="fas fa-clock me-2 text-warning"></i>Ampliaciones de Tiempo</h6>
                                                </div>
                                                <div class="card-body pt-0">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm">
                                                            <thead>
                                                                <tr>
                                                                   
                                                                    <th>Nueva Fecha</th>
                                                                    <th>Días</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($contrato->ampliacionesTiempo as $amp)
                                                                @php
                                                                    $fechaAnterior = $loop->first ? $contrato->fecha_terminacion_obra : $contrato->ampliacionesTiempo[$loop->index - 1]->fecha_terminacion_obra;
                                                                    $dias = \Carbon\Carbon::parse($fechaAnterior)->diffInDays(\Carbon\Carbon::parse($amp->fecha_terminacion_obra));
                                                                @endphp
                                                                <tr>
                                                                    
                                                                    <td>{{ \Carbon\Carbon::parse($amp->fecha_terminacion_obra)->format('d/m/Y') }}</td>
                                                                    <td><span class="badge bg-info">{{ $dias }} días</span></td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <!-- Ampliaciones de Monto -->
                                        @if($contrato->ampliacionesMonto->count() > 0)
                                        <div class="col-md-6 mb-3">
                                            <div class="card border-0 bg-light">
                                                <div class="card-header bg-transparent border-0 pt-3">
                                                    <h6 class="mb-0"><i class="fas fa-dollar-sign me-2 text-success"></i>Ampliaciones de Monto</h6>
                                                </div>
                                                <div class="card-body pt-0">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm">
                                                            <thead>
                                                                <tr>
                                                                    <th>Fecha</th>
                                                                    <th class="text-end">Subtotal</th>
                                                                    <th class="text-center">IVA %</th>
                                                                    <th class="text-end">IVA Monto</th>
                                                                    <th class="text-end">Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($contrato->ampliacionesMonto as $amp)
                                                                @php
                                                                    // Calcular el monto del IVA basado en el porcentaje
                                                                    $montoIVA = $amp->subtotal * ($amp->iva / 100);
                                                                    $totalConIVA = $amp->subtotal + $montoIVA;
                                                                @endphp
                                                                <tr>
                                                                    <td>{{ \Carbon\Carbon::parse($amp->created_at)->format('d/m/Y') }}</td>
                                                                    <td class="text-end">${{ number_format($amp->subtotal, 2) }}</td>
                                                                    <td class="text-center">{{ number_format($amp->iva, 2) }}%</td>
                                                                    <td class="text-end">${{ number_format($montoIVA, 2) }}</td>
                                                                    <td class="text-end">${{ number_format($totalConIVA, 2) }}</td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot class="table-group-divider fw-bold">
                                                                <tr>
                                                                    <th class="text-end">Totales:</th>
                                                                    <th class="text-end">${{ number_format($contrato->ampliacionesMonto->sum('subtotal'), 2) }}</th>
                                                                    <th class="text-center">-</th>
                                                                    <th class="text-end">${{ number_format($contrato->ampliacionesMonto->sum(function($amp) {
                                                                        return $amp->subtotal * ($amp->iva / 100);
                                                                    }), 2) }}</th>
                                                                    <th class="text-end">${{ number_format($contrato->ampliacionesMonto->sum(function($amp) {
                                                                        return $amp->subtotal + ($amp->subtotal * ($amp->iva / 100));
                                                                    }), 2) }}</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    
                                </div>
                                @endif
                                
                                <!-- Botones de acción -->
                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('acontratos.show', $contrato->id) }}" 
                                    class="btn btn-primary "
                                    title="Ver detalles">
                                        <i class="fas fa-eye me-1"></i> Ver
                                    </a>
                                 
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        <!-- Paginación -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    Mostrando {{ $contratos->firstItem() }} - {{ $contratos->lastItem() }} de {{ $contratos->total() }} registros
                                </small>
                            </div>
                            <nav aria-label="Page navigation">
                                {{ $contratos->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </nav>
                        </div>
                    @else
                        <!-- Estado vacío -->
                        <div class="text-center py-5 bg-light rounded">
                            <div class="mb-3">
                                <i class="fas fa-file-contract fa-4x text-muted"></i>
                            </div>
                            <h4 class="text-muted mb-3">No se encontraron contratos</h4>
                            <p class="text-muted mb-4">
                                @if(request()->has('search'))
                                No hay resultados que coincidan con tu búsqueda.
                                @else
                                No hay contratos registrados aún.
                                @endif
                            </p>
                            @if(request()->has('search'))
                            <a href="{{ route('acontratos.index') }}" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-times me-1"></i> Limpiar búsqueda
                            </a>
                            @endif
                           
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    @include('footer')
    
    <!-- Modal de confirmación para eliminar -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar el contrato <strong id="obraNombre"></strong>?</p>
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
    
    
</body>
</html>

<!-- 
1.- Quitar los iconos de los montos 
2.- Agregar el desglose de los convenios
3.- Reflejar los montos que se agregaron en los convenios
4.- reflejar la fecha y la duracion dependiendo el convenio
5.- 
-->