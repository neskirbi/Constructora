<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Ver Destajo</title>
</head>
<body>
    <div class="main-container">
        @include('toast.toasts')
        @include('administradores.sidebar')
        
        <main class="main-content" id="mainContent">
            @include('administradores.navbar')

            <div class="content-area">
                <div class="container-fluid py-4">
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <!-- Header -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h4 class="mb-1">
                                        <i class="fas fa-file-invoice me-2 text-primary"></i>
                                        Destajo #{{ $destajo->consecutivo }}
                                    </h4>
                                    @if($destajo->verificado == 1)
                                        <span class="badge bg-warning">Pendiente</span>
                                    @elseif($destajo->verificado == 2)
                                        <span class="badge bg-success">Aprobado</span>
                                    @elseif($destajo->verificado == 0)
                                        <span class="badge bg-danger">Rechazado</span>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('destajos.index') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-arrow-left me-1"></i> Regresar
                                    </a>
                                </div>
                            </div>

                            <!-- Información Principal -->
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Contrato -->
                                        <div class="col-md-6 mb-3">
                                            <small class="text-muted d-block">
                                                <i class="fas fa-file-contract me-1"></i> Contrato
                                            </small>
                                            <span class="fw-bold">
                                                @php
                                                    $contrato = $contratos->firstWhere('id', $destajo->id_contrato);
                                                @endphp
                                                {{ $contrato->contrato_no ?? 'No especificado' }}
                                            </span>
                                        </div>
                                        
                                        <!-- Proveedor -->
                                        <div class="col-md-6 mb-3">
                                            <small class="text-muted d-block">
                                                <i class="fas fa-truck me-1"></i> Proveedor
                                            </small>
                                            <span class="fw-bold">
                                                @php
                                                    $proveedor = $proveedores->firstWhere('id', $destajo->id_proveedor);
                                                @endphp
                                                {{ $proveedor->clave ?? '' }} - {{ $proveedor->nombre ?? 'No especificado' }}
                                            </span>
                                        </div>
                                        
                                        <!-- Concepto -->
                                        <div class="col-12 mb-3">
                                            <small class="text-muted d-block">
                                                <i class="fas fa-list-alt me-1"></i> Concepto
                                            </small>
                                            <span class="fw-bold">{{ $destajo->clave_concepto }}</span>
                                            <p class="mb-0 mt-1">{{ $destajo->descripcion_concepto }}</p>
                                        </div>
                                        
                                        <!-- Unidad -->
                                        <div class="col-md-6 mb-3">
                                            <small class="text-muted d-block">
                                                <i class="fas fa-ruler me-1"></i> Unidad
                                            </small>
                                            <span>{{ $destajo->unidad_concepto }}</span>
                                        </div>
                                        
                                        <!-- Referencia -->
                                        <div class="col-md-6 mb-3">
                                            <small class="text-muted d-block">
                                                <i class="fas fa-tag me-1"></i> Referencia
                                            </small>
                                            <span>{{ $destajo->referencia ?? 'Sin referencia' }}</span>
                                        </div>
                                        
                                        <!-- Fechas -->
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">
                                                <i class="fas fa-calendar-plus me-1"></i> Creado
                                            </small>
                                            <span>{{ $destajo->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">
                                                <i class="fas fa-calendar-check me-1"></i> Actualizado
                                            </small>
                                            <span>{{ $destajo->updated_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Montos -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-calculator me-2"></i>
                                        Cálculo del Destajo
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <!-- Fila 1: Cantidad × PU = Subtotal -->
                                    <div class="row align-items-center mb-4">
                                        <!-- Cantidad -->
                                        <div class="col-md-3 text-center">
                                            <small class="text-muted d-block mb-2">
                                                <i class="fas fa-hashtag me-1"></i> Cantidad
                                            </small>
                                            <div class="h1 text-dark">
                                                {{ number_format($destajo->cantidad, 2) }}
                                            </div>
                                        </div>
                                        
                                        <!-- Signo × -->
                                        <div class="col-md-1 text-center">
                                            <div class="h3 text-muted mt-4">×</div>
                                        </div>
                                        
                                        <!-- Precio Unitario -->
                                        <div class="col-md-3 text-center">
                                            <small class="text-muted d-block mb-2">
                                                <i class="fas fa-dollar-sign me-1"></i> Precio Unitario
                                            </small>
                                            <div class="h1 text-dark">
                                                ${{ number_format($destajo->costo_unitario_concepto, 2) }}
                                            </div>
                                        </div>
                                        
                                        <!-- Signo = -->
                                        <div class="col-md-1 text-center">
                                            <div class="h3 text-muted mt-4">=</div>
                                        </div>
                                        
                                        <!-- Subtotal -->
                                        <div class="col-md-4 text-center">
                                            <small class="text-muted d-block mb-2">
                                                <i class="fas fa-calculator me-1"></i> Subtotal
                                            </small>
                                            <div class="h1 fw-bold text-dark">
                                                ${{ number_format($destajo->costo_operado, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Fila 2: IVA + Subtotal = Total -->
                                    <div class="row align-items-center mt-4 pt-4 border-top">
                                        <!-- IVA -->
                                        <div class="col-md-4 text-center">
                                            <small class="text-muted d-block mb-2">
                                                <i class="fas fa-percentage me-1"></i> IVA
                                            </small>
                                            <div class="h2 text-dark">
                                                ${{ number_format($destajo->iva, 2) }}
                                            </div>
                                        </div>
                                        
                                        <!-- Signo + -->
                                        <div class="col-md-1 text-center">
                                            <div class="h3 text-muted">+</div>
                                        </div>
                                        
                                        <!-- Subtotal (repetido para claridad) -->
                                        <div class="col-md-3 text-center">
                                            <small class="text-muted d-block mb-2">
                                                <i class="fas fa-calculator me-1"></i> Subtotal
                                            </small>
                                            <div class="h2 text-dark">
                                                ${{ number_format($destajo->costo_operado, 2) }}
                                            </div>
                                        </div>
                                        
                                        <!-- Signo = -->
                                        <div class="col-md-1 text-center">
                                            <div class="h3 text-muted">=</div>
                                        </div>
                                        
                                        <!-- Total -->
                                        <div class="col-md-3 text-center">
                                            <small class="text-muted d-block mb-2">
                                                <i class="fas fa-file-invoice-dollar me-1"></i> Total
                                            </small>
                                            <div class="display-4 fw-bold text-dark">
                                                ${{ number_format($destajo->total, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones de Acción -->
                            @if($destajo->verificado == 1)
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-muted small">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Este destajo está pendiente de verificación
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-outline-danger me-2" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#rechazarModal">
                                                <i class="fas fa-times me-1"></i> Rechazar
                                            </button>
                                            
                                            <button type="button" class="btn btn-success" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#confirmarModal">
                                                <i class="fas fa-check me-1"></i> Confirmar
                                            </button>
                                        </div>
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

    <!-- Modal Rechazar -->
    <div class="modal fade" id="rechazarModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rechazar Destajo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas rechazar el destajo <strong>#{{ $destajo->consecutivo }}</strong>?</p>
                    <p class="text-danger small mb-0">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form method="POST" action="{{ route('adestajos.rechazar', $destajo->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-ban me-1"></i> Rechazar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Confirmar -->
    <div class="modal fade" id="confirmarModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Destajo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas confirmar el destajo <strong>#{{ $destajo->consecutivo }}</strong>?</p>
                    <p class="text-success small mb-0">El destajo quedará verificado en el sistema.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form method="POST" action="{{ route('adestajos.confirmar', $destajo->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-1"></i> Confirmar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('footer')
</body>
</html>