<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Ver Compra</title>
    <style>
        .compra-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
            overflow: hidden;
        }
        .compra-header {
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .compra-consecutivo {
            font-size: 2rem;
            font-weight: 700;
            color: #0d6efd;
            margin: 0;
        }
        .compra-proveedor {
            font-size: 1.1rem;
            color: #495057;
            font-weight: 500;
            margin-bottom: 20px;
            padding: 10px 15px;
            background: #e7f1ff;
            border-radius: 8px;
            border-left: 4px solid #0d6efd;
        }
        .compra-proveedor i {
            color: #0d6efd;
            margin-right: 8px;
        }
        .compra-body {
            padding: 20px;
        }
        .compra-footer {
            padding: 15px 20px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .compra-estado {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .estado-rechazado {
            background: #fee;
            color: #dc3545;
            border: 1px solid #f5c6cb;
        }
        .estado-pendiente {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .estado-aprobado {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .detalles-table {
            width: 100%;
            font-size: 0.95rem;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .detalles-table th {
            background-color: #f8f9fa;
            padding: 12px;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
        }
        .detalles-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e9ecef;
        }
        .detalles-table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .moneda {
            color: #198754;
            font-weight: 600;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .info-item {
            display: flex;
            flex-direction: column;
        }
        .info-label {
            font-size: 0.8rem;
            color: #6c757d;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: #495057;
        }
        .info-value.moneda {
            color: #198754;
        }
        .btn-action {
            padding: 8px 20px;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }
        .btn-confirmar {
            background: #198754;
            color: white;
        }
        .btn-confirmar:hover {
            background: #146c43;
            color: white;
        }
        .btn-rechazar {
            background: #dc3545;
            color: white;
        }
        .btn-rechazar:hover {
            background: #b02a37;
            color: white;
        }
        .btn-pendiente {
            background: #ffc107;
            color: #212529;
        }
        .btn-pendiente:hover {
            background: #e0a800;
            color: #212529;
        }
        .btn-regresar {
            background: #6c757d;
            color: white;
        }
        .btn-regresar:hover {
            background: #5a6268;
            color: white;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
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
                    <div class="compra-card">
                        <div class="compra-header">
                            <div class="compra-consecutivo">
                                <i class="fas fa-hashtag me-2"></i>
                                Compra #{{ $compra->consecutivo }}
                            </div>
                            
                            @php
                                $estadoClase = '';
                                $estadoTexto = '';
                                $estadoIcono = '';
                                
                                if(isset($compra->verificado)) {
                                    if($compra->verificado == 0) {
                                        $estadoClase = 'estado-rechazado';
                                        $estadoTexto = 'Rechazado';
                                        $estadoIcono = 'fa-times-circle';
                                    } elseif($compra->verificado == 1) {
                                        $estadoClase = 'estado-pendiente';
                                        $estadoTexto = 'Pendiente';
                                        $estadoIcono = 'fa-clock';
                                    } elseif($compra->verificado == 2) {
                                        $estadoClase = 'estado-aprobado';
                                        $estadoTexto = 'Aprobado';
                                        $estadoIcono = 'fa-check-circle';
                                    }
                                }
                            @endphp
                            
                            @if(isset($compra->verificado))
                            <span class="compra-estado {{ $estadoClase }}">
                                <i class="fas {{ $estadoIcono }} me-1"></i>
                                {{ $estadoTexto }}
                            </span>
                            @endif
                        </div>
                        
                        <div class="compra-body">
    <!-- Proveedor -->
    <div class="compra-proveedor">
        <i class="fas fa-building"></i>
        <strong>Proveedor:</strong> {{ $compra->proveedor_nombre ?? 'Proveedor no encontrado' }}
        @if(isset($compra->proveedor_clave))
        <span class="text-muted ms-2">({{ $compra->proveedor_clave }})</span>
        @endif
    </div>
    
    <!-- Grid de información -->
    <div class="info-grid">
        <div class="info-item">
            <span class="info-label">Referencia</span>
            <span class="info-value">{{ $compra->referencia ?? 'N/A' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Contrato</span>
            <span class="info-value">{{ $compra->contrato_no ?? 'N/A' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Costo Operado</span>
            <span class="info-value moneda">${{ number_format($compra->costo_operado, 2) }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">IVA</span>
            <span class="info-value moneda">${{ number_format($compra->iva, 2) }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Total</span>
            <span class="info-value moneda" style="font-size: 1.2rem;">${{ number_format($compra->total, 2) }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Creado por</span>
            <span class="info-value">{{ $compra->usuario_nombres ?? 'N/A' }} {{ $compra->usuario_apellidos ?? '' }}</span>
        </div>
        
        <!-- NUEVOS CAMPOS: Fecha Entrega y Tipo Entrega -->
        <div class="info-item">
            <span class="info-label">Fecha de Entrega</span>
            <span class="info-value">{{ $compra->fecha_entrega ? \Carbon\Carbon::parse($compra->fecha_entrega)->format('d/m/Y') : 'N/A' }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Tipo de Entrega</span>
            <span class="info-value">
                @if($compra->tipo_entrega == 'recoleccion')
                    <span class="badge bg-info">Recolección</span>
                @elseif($compra->tipo_entrega == 'envio')
                    <span class="badge bg-primary">Envío</span>
                @else
                    N/A
                @endif
            </span>
        </div>
    </div>

    <!-- NUEVO: Comentarios (si existen) -->
    @if($compra->comentarios)
    <div class="row mt-3">
        <div class="col-12">
            <div class="card border-light">
                <div class="card-header bg-light py-2">
                    <h6 class="mb-0"><i class="fas fa-comment me-2"></i>Comentarios</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $compra->comentarios }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Productos/Servicios -->
    @if(isset($detalles) && count($detalles) > 0)
    <h6 class="fw-bold mb-3 mt-4">
        <i class="fas fa-boxes me-2"></i>
        Productos / Servicios
    </h6>
    <div class="table-responsive">
        <table class="detalles-table">
            <thead>
                <tr>
                    <th>Clave</th>
                    <th>Descripción</th>
                    <th>Unidad</th>
                    <th class="text-end">Cantidad</th>
                    <th class="text-end">% Desc.</th>
                    <th class="text-end">Monto Desc.</th>
                    <th class="text-end">P. Unitario</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detalles as $detalle)
                @php
                    $descuentoPorcentaje = $detalle->descuento_porcentaje ?? 0;
                    $descuentoMonto = $detalle->descuento_monto ?? 0;
                    $subtotal = $detalle->cantidad * $detalle->ult_costo;
                    $subtotalConDescuento = $subtotal - $descuentoMonto;
                @endphp
                <tr>
                    <td><strong>{{ $detalle->clave }}</strong></td>
                    <td>{{ $detalle->descripcion }}</td>
                    <td>{{ $detalle->unidades }}</td>
                    <td class="text-end">{{ number_format($detalle->cantidad, 4) }}</td>
                    <td class="text-end">{{ $descuentoPorcentaje > 0 ? number_format($descuentoPorcentaje, 2) . '%' : '-' }}</td>
                    <td class="text-end moneda">{{ $descuentoMonto > 0 ? '$' . number_format($descuentoMonto, 2) : '-' }}</td>
                    <td class="text-end moneda">${{ number_format($detalle->ult_costo, 2) }}</td>
                    <td class="text-end moneda">${{ number_format($subtotalConDescuento, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
                        
                        <div class="compra-footer">
                            <div>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    Creado: {{ \Carbon\Carbon::parse($compra->created_at)->format('d/m/Y H:i') }}
                                </small>
                                @if($compra->updated_at && $compra->created_at != $compra->updated_at)
                                <br>
                               
                                @endif
                            </div>
                            <div class="action-buttons">
                                <a href="{{ route('acompras.index') }}" class="btn-action btn-regresar">
                                    <i class="fas fa-arrow-left"></i> Regresar
                                </a>
                                
                                @if(isset($compra->verificado))
                                    @if($compra->verificado == 1) {{-- Pendiente --}}
                                        <button type="button" class="btn-action btn-confirmar" onclick="abrirModal('confirmar', '{{ $compra->id }}', '{{ $compra->consecutivo }}')">
                                            <i class="fas fa-check-circle"></i> Aprobar
                                        </button>
                                        <button type="button" class="btn-action btn-rechazar" onclick="abrirModal('rechazar', '{{ $compra->id }}', '{{ $compra->consecutivo }}')">
                                            <i class="fas fa-times-circle"></i> Rechazar
                                        </button>
                                    @elseif($compra->verificado == 2) {{-- Aprobado --}}
                                        <button type="button" class="btn-action btn-rechazar" onclick="abrirModal('rechazar', '{{ $compra->id }}', '{{ $compra->consecutivo }}')">
                                            <i class="fas fa-times-circle"></i> Rechazar
                                        </button>
                                    @elseif($compra->verificado == 0) {{-- Rechazado --}}
                                        <button type="button" class="btn-action btn-confirmar" onclick="abrirModal('confirmar', '{{ $compra->id }}', '{{ $compra->consecutivo }}')">
                                            <i class="fas fa-check-circle"></i> Aprobar
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal de Confirmación -->
    <div class="modal fade" id="accionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" id="modalHeader">
                    <h5 class="modal-title" id="modalTitle">
                        <i class="fas fa-question-circle me-2"></i>
                        Confirmar acción
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="modalMessage">¿Estás seguro de que deseas realizar esta acción?</p>
                    <p class="mb-0" id="compraInfo"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </button>
                    <form id="accionForm" method="POST" style="display: inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn" id="modalBoton">
                            <i class="fas fa-check me-1"></i> Confirmar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('footer')
    
    <script>
    function abrirModal(accion, id, consecutivo) {
        const modal = new bootstrap.Modal(document.getElementById('accionModal'));
        const modalTitle = document.getElementById('modalTitle');
        const modalMessage = document.getElementById('modalMessage');
        const compraInfo = document.getElementById('compraInfo');
        const modalBoton = document.getElementById('modalBoton');
        const modalHeader = document.getElementById('modalHeader');
        const accionForm = document.getElementById('accionForm');
        
        if (accion === 'confirmar') {
            modalTitle.innerHTML = '<i class="fas fa-check-circle text-success me-2"></i> Aprobar Compra';
            modalMessage.textContent = '¿Estás seguro de que deseas APROBAR esta compra?';
            modalBoton.className = 'btn btn-success';
            modalBoton.innerHTML = '<i class="fas fa-check-circle me-1"></i> Sí, Aprobar';
            accionForm.action = '{{ route("compras.confirmar", ":id") }}'.replace(':id', id);
        } else if (accion === 'rechazar') {
            modalTitle.innerHTML = '<i class="fas fa-times-circle text-danger me-2"></i> Rechazar Compra';
            modalMessage.textContent = '¿Estás seguro de que deseas RECHAZAR esta compra?';
            modalBoton.className = 'btn btn-danger';
            modalBoton.innerHTML = '<i class="fas fa-times-circle me-1"></i> Sí, Rechazar';
            accionForm.action = '{{ route("compras.rechazar", ":id") }}'.replace(':id', id);
        }
        
        compraInfo.innerHTML = '<strong>Compra #' + consecutivo + '</strong>';
        modal.show();
    }
    </script>
</body>
</html>