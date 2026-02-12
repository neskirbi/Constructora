<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Destajos</title>
    <style>
        .destajo-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
            overflow: hidden;
        }
        .destajo-card:hover {
            box-shadow: 0 5px 20px rgba(0,0,0,0.12);
            transform: translateY(-2px);
        }
        .destajo-header {
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid #dee2e6;
            position: relative;
        }
        .destajo-body {
            padding: 20px;
        }
        .destajo-footer {
            padding: 15px 20px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .destajo-estado {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
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
        .destajo-consecutivo {
            font-size: 1.8rem;
            font-weight: 700;
            color: #0d6efd;
            margin-bottom: 5px;
        }
        .destajo-clave {
            font-size: 1.1rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 10px;
            display: block;
        }
        .destajo-titulo {
            font-size: 1rem;
            color: #6c757d;
            margin-bottom: 5px;
            line-height: 1.5;
        }
        .destajo-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        .info-item {
            display: flex;
            flex-direction: column;
        }
        .info-label {
            font-size: 0.8rem;
            color: #6c757d;
            margin-bottom: 3px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-value {
            font-size: 1rem;
            font-weight: 600;
            color: #495057;
        }
        .info-value.moneda {
            color: #198754;
        }
        .destajo-descripcion {
            color: #495057;
            line-height: 1.6;
            margin: 15px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #0d6efd;
        }
        .destajo-referencia {
            color: #6c757d;
            font-size: 0.9rem;
            font-style: italic;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 3px solid #6c757d;
        }
        .btn-action {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
            border: none;
        }
        .btn-ver {
            background: #0dcaf0;
            color: white;
        }
        .btn-ver:hover {
            background: #0bb6d9;
            color: white;
        }
        .btn-eliminar {
            background: #dc3545;
            color: white;
        }
        .btn-eliminar:hover {
            background: #c82333;
            color: white;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
            background: #f8f9fa;
            border-radius: 12px;
            border: 2px dashed #dee2e6;
        }
        .empty-state i {
            font-size: 3.5rem;
            margin-bottom: 20px;
            color: #adb5bd;
            opacity: 0.7;
        }
        .empty-state h5 {
            font-weight: 600;
            margin-bottom: 10px;
        }
        .pagination-container {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            margin-top: 20px;
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
                                    <i class="fas fa-calculator me-2 text-primary"></i>
                                    Destajos
                                </h5>
                                <a href="{{ route('destajos.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Nuevo Destajo
                                </a>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <form method="GET" action="{{ route('destajos.index') }}">
                                        <div class="input-group">
                                            <input type="text" 
                                                   name="search" 
                                                   class="form-control" 
                                                   placeholder="Buscar por clave, descripción, referencia..." 
                                                   value="{{ $search ?? '' }}">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            @if($search)
                                            <a href="{{ route('destajos.index') }}" class="btn btn-secondary">
                                                <i class="fas fa-times"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-6 text-end">
                                    <span class="text-muted">
                                        <i class="fas fa-list-check me-1"></i>
                                        Total: {{ $destajos->total() }} destajo(s)
                                    </span>
                                </div>
                            </div>

                            @if($destajos->count() > 0)
                                @foreach($destajos as $destajo)
                                <div class="destajo-card">
                                    <div class="destajo-header">
                                        <!-- Título principal: Clave del concepto -->
                                        <div class="destajo-clave">{{ $destajo->clave_concepto }}</div>
                                     
                                        <!-- Estado del destajo - Lado derecho -->
                                        @php
                                            $estadoClase = '';
                                            $estadoTexto = '';
                                            $estadoIcono = '';
                                            
                                            if(isset($destajo->verificado)) {
                                                if($destajo->verificado == 0) {
                                                    $estadoClase = 'estado-rechazado';
                                                    $estadoTexto = 'Rechazado';
                                                    $estadoIcono = 'fa-times-circle';
                                                } elseif($destajo->verificado == 1) {
                                                    $estadoClase = 'estado-pendiente';
                                                    $estadoTexto = 'Pendiente';
                                                    $estadoIcono = 'fa-clock';
                                                } elseif($destajo->verificado == 2) {
                                                    $estadoClase = 'estado-aprobado';
                                                    $estadoTexto = 'Aprobado';
                                                    $estadoIcono = 'fa-check-circle';
                                                }
                                            }
                                        @endphp
                                        
                                        @if(isset($destajo->verificado))
                                        <span class="destajo-estado {{ $estadoClase }}">
                                            <i class="fas {{ $estadoIcono }} me-1"></i>
                                            {{ $estadoTexto }}
                                        </span>
                                        @endif
                                        
                                        <!-- Consecutivo pequeño debajo -->
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                Consecutivo: {{ $destajo->consecutivo }}
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <div class="destajo-body">
                                        <div class="destajo-info-grid">
                                            <div class="info-item">
                                                <span class="info-label">Unidad</span>
                                                <span class="info-value">{{ $destajo->unidad_concepto }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label">Cantidad</span>
                                                <span class="info-value">{{ number_format($destajo->cantidad, 2) }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label">Costo Unitario</span>
                                                <span class="info-value moneda">${{ number_format($destajo->costo_unitario_concepto, 2) }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label">Subtotal</span>
                                                <span class="info-value moneda">${{ number_format($destajo->costo_operado, 2) }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label">IVA</span>
                                                <span class="info-value moneda">${{ number_format($destajo->iva, 2) }}</span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label">Total</span>
                                                <span class="info-value moneda" style="color: #198754; font-size: 1.1rem;">
                                                    ${{ number_format($destajo->total, 2) }}
                                                </span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label">Proveedor</span>
                                                <span class="info-value">
                                                    @php
                                                        $proveedorNombre = \App\Models\ProveedorSer::find($destajo->id_proveedor);
                                                    @endphp
                                                    {{ $proveedorNombre ? $proveedorNombre->nombre : 'N/A' }}
                                                </span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label">Contrato</span>
                                                <span class="info-value">
                                                    @php
                                                        $contratoNo = \App\Models\Contrato::find($destajo->id_contrato);
                                                    @endphp
                                                    {{ $contratoNo ? $contratoNo->contrato_no : 'N/A' }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        @if($destajo->referencia)
                                        <div class="destajo-referencia mt-3">
                                            <i class="fas fa-sticky-note me-2"></i>
                                            <strong>Referencia:</strong> {{ $destajo->referencia }}
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <div class="destajo-footer">
                                        <div>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                Creado: {{ $destajo->created_at->format('d/m/Y H:i') }}
                                            </small>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('destajos.show', $destajo->id) }}" 
                                            class="btn-action btn-ver">
                                                <i class="fas fa-eye"></i> Ver
                                            </a>
                                            
                                            @if(isset($destajo->verificado) && $destajo->verificado == 1)
                                            <button type="button" 
                                                    class="btn-action btn-eliminar"
                                                    onclick="confirmDelete('{{ $destajo->id }}', 'Destajo #{{ $destajo->consecutivo }}')">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="empty-state">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                    <h5>No se encontraron destajos</h5>
                                    <p class="text-muted mb-4">
                                        @if($search)
                                        No hay resultados para tu búsqueda "{{ $search }}"
                                        @else
                                        Aún no has creado ningún destajo
                                        @endif
                                    </p>
                                    @if($search)
                                    <a href="{{ route('destajos.index') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-list me-1"></i> Ver todos los destajos
                                    </a>
                                    @else
                                    <a href="{{ route('destajos.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i> Crear primer destajo
                                    </a>
                                    @endif
                                </div>
                            @endif

                            @if($destajos->hasPages())
                            <div class="pagination-container">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Mostrando {{ $destajos->firstItem() }} a {{ $destajos->lastItem() }} de {{ $destajos->total() }} registros
                                    </div>
                                    <div>
                                        {{ $destajos->appends(request()->query())->links('pagination::bootstrap-4') }}
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
    
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                        Confirmar eliminación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar el destajo <strong id="destajoNombre"></strong>?</p>
                    <p class="text-danger mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Esta acción no se puede deshacer.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </button>
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
            document.getElementById('destajoNombre').textContent = nombre;
            
            const form = document.getElementById('deleteForm');
            form.action = '{{ route("destajos.destroy", "") }}/' + id;
            
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