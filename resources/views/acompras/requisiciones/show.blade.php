<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Detalle Requisición</title>
    <style>
        .header-detalle {
            background: white;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #dee2e6;
            margin-bottom: 20px;
        }
        .badge-procesada {
            background: #28a745;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        .card-item {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 12px;
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
            transition: all 0.3s;
            position: relative;
        }
        .card-item:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-color: #dee2e6;
        }
        .card-item .btn-eliminar {
            position: absolute;
            top: 12px;
            right: 12px;
            background: none;
            border: none;
            color: #dc3545;
            font-size: 1.1rem;
            cursor: pointer;
            opacity: 0.3;
            transition: all 0.2s;
            padding: 4px 8px;
            border-radius: 4px;
        }
        .card-item .btn-eliminar:hover {
            opacity: 1;
            background: #dc3545;
            color: white;
        }
        .card-item .clave {
            font-weight: 700;
            color: #0d6efd;
            font-size: 1.1rem;
        }
        .card-item .descripcion {
            color: #495057;
            font-size: 0.95rem;
        }
        .card-item .unidad-badge {
            background: #e9ecef;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            color: #495057;
        }
        .card-item .input-cantidad {
            width: 100px;
            text-align: right;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 4px 8px;
            font-size: 0.9rem;
            font-weight: 500;
            display: inline-block;
        }
        .card-item .input-cantidad:focus {
            border-color: #0d6efd;
            outline: none;
            box-shadow: 0 0 0 3px rgba(13,110,253,0.15);
        }
        .card-item .label-campo {
            font-size: 0.7rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        .card-item .fila-proveedores {
            margin-top: 10px;
            border-top: 1px solid #e9ecef;
            padding-top: 10px;
        }
        .card-item .fila-proveedores .proveedor-row {
            padding: 6px 0;
            border-bottom: 1px solid #f1f3f5;
        }
        .card-item .fila-proveedores .proveedor-row:last-child {
            border-bottom: none;
        }
        .card-item .fila-proveedores .proveedor-row .select-proveedor {
            width: 100%;
            font-size: 0.85rem;
            padding: 5px 8px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }
        .card-item .fila-proveedores .proveedor-row .input-precio,
        .card-item .fila-proveedores .proveedor-row .input-descuento {
            width: 100%;
            text-align: right;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 5px 8px;
            font-size: 0.85rem;
        }
        .card-item .fila-proveedores .proveedor-row .btn-eliminar-proveedor {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 4px;
            opacity: 0.5;
            transition: all 0.2s;
            font-size: 1rem;
        }
        .card-item .fila-proveedores .proveedor-row .btn-eliminar-proveedor:hover {
            opacity: 1;
            background: #dc3545;
            color: white;
        }
        .card-item .btn-agregar-proveedor {
            margin-top: 10px;
            padding: 4px 14px;
            font-size: 0.75rem;
            border-radius: 4px;
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .card-item .btn-agregar-proveedor:hover {
            background: #218838;
        }
        .card-item .btn-agregar-proveedor i {
            margin-right: 4px;
        }
        .card-item .btn-guardar-item {
            margin-top: 10px;
            padding: 6px 30px;
            font-size: 0.9rem;
            border-radius: 4px;
            background: #0d6efd;
            color: white;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            float: right;
        }
        .card-item .btn-guardar-item:hover {
            background: #0b5ed7;
        }
        .resumen-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            border: 1px solid #dee2e6;
        }
        .resumen-card .label {
            font-size: 0.8rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        .resumen-card .valor {
            font-size: 1.2rem;
            font-weight: 700;
        }
        .resumen-card .valor.total {
            color: #198754;
            font-size: 1.4rem;
        }
        @media (max-width: 768px) {
            .card-item .fila-proveedores .proveedor-row .select-proveedor,
            .card-item .fila-proveedores .proveedor-row .input-precio,
            .card-item .fila-proveedores .proveedor-row .input-descuento {
                width: 100%;
            }
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
                            @if($requisicion->procesada == 1)
                                <span class="badge-procesada"><i class="fas fa-check-circle me-1"></i> Procesada</span>
                            @endif
                        </h4>
                        <div>
                            @if(0)
                            <button class="btn btn-success me-2" onclick="confirmarCompra()">
                                <i class="fas fa-check me-1"></i> Realizar Compra
                            </button>
                            @endif
                            <a href="{{ url('requisiciones') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Volver
                            </a>
                        </div>
                    </div>

                    <!-- Header -->
                    <div class="header-detalle">
                        <div class="row">
                            <div class="col-md-3"><strong># Requisición:</strong> {{ $requisicion->consecutivo ?? 'N/A' }}</div>
                            <div class="col-md-3"><strong>Frente:</strong> {{ $requisicion->frente ?? 'N/A' }}</div>
                            <div class="col-md-3"><strong>Empresa:</strong> {{ $requisicion->empresa ?? 'N/A' }}</div>
                            <div class="col-md-3"><strong>Contrato:</strong> {{ $contrato->consecutivo ?? 'N/A' }}</div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-4"><strong>Proyecto:</strong> {{ $requisicion->proyecto ?? 'N/A' }}</div>
                            <div class="col-md-4"><strong>Cliente:</strong> {{ $requisicion->cliente ?? 'N/A' }}</div>
                            <div class="col-md-4"><strong>Contratista:</strong> {{ $requisicion->contratista ?? 'N/A' }}</div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-4"><strong>Partida:</strong> {{ $requisicion->partida ?? 'N/A' }}</div>
                            <div class="col-md-4"><strong>Fecha:</strong> {{ $requisicion->created_at ? \Carbon\Carbon::parse($requisicion->created_at)->format('d/m/Y') : 'N/A' }}</div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12"><strong>Dirección:</strong> {{ $requisicion->direccion_entrega ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <!-- Lista de items como tarjetas -->
                    @foreach($items as $item)
                    <div class="card-item" data-id="{{ $item->id }}">
                        <button class="btn-eliminar" onclick="eliminarItem('{{ $item->id }}', '{{ $item->clave }}', this)" title="Eliminar">
                            <i class="fas fa-times"></i>
                        </button>

                        <!-- Fila 1: Clave, Descripción, Unidad y Cantidad -->
                        <div class="row">
                            <div class="col-md-12">
                                <span class="clave">{{ $item->clave }}</span>
                                <span class="descripcion ms-2">{{ $item->descripcion }}</span>
                                <span class="unidad-badge ms-2">{{ $item->unidad }}</span>
                                <span class="ms-3">
                                    <span class="label-campo">Cantidad:</span>
                                    <input type="number" class="input-cantidad" value="{{ $item->cantidad }}" 
                                           step="0.01" min="0" id="cantidad-{{ $item->id }}"
                                           noformat>
                                </span>
                            </div>
                        </div>

                        <!-- Fila 2: Proveedores -->
                        <div class="fila-proveedores" id="proveedores-{{ $item->id }}">
                            @foreach($item->proveedores as $proveedor)
                            <div class="proveedor-row" data-id="{{ $proveedor->id }}">
                                <div class="row">
                                    <div class="col-4">
                                        <span class="label-campo">Proveedor</span>
                                        <select class="select-proveedor">
                                            <option value="">Sin proveedor</option>
                                            @foreach($proveedores as $p)
                                            <option value="{{ $p->id }}" {{ $proveedor->proveedor_id == $p->id ? 'selected' : '' }}>
                                                {{ $p->clave }} - {{ $p->nombre }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <span class="label-campo">Precio</span>
                                        <input type="number" class="input-precio" value="{{ $proveedor->precio }}" step="0.01" min="0" placeholder="0.00">
                                    </div>
                                    <div class="col-3">
                                        <span class="label-campo">Descuento </span>
                                        <input type="number" class="input-descuento" value="{{ $proveedor->descuento }}" step="0.01" min="0" max="100" placeholder="0">
                                    </div>
                                    <div class="col-2 text-end">
                                        <span class="label-campo">&nbsp;</span>
                                        <button class="btn-eliminar-proveedor" onclick="eliminarProveedor(this, '{{ $proveedor->id }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Botones: Agregar Proveedor y Guardar Item -->
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
                            <button class="btn-agregar-proveedor" onclick="agregarFilaProveedor('{{ $item->id }}')">
                                <i class="fas fa-user-plus"></i> Agregar Proveedor
                            </button>
                            <button class="btn-guardar-item" onclick="guardarItem('{{ $item->id }}')">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                        </div>
                    </div>
                    @endforeach
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
                            <label for="id_proveedor" class="form-label">Proveedor General:</label>
                            <select name="id_proveedor" id="id_proveedor" class="form-select">
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
            form.action = '{{ url("requisiciones/confirmar") }}/{{ $requisicion->id }}';
            new bootstrap.Modal(document.getElementById('confirmarCompraModal')).show();
        }

        function eliminarItem(id, clave, element) {
            if (confirm('¿Eliminar ' + clave + '?')) {
                $.ajax({
                    url: '{{ url("requisiciones/eliminar-item") }}',
                    type: 'POST',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $(element).closest('.card-item').fadeOut(300, function() {
                                $(this).remove();
                            });
                        }
                    },
                    error: function() {
                        alert('Error al eliminar');
                    }
                });
            }
        }

        function agregarFilaProveedor(itemId) {
            var container = $('#proveedores-' + itemId);
            var row = `
                <div class="proveedor-row" data-id="nuevo">
                    <div class="row">
                        <div class="col-4">
                            <span class="label-campo">Proveedor</span>
                            <select class="select-proveedor">
                                <option value="">Sin proveedor</option>
                                @foreach($proveedores as $proveedor)
                                <option value="{{ $proveedor->id }}">{{ $proveedor->clave }} - {{ $proveedor->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3">
                            <span class="label-campo">Precio</span>
                            <input type="number" class="input-precio" step="0.01" min="0" placeholder="0.00">
                        </div>
                        <div class="col-3">
                            <span class="label-campo">Descuento </span>
                            <input type="number" class="input-descuento" step="0.01" min="0" max="100" placeholder="0">
                        </div>
                        <div class="col-2 text-end">
                            <span class="label-campo">&nbsp;</span>
                            <button class="btn-eliminar-proveedor" onclick="eliminarFilaProveedor(this)">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.append(row);
        }

        function eliminarFilaProveedor(btn) {
            $(btn).closest('.proveedor-row').remove();
        }

        function eliminarProveedor(btn, proveedorId) {
            if (confirm('¿Eliminar este proveedor?')) {
                $.ajax({
                    url: '{{ url("requisiciones/eliminar-proveedor-item") }}/' + proveedorId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $(btn).closest('.proveedor-row').fadeOut(300, function() {
                                $(this).remove();
                            });
                        }
                    },
                    error: function() {
                        alert('Error al eliminar proveedor');
                    }
                });
            }
        }

        function guardarItem(itemId) {
            var cantidad = $('#cantidad-' + itemId).val();
            
            var proveedores = [];
            var container = $('#proveedores-' + itemId);
            container.find('.proveedor-row').each(function() {
                var row = $(this);
                var proveedorId = row.find('.select-proveedor').val();
                var precio = row.find('.input-precio').val();
                var descuento = row.find('.input-descuento').val();
                var rowId = row.data('id');
                
                if (proveedorId) {
                    proveedores.push({
                        id: rowId,
                        proveedor_id: proveedorId,
                        precio: precio || 0,
                        descuento: descuento || 0
                    });
                }
            });

            if (proveedores.length === 0) {
                alert('Agrega al menos un proveedor');
                return;
            }

            $.ajax({
                url: '{{ url("requisiciones/guardar-item-completo") }}',
                type: 'POST',
                data: {
                    id: itemId,
                    cantidad: cantidad,
                    proveedores: proveedores,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $.each(response.data.proveedores, function(index, prov) {
                            var row = $('#proveedores-' + itemId).find('.proveedor-row[data-id="nuevo"]');
                            if (row.length > 0) {
                                row.data('id', prov.id);
                                row.find('.btn-eliminar-proveedor').attr('onclick', 'eliminarProveedor(this, "' + prov.id + '")');
                            }
                        });
                        var btn = $('.card-item[data-id="' + itemId + '"] .btn-guardar-item');
                        var originalText = btn.html();
                        btn.html('<i class="fas fa-check"></i> Guardado');
                        setTimeout(function() {
                            btn.html(originalText);
                        }, 1500);
                    }
                },
                error: function() {
                    alert('Error al guardar item');
                }
            });
        }
    </script>
</body>
</html>