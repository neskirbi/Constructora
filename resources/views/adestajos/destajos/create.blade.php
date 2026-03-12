<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Nuevo Destajo</title>
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
        /* Altura específica para la clave de producto */
        .clave-producto-select .select2-selection--single {
            height: 38px !important;
        }
        /* Estilos para los inputs */
        .form-control-sm, .input-group-sm .form-control {
            height: 38px;
        }
        .input-group-text {
            height: 38px;
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
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-plus-circle me-2 text-success"></i>
                                    Nuevo Destajo
                                </h5>
                                <a href="{{ route('destajos.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-arrow-left me-1"></i> Regresar
                                </a>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <form action="{{ route('destajos.store') }}" method="POST" id="destajoForm">
                                @csrf
                                
                                <!-- Información General -->
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="consecutivo" class="form-label required-label">Consecutivo</label>
                                            <input type="number" 
                                                   class="form-control form-control-sm @error('consecutivo') is-invalid @enderror" 
                                                   id="consecutivo" 
                                                   name="consecutivo" 
                                                   value="{{ old('consecutivo', $siguienteConsecutivo) }}"
                                                   min="1"
                                                   required
                                                   noformat
                                                   style="height: 38px;">
                                            @error('consecutivo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <!-- Campo Referencia después de consecutivo -->
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="referencia" class="form-label required-label">Referencia</label>
                                            <input type="text" 
                                                   class="form-control form-control-sm @error('referencia') is-invalid @enderror" 
                                                   id="referencia" 
                                                   name="referencia" 
                                                   value="{{ old('referencia') }}"
                                                   maxlength="1500"
                                                   placeholder="Folio, contrato, etc."
                                                   required
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
                                                    <option value="{{ $contrato->id }}" {{ old('id_contrato') == $contrato->id ? 'selected' : '' }}>
                                                        {{ $contrato->consecutivo }} - {{ $contrato->obra ?? '' }}
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
                                                    <option value="{{ $proveedor->id }}" {{ old('id_proveedor') == $proveedor->id ? 'selected' : '' }}>
                                                        {{ $proveedor->clave }}
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
                                            </tr>
                                        </thead>
                                        <tbody id="productosBody">
                                            <tr class="producto-row">
                                                <td>
                                                    <select class="form-select" 
                                                            name="productos[0][id_producto]" 
                                                            data-index="0"
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
                                                    <input type="text" 
                                                           class="form-control form-control-sm descripcion-input" 
                                                           readonly
                                                           placeholder="Descripción"
                                                           style="height: 38px;">
                                                </td>
                                                <td>
                                                    <input type="text" 
                                                           class="form-control form-control-sm unidad-input" 
                                                           readonly
                                                           placeholder="Unidad"
                                                           style="height: 38px;">
                                                </td>
                                                <td>
                                                    <input type="number" 
                                                           class="form-control form-control-sm cantidad-input" 
                                                           name="productos[0][cantidad]" 
                                                           step="0.01" 
                                                           min="0.01"
                                                           value="1"
                                                           noformat
                                                           style="height: 38px;"
                                                           required>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text" style="height: 38px;">$</span>
                                                        <input type="number" 
                                                               class="form-control precio-input" 
                                                               name="productos[0][precio]" 
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
                                                               readonly
                                                               style="height: 38px; background-color: #f8f9fa;">
                                                    </div>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <i class="fas fa-trash-alt btn-remove-row" style="display: none; font-size: 1.2rem;"></i>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="7">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" id="agregarProducto">
                                                        <i class="fas fa-plus me-1"></i> Agregar Producto/Servicio
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#nuevoProductoModal">
                                                        <i class="fas fa-plus-circle me-1"></i> Nuevo Producto
                                                    </button>
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
                                                    <p class="mb-0" id="subtotalGlobal">$0.00</p>
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
                                                               value="{{ old('iva', 16) }}"
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
                                                    <p class="mb-0" id="ivaCalculado">$0.00</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <p class="mb-0 fw-bold fs-6">TOTAL:</p>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <p class="mb-0 fw-bold fs-6" id="totalGlobal">$0.00</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Campos ocultos para enviar los totales calculados -->
                                <input type="hidden" name="costo_operado" id="costo_operado_hidden">
                                <input type="hidden" name="total" id="total_hidden">

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('destajos.index') }}" class="btn btn-secondary btn-sm">
                                                Cancelar
                                            </a>
                                            <button type="submit" class="btn btn-success btn-sm" id="btnSubmit">
                                                <i class="fas fa-save me-1"></i> Guardar Destajo
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
    let productCount = 1;
    let productosData = @json($productos);
    
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
            subtotalGlobal += parseFloat($(this).attr('data-subtotal') || 0);
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
        
        // Mostrar botones de eliminar en todas las filas excepto la primera si solo hay una
        actualizarBotonesEliminar();
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

    

    // Inicializar primera fila
    actualizarBotonesEliminar();
    calcularTotalesGlobales();
});
</script>
@include('adestajos.destajos.footer')
</body>
</html>

<!-- Al seleccionar un servicio o producto aqui se mete el el costo o lo que se va a pagar , no se captura en el catalogo 
 poner el agregar nuevo producto o servicio dentro de la misma pantalla donde se agregan 
 pner el frente 

 mejorar la vista de la clave del servicio
-->