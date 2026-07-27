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
        .card-item .input-fecha {
            width: 100%;
            font-size: 0.85rem;
            padding: 5px 8px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }
        .card-item .input-fecha:focus {
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
            display: block;
            margin-bottom: 4px;
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
        .card-item .fila-proveedores .proveedor-row .form-check-input {
            cursor: pointer;
            width: 20px;
            height: 20px;
            margin-top: 0;
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
        .card-item .btn-guardar-item.guardando {
            background: #ffc107;
            color: #212529;
            pointer-events: none;
            opacity: 0.7;
        }
        .card-item .btn-guardar-item.guardando i {
            animation: spin 1s linear infinite;
        }
        .card-item .btn-agregar-proveedor.guardando {
            background: #ffc107;
            color: #212529;
            pointer-events: none;
            opacity: 0.7;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .d-flex-center {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .h-100 {
            min-height: 38px;
        }
        @media (max-width: 768px) {
            .card-item .fila-proveedores .proveedor-row .select-proveedor,
            .card-item .fila-proveedores .proveedor-row .input-precio,
            .card-item .fila-proveedores .proveedor-row .input-descuento,
            .card-item .fila-proveedores .proveedor-row .input-fecha {
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
                            <div class="col-md-4"><strong>Fecha Solicitud:</strong> {{ $requisicion->fecha_solicitud ? \Carbon\Carbon::parse($requisicion->fecha_solicitud)->format('d/m/Y') : 'N/A' }}</div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12"><strong>Dirección:</strong> {{ $requisicion->direccion_entrega ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <!-- Lista de items como tarjetas -->
                    @foreach($items as $item)
                    <div class="card-item" data-id="{{ $item->id }}">
                        <button class="btn-eliminar" onclick="eliminarItem('{{ $item->id }}', '{{ $item->clave }}', this)" title="Eliminar" {{ $requisicion->procesada == 1 ? 'disabled' : '' }}>
                            <i class="fas fa-times"></i>
                        </button>

                        <div class="row">
                            <div class="col-md-12">
                                <span class="clave">{{ $item->clave }}</span>
                                <span class="descripcion ms-2">{{ $item->descripcion }}</span>
                                <span class="unidad-badge ms-2">{{ $item->unidad }}</span>
                            </div>
                        </div>

                        <!-- Campos horizontales debajo -->
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <span class="label-campo" style="font-size: 0.65rem; color: #6c757d; display: block;">Cant. Requisición</span>
                                <input type="number" class="input-cantidad" value="{{ $item->cantidad }}" 
                                    step="0.01" min="0" id="cantidad-{{ $item->id }}"
                                    style="width: 100%; text-align: right; border: 1px solid #dee2e6; border-radius: 4px; padding: 4px 6px;"
                                    {{ $requisicion->procesada == 1 ? 'disabled' : '' }}>
                            </div>
                            <div class="col-md-3">
                                <span class="label-campo" style="font-size: 0.65rem; color: #6c757d; display: block;">Cant. Inventario</span>
                                <input type="number" class="input-inventario" 
                                    value="{{ $item->stock ? $item->stock->cantidad : 0 }}" 
                                    step="0.01" min="0" id="inventario-{{ $item->id }}"
                                    style="width: 100%; text-align: right; border: 1px solid #dee2e6; border-radius: 4px; padding: 4px 6px; background: #e9ecef;"
                                    disabled>
                            </div>
                            <div class="col-md-3">
                                <span class="label-campo" style="font-size: 0.65rem; color: #6c757d; display: block;">Cant. a Comprar</span>
                                <input type="number" class="input-comprar" 
                                    value="{{ max(0, $item->cantidad - ($item->stock ? $item->stock->cantidad : 0)) }}"
                                    step="0.01" min="0" id="comprar-{{ $item->id }}"
                                    style="width: 100%; text-align: right; border: 1px solid #dee2e6; border-radius: 4px; padding: 4px 6px; background: #fff3cd; font-weight: 600;"
                                    {{ $requisicion->procesada == 1 ? 'disabled' : '' }}>
                            </div>
                        </div>

                        <!-- Fila 2: Proveedores -->
                        <div class="fila-proveedores" id="proveedores-{{ $item->id }}">
                            @foreach($item->proveedores as $proveedor)
                            <div class="proveedor-row" data-id="{{ $proveedor->id }}">
                                <div class="row align-items-center">
                                    <div class="col-1">
                                        <div class="d-flex-center h-100">
                                            <input class="form-check-input" type="checkbox" 
                                                id="check-{{ $proveedor->id }}" 
                                                {{ $proveedor->seleccionado ? 'checked' : '' }}
                                                {{ $requisicion->procesada == 1 ? 'disabled' : '' }}>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <span class="label-campo">Proveedor</span>
                                        <select class="select-proveedor" id="proveedor-select-{{ $proveedor->id }}" {{ $requisicion->procesada == 1 ? 'disabled' : '' }}>
                                            <option value="">Sin proveedor</option>
                                            @foreach($proveedores as $p)
                                            <option value="{{ $p->id }}" {{ $proveedor->proveedor_id == $p->id ? 'selected' : '' }}>
                                                {{ $p->clave }} - {{ $p->nombre }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <span class="label-campo">Precio</span>
                                        <input type="number" class="input-precio" value="{{ $proveedor->precio }}" 
                                               step="0.01" min="0" placeholder="0.00"
                                               id="precio-{{ $proveedor->id }}"
                                               {{ $requisicion->procesada == 1 ? 'disabled' : '' }}>
                                    </div>
                                    <div class="col-2">
                                        <span class="label-campo">Descuento</span>
                                        <input type="number" class="input-descuento" value="{{ $proveedor->descuento }}" 
                                               step="0.01" min="0" placeholder="0.00"
                                               id="descuento-{{ $proveedor->id }}"
                                               {{ $requisicion->procesada == 1 ? 'disabled' : '' }}>
                                    </div>
                                    <div class="col-2">
                                        <span class="label-campo">Fecha Entrega</span>
                                        <input type="date" class="input-fecha" 
                                               value="{{ $proveedor->fecha_entrega ? \Carbon\Carbon::parse($proveedor->fecha_entrega)->format('Y-m-d') : '' }}"
                                               id="fecha-{{ $proveedor->id }}"
                                               {{ $requisicion->procesada == 1 ? 'disabled' : '' }}>
                                    </div>
                                    <div class="col-2 text-end">
                                        <div class="h-100 d-flex align-items-center justify-content-end">
                                            <button class="btn-eliminar-proveedor" onclick="eliminarProveedor(this, '{{ $proveedor->id }}')" {{ $requisicion->procesada == 1 ? 'disabled' : '' }}>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Botones: Agregar Proveedor y Guardar Item -->
                        @if($requisicion->procesada != 1)
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
                            <button class="btn-agregar-proveedor" onclick="agregarFilaProveedor('{{ $item->id }}', this)">
                                <i class="fas fa-user-plus"></i> Agregar Proveedor
                            </button>
                            <button class="btn-guardar-item" onclick="guardarItem('{{ $item->id }}', this)">
                                <i class="fas fa-save"></i> Guardar
                            </button>
                        </div>
                        @endif
                    </div>
                    @endforeach

                    <!-- Botón Generar Compras -->
                    @if($requisicion->procesada != 1)
                    <div class="d-flex justify-content-end mt-4">
                        <button class="btn btn-success btn-lg" onclick="generarCompras()">
                            <i class="fas fa-file-invoice me-2"></i> Generar Compras
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    @include('footer')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function eliminarItem(id, clave, element) {
            Swal.fire({
                title: '¿Eliminar ' + clave + '?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
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
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Eliminado',
                                    text: 'El item fue eliminado correctamente',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al eliminar el item'
                            });
                        }
                    });
                }
            });
        }

        function agregarFilaProveedor(itemId, btn) {
            var container = $('#proveedores-' + itemId);
            var row = `
                <div class="proveedor-row" data-id="nuevo">
                    <div class="row align-items-center">
                        <div class="col-1">
                            <div class="d-flex-center h-100">
                                <input class="form-check-input" type="checkbox" id="check-nuevo-${Date.now()}">
                            </div>
                        </div>
                        <div class="col-3">
                            <span class="label-campo">Proveedor</span>
                            <select class="select-proveedor">
                                <option value="">Sin proveedor</option>
                                @foreach($proveedores as $proveedor)
                                <option value="{{ $proveedor->id }}">{{ $proveedor->clave }} - {{ $proveedor->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-2">
                            <span class="label-campo">Precio</span>
                            <input type="number" class="input-precio" step="0.01" min="0" placeholder="0.00">
                        </div>
                        <div class="col-2">
                            <span class="label-campo">Descuento</span>
                            <input type="number" class="input-descuento" step="0.01" min="0" placeholder="0.00">
                        </div>
                        <div class="col-2">
                            <span class="label-campo">Fecha Entrega</span>
                            <input type="date" class="input-fecha">
                        </div>
                        <div class="col-2 text-end">
                            <div class="h-100 d-flex align-items-center justify-content-end">
                                <button class="btn-eliminar-proveedor" onclick="eliminarFilaProveedor(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
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
            Swal.fire({
                title: '¿Eliminar este proveedor?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
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
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Eliminado',
                                    text: 'Proveedor eliminado correctamente',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error al eliminar proveedor'
                            });
                        }
                    });
                }
            });
        }

        $(document).on('change', '.form-check-input', function() {
            var container = $(this).closest('.fila-proveedores');
            
            if ($(this).is(':checked')) {
                container.find('.form-check-input').not(this).prop('checked', false);
            }
        });

        function guardarItem(itemId, btn) {
    var btnOriginal = $(btn);
    var btnHtml = btnOriginal.html();
    
    btnOriginal.addClass('guardando');
    btnOriginal.html('<i class="fas fa-spinner"></i> Guardando...');
    
    var cantidad = $('#cantidad-' + itemId).val();
    
    var proveedores = [];
    var container = $('#proveedores-' + itemId);
    var seleccionados = 0;
    var errores = [];
    var filasConError = 0;
    
    container.find('.proveedor-row').each(function() {
        var row = $(this);
        var proveedorId = row.find('.select-proveedor').val();
        var precio = row.find('.input-precio').val();
        var descuento = row.find('.input-descuento').val();
        var fechaEntrega = row.find('.input-fecha').val();
        var rowId = row.data('id');
        var seleccionado = row.find('.form-check-input').is(':checked') ? 1 : 0;
        
        // Validar cada fila independientemente
        var tieneError = false;
        
        if (!proveedorId || proveedorId === '') {
            if (seleccionado) {
                errores.push('La fila seleccionada no tiene proveedor');
                tieneError = true;
            }
        }
        
        if (seleccionado) {
            seleccionados++;
            
            if (!precio || precio <= 0) {
                errores.push('El proveedor seleccionado no tiene precio');
                tieneError = true;
            }
            if (!fechaEntrega) {
                errores.push('El proveedor seleccionado no tiene fecha de entrega');
                tieneError = true;
            }
        }
        
        if (tieneError) {
            filasConError++;
        }
        
        if (proveedorId) {
            proveedores.push({
                id: rowId,
                proveedor_id: proveedorId,
                precio: precio || 0,
                descuento: descuento || 0,
                seleccionado: seleccionado,
                fecha_entrega: fechaEntrega || null
            });
        }
    });

    // Validar que haya al menos un proveedor agregado (con select)
    var proveedoresConSelect = proveedores.filter(p => p.proveedor_id);
    if (proveedoresConSelect.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Sin proveedores',
            text: 'Agrega al menos un proveedor (selecciona uno en el select)',
            confirmButtonColor: '#0d6efd',
            confirmButtonText: 'Entendido'
        });
        btnOriginal.removeClass('guardando');
        btnOriginal.html(btnHtml);
        return;
    }

    // Validar que solo se pueda seleccionar un proveedor
    if (seleccionados > 1) {
        Swal.fire({
            icon: 'warning',
            title: 'Selección única',
            text: 'Solo puedes seleccionar un proveedor por producto',
            confirmButtonColor: '#0d6efd',
            confirmButtonText: 'Entendido'
        });
        btnOriginal.removeClass('guardando');
        btnOriginal.html(btnHtml);
        return;
    }

    // Validar que haya un proveedor seleccionado
    if (seleccionados === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Selecciona un proveedor',
            text: 'Debes seleccionar un proveedor para este producto',
            confirmButtonColor: '#0d6efd',
            confirmButtonText: 'Entendido'
        });
        btnOriginal.removeClass('guardando');
        btnOriginal.html(btnHtml);
        return;
    }

    // Validar errores del proveedor seleccionado
    if (errores.length > 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Datos incompletos',
            text: errores.join('<br>'),
            confirmButtonColor: '#0d6efd',
            confirmButtonText: 'Entendido'
        });
        btnOriginal.removeClass('guardando');
        btnOriginal.html(btnHtml);
        return;
    }

    // Validar que todas las filas tengan precio y fecha (aunque no estén seleccionadas)
    var todasLasFilas = container.find('.proveedor-row');
    var erroresGlobales = [];
    
    todasLasFilas.each(function() {
        var row = $(this);
        var proveedorId = row.find('.select-proveedor').val();
        var precio = row.find('.input-precio').val();
        var fechaEntrega = row.find('.input-fecha').val();
        
        if (proveedorId && proveedorId !== '') {
            if (!precio || precio <= 0) {
                erroresGlobales.push('La fila con proveedor ' + proveedorId + ' no tiene precio');
            }
            if (!fechaEntrega) {
                erroresGlobales.push('La fila con proveedor ' + proveedorId + ' no tiene fecha de entrega');
            }
        }
    });

    if (erroresGlobales.length > 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Datos incompletos en filas',
            text: erroresGlobales.join('<br>'),
            confirmButtonColor: '#0d6efd',
            confirmButtonText: 'Entendido'
        });
        btnOriginal.removeClass('guardando');
        btnOriginal.html(btnHtml);
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
                        row.find('.form-check-input').attr('id', 'check-' + prov.id);
                    }
                });
                
                btnOriginal.removeClass('guardando');
                btnOriginal.html('<i class="fas fa-check"></i> Guardado');
                setTimeout(function() {
                    btnOriginal.html(btnHtml);
                }, 1500);
            }
        },
        error: function() {
            btnOriginal.removeClass('guardando');
            btnOriginal.html(btnHtml);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al guardar el item'
            });
        }
    });
}

        function generarCompras() {
            Swal.fire({
                title: '¿Generar compras?',
                text: 'Se crearán las órdenes de compra según los proveedores seleccionados',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, generar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Generando compras...',
                        text: 'Por favor espera',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: '{{ url("requisiciones/generar-compras") }}/{{ $requisicion->id }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Compras generadas!',
                                    text: 'Se crearon ' + response.total_compras + ' órdenes de compra',
                                    confirmButtonColor: '#28a745'
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON?.message || 'Error al generar compras'
                            });
                        }
                    });
                }
            });
        }

        // Agregar evento para actualizar "Cant. a Comprar"
        $(document).on('change', '.input-cantidad, .input-inventario', function() {
            var row = $(this).closest('.row');
            var cantidad = parseFloat(row.find('.input-cantidad').val()) || 0;
            var inventario = parseFloat(row.find('.input-inventario').val()) || 0;
            var comprar = Math.max(0, cantidad - inventario);
            row.find('.input-comprar').val(comprar.toFixed(2));
        });
    </script>
</body>
</html>