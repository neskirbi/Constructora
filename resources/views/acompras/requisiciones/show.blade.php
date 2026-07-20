<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Detalle Requisición</title>
    <style>
        .requisicion-item {
            background: white;
            border-radius: 8px;
            padding: 12px 40px 12px 15px;
            margin-bottom: 8px;
            border: 1px solid #e9ecef;
            position: relative;
            transition: all 0.3s;
        }
        .requisicion-item:hover {
            background: #f8f9fa;
        }
        .header-detalle {
            background: white;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #dee2e6;
            margin-bottom: 20px;
        }
        .btn-eliminar-item {
            background: none;
            border: none;
            color: #dc3545;
            font-size: 0.9rem;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 4px;
            transition: all 0.2s;
            opacity: 0.3;
            position: absolute;
            top: 8px;
            right: 8px;
        }
        .btn-eliminar-item:hover {
            opacity: 1;
            background: #dc3545;
            color: white;
        }
        .fila-nueva-proveedor td {
            padding: 8px;
            vertical-align: middle;
        }
        .fila-nueva-proveedor .form-select-sm, 
        .fila-nueva-proveedor .form-control-sm {
            font-size: 0.875rem;
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
                            <i class="fas fa-file-contract text-primary me-2"></i>
                            Detalle Requisición
                        </h4>
                        <div>
                            <button class="btn btn-success me-2" onclick="confirmarCompra()">
                                <i class="fas fa-check me-1"></i> Realizar Compra
                            </button>
                            <a href="{{ route('compras.requisiciones.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Volver
                            </a>
                        </div>
                    </div>

                    <!-- Header del contrato -->
                    <div class="header-detalle">
                        <div class="row">
                            <div class="col-md-8">
                                <strong>Contrato:</strong> 
                                @if($contrato)
                                    {{ $contrato->refinterna }} ({{ $contrato->contrato_no }})
                                @else
                                    <span class="text-warning">Sin contrato</span>
                                @endif
                            </div>
                            <div class="col-md-4 text-end">
                                <strong>Items:</strong> <span id="totalItems">{{ $items->count() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de items -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0"><i class="fas fa-list me-2"></i> Productos / Servicios</h6>
                        </div>
                        <div class="card-body p-0">
                            @foreach($items as $item)
                            <div class="requisicion-item" data-id="{{ $item->id }}">
                                <button class="btn-eliminar-item" onclick="eliminarItem('{{ $item->id }}', '{{ $item->clave }}', this)" title="Eliminar">
                                    <i class="fas fa-times"></i>
                                </button>
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <strong>{{ $item->clave }}</strong>
                                    </div>
                                    <div class="col-md-6">
                                        {{ $item->descripcion }}
                                    </div>
                                    <div class="col-md-2">
                                        <span class="badge bg-secondary">{{ $item->unidad }}</span>
                                        <span class="ms-2">{{ number_format($item->cantidad, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Sección de Proveedores -->
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-users text-primary me-2"></i>
                                Proveedores
                            </h6>
                            <button class="btn btn-sm btn-primary" onclick="agregarFilaProveedor()">
                                <i class="fas fa-plus me-1"></i> Agregar Proveedor
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Proveedor</th>
                                        <th class="text-end">Monto</th>
                                        <th class="text-center" style="width: 120px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaProveedores">
                                    @foreach($requisicionProveedores as $rp)
                                    <tr id="proveedor-row-{{ $rp->id }}">
                                        <td>{{ $rp->proveedor->clave ?? '' }} - {{ $rp->proveedor->nombre ?? 'N/A' }}</td>
                                        <td class="text-end">${{ number_format($rp->monto, 2) }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-danger" onclick="eliminarProveedor('{{ $rp->id }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Total Proveedores</th>
                                        <th class="text-end" id="totalProveedores">${{ number_format($requisicionProveedores->sum('monto'), 2) }}</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Confirmar Compra -->
    <div class="modal fade" id="confirmarCompraModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="confirmarCompraForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmar Compra</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Crear compra con los items de esta requisición?</p>
                        <div class="mt-3">
                            <label for="id_proveedor" class="form-label">Proveedor:</label>
                            <select name="id_proveedor" id="id_proveedor" class="form-select" required>
                                <option value="">Seleccionar proveedor</option>
                                @foreach($proveedores as $proveedor)
                                <option value="{{ $proveedor->id }}">{{ $proveedor->clave }} - {{ $proveedor->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('footer')

    <script>
        function confirmarCompra() {
            const form = document.getElementById('confirmarCompraForm');
            form.action = '{{ url("requisiciones/confirmar") }}/{{ $contratoId }}';
            new bootstrap.Modal(document.getElementById('confirmarCompraModal')).show();
        }

        function eliminarItem(id, clave, element) {
            if (confirm('¿Eliminar ' + clave + '?')) {
                $.ajax({
                    url: '{{ url("requisiciones/eliminar") }}',
                    type: 'POST',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            var item = $(element).closest('.requisicion-item');
                            item.fadeOut(300, function() {
                                $(this).remove();
                                var totalItems = $('.requisicion-item').length;
                                $('#totalItems').text(totalItems);
                                if (totalItems === 0) {
                                    location.reload();
                                }
                            });
                        }
                    },
                    error: function() {
                        alert('Error al eliminar');
                    }
                });
            }
        }

        function agregarFilaProveedor() {
            var row = `
                <tr class="fila-nueva-proveedor">
                    <td>
                        <select class="form-select form-select-sm proveedor-select" style="width: 100%;">
                            <option value="">Seleccionar proveedor</option>
                            @foreach($proveedores as $proveedor)
                            <option value="{{ $proveedor->id }}">{{ $proveedor->clave }} - {{ $proveedor->nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm monto-input" step="0.01" min="0" placeholder="0.00" style="text-align: right;">
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-success" onclick="guardarFilaProveedor(this)">
                            <i class="fas fa-save"></i>
                        </button>
                        <button class="btn btn-sm btn-secondary" onclick="cancelarFilaProveedor(this)">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#tablaProveedores').append(row);
        }

        function guardarFilaProveedor(btn) {
            var row = $(btn).closest('tr');
            var proveedorId = row.find('.proveedor-select').val();
            var monto = row.find('.monto-input').val();
            
            if (!proveedorId) {
                alert('Selecciona un proveedor');
                return;
            }
            
            if (!monto || monto <= 0) {
                alert('Ingresa un monto válido');
                return;
            }
            
            var requisicionId = $('.requisicion-item:first').data('id');
            
            $.ajax({
                url: '{{ url("requisiciones/agregar-proveedor") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    requisicion_id: requisicionId,
                    proveedor_id: proveedorId,
                    monto: monto
                },
                success: function(response) {
                    if (response.success) {
                        row.attr('id', 'proveedor-row-' + response.data.id);
                        row.removeClass('fila-nueva-proveedor');
                        row.html(`
                            <td>${response.data.proveedor}</td>
                            <td class="text-end">$${parseFloat(response.data.monto).toFixed(2)}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-danger" onclick="eliminarProveedor('${response.data.id}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        `);
                        actualizarTotalProveedores();
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + (xhr.responseJSON?.message || 'Error al guardar'));
                }
            });
        }

        function cancelarFilaProveedor(btn) {
            $(btn).closest('tr').remove();
        }

        function eliminarProveedor(id) {
            if (confirm('¿Eliminar este proveedor?')) {
                $.ajax({
                    url: '{{ url("requisiciones/eliminar-proveedor") }}/' + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#proveedor-row-' + id).fadeOut(300, function() {
                                $(this).remove();
                                actualizarTotalProveedores();
                            });
                        }
                    },
                    error: function() {
                        alert('Error al eliminar proveedor');
                    }
                });
            }
        }

        function actualizarTotalProveedores() {
            var total = 0;
            $('#tablaProveedores tr:not(.fila-nueva-proveedor)').each(function() {
                var montoText = $(this).find('td:eq(1)').text();
                if (montoText) {
                    var monto = parseFloat(montoText.replace('$', '').replace(/,/g, ''));
                    if (!isNaN(monto)) {
                        total += monto;
                    }
                }
            });
            $('#totalProveedores').text('$' + total.toFixed(2));
        }
    </script>
</body>
</html>