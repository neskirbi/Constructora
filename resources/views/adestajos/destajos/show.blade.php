<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Editar Destajo</title>
    <style>
        .required-label::after {
            content: " *";
            color: #dc3545;
        }
        .form-card {
            max-width: 1200px;
            margin: 0 auto;
        }
        .input-group-text {
            background-color: #f8f9fa;
        }
        .table-productos {
            font-size: 0.9rem;
        }
        .table-productos th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .btn-remove-row {
            color: #dc3545;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-remove-row:hover {
            color: #a71d2a;
            transform: scale(1.1);
        }
        .total-card {
            background-color: #e9ecef;
            border-radius: 8px;
            padding: 15px;
        }
        /* Ajustar altura del select2 */
        .select2-container--default .select2-selection--single {
            height: 38px !important;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px !important;
            padding-left: 12px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
        }
        /* Estilos para los inputs */
        .form-control-sm, .input-group-sm .form-control {
            height: 38px;
        }
        .input-group-text {
            height: 38px;
        }
        .readonly-field {
            background-color: #f8f9fa;
            cursor: not-allowed;
        }
        .destajo-header {
            padding: 15px 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .destajo-consecutivo {
            font-size: 1.8rem;
            font-weight: 700;
            color: #0d6efd;
            margin: 0;
        }
        .destajo-estado {
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
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
    <div class="main-container">
        @include('toast.toasts')
        @include('adestajos.sidebar')
        
        <main class="main-content" id="mainContent">
            @include('adestajos.navbar')

            <div class="content-area">
                <div class="container-fluid py-4">
                    <div class="card shadow-sm form-card">
                        <div class="destajo-header">
                           
                             <h5 class="mb-0">
                                    <i class="fas fa-plus-circle me-2 text-success"></i>
                                    Editar Destajo
                                </h5>
                            
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
                        </div>
                        
                        <div class="card-body">
                            <form action="{{ route('destajos.update', $destajo->id) }}" method="POST" id="destajoForm">
                                @csrf
                                @method('PUT')
                                
                                <!-- Información General -->
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="consecutivo" class="form-label required-label">Consecutivo</label>
                                            <input type="number" 
                                                   class="form-control form-control-sm readonly-field @error('consecutivo') is-invalid @enderror" 
                                                   id="consecutivo" 
                                                   name="consecutivo" 
                                                   value="{{ old('consecutivo', $destajo->consecutivo) }}"
                                                   min="1"
                                                   required
                                                   readonly
                                                   noformat
                                                   style="height: 38px;">
                                            @error('consecutivo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="referencia" class="form-label">Referencia</label>
                                            <input type="text" 
                                                   class="form-control form-control-sm @error('referencia') is-invalid @enderror" 
                                                   id="referencia" 
                                                   name="referencia" 
                                                   value="{{ old('referencia', $destajo->referencia) }}"
                                                   maxlength="1500"
                                                   placeholder="Folio, contrato, etc."
                                                   style="height: 38px;">
                                            @error('referencia')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="id_contrato" class="form-label required-label">Contrato</label>
                                            <select class="form-select form-select-sm @error('id_contrato') is-invalid @enderror" 
                                                    id="id_contrato" 
                                                    name="id_contrato"
                                                    required
                                                    style="height: 38px;">
                                                <option value="">Seleccione un contrato</option>
                                                @foreach($contratos as $contrato)
                                                    <option value="{{ $contrato->id }}" {{ old('id_contrato', $destajo->id_contrato) == $contrato->id ? 'selected' : '' }}>
                                                        {{ $contrato->contrato_no }} - {{ $contrato->obra ?? '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('id_contrato')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="id_proveedor" class="form-label required-label">Proveedor</label>
                                            <select class="form-select form-select-sm @error('id_proveedor') is-invalid @enderror" 
                                                    id="id_proveedor" 
                                                    name="id_proveedor"
                                                    required
                                                    style="height: 38px;">
                                                <option value="">Seleccione un proveedor</option>
                                                @foreach($proveedores as $proveedor)
                                                    <option value="{{ $proveedor->id }}" {{ old('id_proveedor', $destajo->id_proveedor) == $proveedor->id ? 'selected' : '' }}>
                                                        {{ $proveedor->clave }} - {{ $proveedor->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('id_proveedor')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Sección de Productos/Servicios -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <h6 class="fw-bold mb-3">
                                            <i class="fas fa-boxes me-2"></i>
                                            Productos / Servicios
                                        </h6>
                                    </div>
                                </div>

                                <div class="table-responsive mb-3">
                                    <table class="table table-bordered table-productos" id="productosTable">
                                        <thead>
                                            <tr>
                                                <th width="15%">Clave</th>
                                                <th width="30%">Descripción</th>
                                                <th width="10%">Unidad</th>
                                                <th width="10%">Cantidad</th>
                                                <th width="12%">Precio Unitario</th>
                                                <th width="12%">Subtotal</th>
                                                <th width="11%">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="productosBody">
                                            @foreach($detalles as $index => $detalle)
                                            <tr class="producto-row">
                                                <td>
                                                    <select class="form-select form-select-sm producto-select" 
                                                            name="productos[{{ $index }}][id_producto]" 
                                                            data-index="{{ $index }}"
                                                            style="width: 100%; height: 38px;"
                                                            required>
                                                        <option value="">Seleccionar</option>
                                                        @foreach($productos as $producto)
                                                            <option value="{{ $producto->id }}" 
                                                                    data-clave="{{ $producto->clave }}"
                                                                    data-descripcion="{{ $producto->descripcion }}"
                                                                    data-unidad="{{ $producto->unidades }}"
                                                                    data-precio="{{ $producto->ult_costo }}"
                                                                    {{ $detalle->id_productoservicio == $producto->id ? 'selected' : '' }}>
                                                                {{ $producto->clave }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" 
                                                           class="form-control form-control-sm descripcion-input" 
                                                           value="{{ $detalle->descripcion }}"
                                                           readonly
                                                           placeholder="Descripción"
                                                           style="height: 38px;">
                                                </td>
                                                <td>
                                                    <input type="text" 
                                                           class="form-control form-control-sm unidad-input" 
                                                           value="{{ $detalle->unidades }}"
                                                           readonly
                                                           placeholder="Unidad"
                                                           style="height: 38px;">
                                                </td>
                                                <td>
                                                    <input type="number" 
                                                           class="form-control form-control-sm cantidad-input" 
                                                           name="productos[{{ $index }}][cantidad]" 
                                                           value="{{ $detalle->cantidad }}"
                                                           step="0.01" 
                                                           min="0.01"
                                                           noformat
                                                           style="height: 38px;"
                                                           required>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text" style="height: 38px;">$</span>
                                                        <input type="number" 
                                                               class="form-control precio-input" 
                                                               name="productos[{{ $index }}][precio]" 
                                                               value="{{ $detalle->ult_costo }}"
                                                               step="0.01"
                                                               noformat
                                                               style="height: 38px;"
                                                               required>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text" style="height: 38px;">$</span>
                                                        <input type="text" 
                                                               class="form-control subtotal-text" 
                                                               value="${{ number_format($detalle->cantidad * $detalle->ult_costo, 2) }}"
                                                               readonly
                                                               style="height: 38px; background-color: #f8f9fa;">
                                                    </div>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <i class="fas fa-trash-alt btn-remove-row" style="{{ $loop->first && count($detalles) == 1 ? 'display: none;' : '' }} font-size: 1.2rem;"></i>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="7">
                                                    <div class="d-flex gap-2">
                                                        <button type="button" class="btn btn-sm btn-outline-primary" id="agregarProducto">
                                                            <i class="fas fa-plus me-1"></i> Agregar Producto/Servicio
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#nuevoProductoModal">
                                                            <i class="fas fa-plus-circle me-1"></i> Nuevo Producto
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- Resumen de Totales -->
                                <div class="row justify-content-end mb-4">
                                    <div class="col-md-5">
                                        <div class="total-card">
                                            <div class="row mb-2">
                                                <div class="col-6">
                                                    <p class="mb-0 fw-bold">Subtotal:</p>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <p class="mb-0" id="subtotalGlobal">${{ number_format($destajo->costo_operado, 2) }}</p>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-6">
                                                    <label for="iva" class="fw-bold mb-0">IVA (%):</label>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" 
                                                               class="form-control form-control-sm text-end" 
                                                               id="iva" 
                                                               name="iva" 
                                                               value="{{ old('iva', $destajo->iva ? round(($destajo->iva / $destajo->costo_operado) * 100, 2) : 16) }}"
                                                               step="0.01"
                                                               min="0"
                                                               noformat
                                                               style="height: 38px;">
                                                        <span class="input-group-text" style="height: 38px;">%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-6">
                                                    <p class="mb-0">IVA Calculado:</p>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <p class="mb-0" id="ivaCalculado">${{ number_format($destajo->iva, 2) }}</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <p class="mb-0 fw-bold fs-6">TOTAL:</p>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <p class="mb-0 fw-bold fs-6" id="totalGlobal">${{ number_format($destajo->total, 2) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Campos ocultos -->
                                <input type="hidden" name="costo_operado" id="costo_operado_hidden" value="{{ $destajo->costo_operado }}">
                                <input type="hidden" name="total" id="total_hidden" value="{{ $destajo->total }}">

                               

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('destajos.index') }}" class="btn btn-secondary btn-sm">
                                                Cancelar
                                            </a>
                                            <button type="submit" class="btn btn-warning btn-sm">
                                                <i class="fas fa-save me-1"></i> Actualizar Destajo
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal para agregar nuevo producto/servicio -->
    <div class="modal fade" id="nuevoProductoModal" tabindex="-1" aria-labelledby="nuevoProductoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="nuevoProductoModalLabel">
                        <i class="fas fa-plus-circle text-success me-2"></i>
                        Nuevo Producto/Servicio
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="nuevoProductoForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nuevo_clave" class="form-label required-label">Clave</label>
                            <input type="text" 
                                   class="form-control form-control-sm" 
                                   id="nuevo_clave" 
                                   name="clave" 
                                   maxlength="32"
                                   required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="nuevo_descripcion" class="form-label required-label">Descripción</label>
                            <textarea class="form-control form-control-sm" 
                                      id="nuevo_descripcion" 
                                      name="descripcion" 
                                      rows="2"
                                      required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="nuevo_unidades" class="form-label required-label">Unidades</label>
                            <input type="text" 
                                   class="form-control form-control-sm" 
                                   id="nuevo_unidades" 
                                   name="unidades" 
                                   maxlength="10"
                                   placeholder="PZA, M2, etc."
                                   required>
                        </div>
                        
                        <div class="alert alert-danger d-none" id="productoError"></div>
                        <div class="alert alert-success d-none" id="productoSuccess"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-success btn-sm" id="guardarProductoBtn">
                            <i class="fas fa-save me-1"></i> Guardar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('footer')
    
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    let productCount = {{ count($detalles) }};
    let productosData = @json($productos);
    
    // Inicializar los data-subtotal de las filas existentes
    $('.producto-row').each(function() {
        const cantidad = parseFloat($(this).find('.cantidad-input').val()) || 0;
        const precio = parseFloat($(this).find('.precio-input').val()) || 0;
        const subtotal = cantidad * precio;
        $(this).attr('data-subtotal', subtotal);
    });
    
    calcularTotalesGlobales();

    // Función para actualizar subtotal de una fila
    function actualizarSubtotalFila(row) {
        const cantidad = parseFloat($(row).find('.cantidad-input').val()) || 0;
        const precio = parseFloat($(row).find('.precio-input').val()) || 0;
        const subtotal = cantidad * precio;
        $(row).find('.subtotal-text').val('$' + subtotal.toFixed(2));
        $(row).attr('data-subtotal', subtotal);
        calcularTotalesGlobales();
    }

    // Función para calcular todos los totales
    function calcularTotalesGlobales() {
        let subtotalGlobal = 0;
        $('.producto-row').each(function() {
            const subtotal = parseFloat($(this).attr('data-subtotal')) || 0;
            subtotalGlobal += subtotal;
        });

        const ivaPorcentaje = parseFloat($('#iva').val()) || 0;
        const ivaCalculado = subtotalGlobal * (ivaPorcentaje / 100);
        const totalGlobal = subtotalGlobal + ivaCalculado;

        $('#subtotalGlobal').text('$' + subtotalGlobal.toFixed(2));
        $('#ivaCalculado').text('$' + ivaCalculado.toFixed(2));
        $('#totalGlobal').text('$' + totalGlobal.toFixed(2));
        
        // Actualizar campos ocultos
        $('#costo_operado_hidden').val(subtotalGlobal.toFixed(2));
        $('#total_hidden').val(totalGlobal.toFixed(2));
    }

    // Evento cuando se selecciona un producto
    $(document).on('change', 'select[name^="productos"][name$="[id_producto]"]', function() {
        const row = $(this).closest('tr');
        const selectedOption = $(this).find('option:selected');
        
        if (selectedOption.val()) {
            const descripcion = selectedOption.data('descripcion');
            const unidad = selectedOption.data('unidad');
            const precio = selectedOption.data('precio');
            
            row.find('.descripcion-input').val(descripcion || '');
            row.find('.unidad-input').val(unidad || '');
            row.find('.precio-input').val(precio || 0);
            
            actualizarSubtotalFila(row);
        } else {
            // Si selecciona la opción vacía, limpiar campos
            row.find('.descripcion-input').val('');
            row.find('.unidad-input').val('');
            row.find('.precio-input').val('');
            row.find('.subtotal-text').val('$0.00');
            row.attr('data-subtotal', 0);
            calcularTotalesGlobales();
        }
    });

    // Eventos para cambios en cantidad y precio
    $(document).on('input', '.cantidad-input, .precio-input', function() {
        const row = $(this).closest('tr');
        actualizarSubtotalFila(row);
    });

    // Evento para cambio en porcentaje de IVA
    $('#iva').on('input', calcularTotalesGlobales);

    // Agregar nueva fila
    $('#agregarProducto').on('click', function() {
        const tbody = $('#productosBody');
        const index = productCount;
        
        const newRow = $('<tr>').addClass('producto-row');
        
        newRow.html(`
            <td>
                <select class="form-select form-select-sm" 
                        name="productos[${index}][id_producto]" 
                        data-index="${index}"
                        style="width: 100%; height: 38px;"
                        required>
                    <option value="">Seleccionar</option>
                    @foreach($productos as $producto)
                        <option value="{{ $producto->id }}" 
                                data-clave="{{ $producto->clave }}"
                                data-descripcion="{{ $producto->descripcion }}"
                                data-unidad="{{ $producto->unidades }}"
                                data-precio="{{ $producto->ult_costo }}">
                            {{ $producto->clave }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="text" class="form-control form-control-sm descripcion-input" readonly placeholder="Descripción" style="height: 38px;">
            </td>
            <td>
                <input type="text" class="form-control form-control-sm unidad-input" readonly placeholder="Unidad" style="height: 38px;">
            </td>
            <td>
                <input type="number" class="form-control form-control-sm cantidad-input" name="productos[${index}][cantidad]" step="0.01" min="0.01" value="1" noformat style="height: 38px;" required>
            </td>
            <td>
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="height: 38px;">$</span>
                    <input type="number" class="form-control precio-input" name="productos[${index}][precio]" step="0.01" noformat style="height: 38px;" required>
                </div>
            </td>
            <td>
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="height: 38px;">$</span>
                    <input type="text" class="form-control subtotal-text" readonly style="height: 38px; background-color: #f8f9fa;">
                </div>
            </td>
            <td class="text-center align-middle">
                <i class="fas fa-trash-alt btn-remove-row" style="font-size: 1.2rem;"></i>
            </td>
        `);
        
        tbody.append(newRow);
        productCount++;
        
        // Inicializar la nueva fila con subtotal en 0
        newRow.attr('data-subtotal', 0);
        
        // Mostrar botones de eliminar
        actualizarBotonesEliminar();
        calcularTotalesGlobales();
    });

    // Eliminar fila
    $(document).on('click', '.btn-remove-row', function() {
        if ($('.producto-row').length > 1) {
            $(this).closest('tr').remove();
            calcularTotalesGlobales();
            actualizarBotonesEliminar();
        }
    });

    // Función para actualizar visibilidad de botones eliminar
    function actualizarBotonesEliminar() {
        const rows = $('.producto-row');
        rows.each(function(index) {
            const btnRemove = $(this).find('.btn-remove-row');
            if (btnRemove.length) {
                btnRemove.css('display', rows.length > 1 ? 'inline-block' : 'none');
            }
        });
    }

    // Validación del formulario
    $('#destajoForm').on('submit', function(event) {
        let hasProducts = false;
        
        $('.producto-row').each(function() {
            const select = $(this).find('select[name^="productos"][name$="[id_producto]"]');
            if (select.val()) {
                hasProducts = true;
            }
        });
        
        if (!hasProducts) {
            event.preventDefault();
            alert('Debe agregar al menos un producto o servicio');
            return false;
        }
        
        if (!this.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        $(this).addClass('was-validated');
    });

    // Manejar envío del formulario para nuevo producto
    $('#nuevoProductoForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const formData = new FormData(this);
        const submitBtn = $('#guardarProductoBtn');
        const errorAlert = $('#productoError');
        const successAlert = $('#productoSuccess');
        
        // Ocultar alerts previos
        errorAlert.addClass('d-none');
        successAlert.addClass('d-none');
        
        // Deshabilitar botón
        submitBtn.prop('disabled', true);
        submitBtn.html('<span class="spinner-border spinner-border-sm me-1"></span> Guardando...');
        
        // Enviar petición AJAX
        $.ajax({
            url: '{{ route("NuevoPS") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(data) {
                if (data.success) {
                    // Mostrar mensaje de éxito
                    successAlert.text(data.message).removeClass('d-none');
                    
                    // Crear nueva opción
                    const newOption = $('<option>', {
                        value: data.producto.id,
                        text: data.producto.clave + ' - ' + data.producto.descripcion.substring(0, 30),
                        'data-clave': data.producto.clave,
                        'data-descripcion': data.producto.descripcion,
                        'data-unidad': data.producto.unidades,
                        'data-precio': data.producto.ult_costo
                    });
                    
                    // Agregar a todos los selects
                    $('select[name^="productos"][name$="[id_producto]"]').append(newOption.clone());
                    
                    // Limpiar formulario
                    form[0].reset();
                    
                    // Cerrar modal después de 1 segundo
                    setTimeout(function() {
                        $('#nuevoProductoModal').modal('hide');
                        successAlert.addClass('d-none');
                    }, 1000);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    // Errores de validación
                    const errors = xhr.responseJSON.errors;
                    let errorHtml = '';
                    $.each(errors, function(key, value) {
                        errorHtml += value.join('<br>') + '<br>';
                    });
                    errorAlert.html(errorHtml).removeClass('d-none');
                } else {
                    // Otros errores
                    errorAlert.text(xhr.responseJSON.message || 'Error al guardar el producto').removeClass('d-none');
                }
            },
            complete: function() {
                // Restaurar botón
                submitBtn.prop('disabled', false);
                submitBtn.html('<i class="fas fa-save me-1"></i> Guardar Producto');
            }
        });
    });

    // Limpiar alerts cuando se cierra el modal
    $('#nuevoProductoModal').on('hidden.bs.modal', function() {
        $('#productoError').addClass('d-none');
        $('#productoSuccess').addClass('d-none');
        $('#nuevoProductoForm')[0].reset();
    });

    // Inicializar primera fila
    actualizarBotonesEliminar();
});
</script>
</body>
</html>