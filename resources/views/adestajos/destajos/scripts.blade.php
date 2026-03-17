<script>
// ==============================================
// FUNCIONES COMUNES PARA CREATE Y SHOW
// ==============================================

// Inicializar Select2 para todos los selects
function initSelect2() {
    // Para selects de productos
    $('.producto-select').select2({
        placeholder: 'Seleccionar producto/servicio',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return "No se encontraron resultados";
            },
            searching: function() {
                return "Buscando...";
            }
        }
    });
    
    // Para select de proveedor
    $('#id_proveedor').select2({
        placeholder: 'Seleccionar proveedor',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return "No se encontraron resultados";
            },
            searching: function() {
                return "Buscando...";
            }
        }
    });
}

// Función para formatear moneda con separadores de miles
function formatearMoneda(valor) {
    if (isNaN(valor) || valor === null || valor === undefined) {
        valor = 0;
    }
    return '$' + parseFloat(valor).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

// Función para calcular todos los totales
function calcularTotalesGlobales() {
    let subtotalGlobal = 0;
    $('.producto-card').each(function() {
        subtotalGlobal += parseFloat($(this).attr('data-subtotal') || 0);
    });

    const ivaPorcentaje = parseFloat($('#iva').val()) || 0;
    const ivaCalculado = subtotalGlobal * (ivaPorcentaje / 100);
    const totalGlobal = subtotalGlobal + ivaCalculado;

    $('#subtotalGlobal').text(formatearMoneda(subtotalGlobal));
    $('#ivaCalculado').text(formatearMoneda(ivaCalculado));
    $('#totalGlobal').text(formatearMoneda(totalGlobal));
    
    $('#costo_operado_hidden').val(subtotalGlobal.toFixed(2));
    $('#total_hidden').val(totalGlobal.toFixed(2));
}

// Función para crear una nueva tarjeta de producto (para CREATE)
function crearTarjetaProducto(index, productosData) {
    let options = '<option value="">Seleccionar</option>';
    productosData.forEach(function(producto) {
        options += `<option value="${producto.id}" 
                        data-clave="${producto.clave}"
                        data-descripcion="${producto.descripcion}"
                        data-unidad="${producto.unidades}"
                        data-precio="${producto.ult_costo}">
                        ${producto.clave}
                    </option>`;
                    console.log(producto.clave);
    });

    return `
        <div class="producto-card-wrapper mb-3" data-index="${index}">
            <div class="card producto-card">
                <div class="card-header">
                    <h6 class="mb-0 float-start"><i class="fas fa-box me-2"></i>Producto #${index + 1}</h6>
                    <div class="card-tools float-end">
                        <button type="button" class="btn-remove-row">
                            <i class="fas fa-trash-alt me-1"></i>Eliminar
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Clave</label>
                            <select class="form-select form-select-sm producto-select" 
                                    name="productos[${index}][id_producto]" 
                                    style="width: 100%; height: 38px;"
                                    required>
                                ${options}
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Descripción</label>
                            <input type="text" 
                                   class="form-control form-control-sm descripcion-input" 
                                   readonly
                                   placeholder="Descripción"
                                   style="height: 38px; background-color: #f8f9fa;">
                        </div>
                        
                        <div class="col-md-2 mb-2">
                            <label class="form-label">Unidad</label>
                            <input type="text" 
                                   class="form-control form-control-sm unidad-input" 
                                   readonly
                                   placeholder="Unidad"
                                   style="height: 38px; background-color: #f8f9fa;">
                        </div>
                        
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Cantidad</label>
                            <input type="number" 
                                   class="form-control form-control-sm cantidad-input text-end" 
                                   name="productos[${index}][cantidad]" 
                                   step="0.01" 
                                   min="0.01"
                                   value="1"
                                   style="height: 38px;"
                                   required>
                        </div>
                    </div>
                    
                    <div class="row mt-2">
                        <div class="col-md-3">
                            <!-- Espacio vacío -->
                        </div>
                        <div class="col-md-3">
                            <!-- Espacio vacío -->
                        </div>
                        <div class="col-md-3">
                            <!-- Espacio vacío -->
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Precio Unitario</label>
                            <div class="input-group">
                                <span class="input-group-text" style="height: 38px;">$</span>
                                <input type="number" 
                                       class="form-control precio-input text-end" 
                                       name="productos[${index}][precio]" 
                                       step="0.01" 
                                       style="height: 38px;"
                                       required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-2">
                        <div class="col-md-3">
                            <!-- Espacio vacío -->
                        </div>
                        <div class="col-md-3">
                            <!-- Espacio vacío -->
                        </div>
                        <div class="col-md-3">
                            <!-- Espacio vacío -->
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Subtotal</label>
                            <div class="input-group">
                                <span class="input-group-text" style="height: 38px;">$</span>
                                <input type="text" 
                                       class="form-control subtotal-text text-end" 
                                       readonly
                                       style="height: 38px; background-color: #f8f9fa;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Evento cuando se selecciona un producto
$(document).on('change', '.producto-select', function() {
    const card = $(this).closest('.producto-card');
    const selectedOption = $(this).find('option:selected');
    
    if (selectedOption.val()) {
        const descripcion = selectedOption.data('descripcion');
        const unidad = selectedOption.data('unidad');
        const precio = selectedOption.data('precio');
        
        card.find('.descripcion-input').val(descripcion || '');
        card.find('.unidad-input').val(unidad || '');
        card.find('.precio-input').val(precio || 0);
        
        const cantidad = parseFloat(card.find('.cantidad-input').val()) || 0;
        const subtotal = cantidad * (precio || 0);
        
        card.find('.subtotal-text').val(formatearMoneda(subtotal));
        card.closest('.producto-card').attr('data-subtotal', subtotal);
        calcularTotalesGlobales();
    } else {
        card.find('.descripcion-input').val('');
        card.find('.unidad-input').val('');
        card.find('.precio-input').val('');
        card.find('.subtotal-text').val(formatearMoneda(0));
        card.closest('.producto-card').attr('data-subtotal', 0);
        calcularTotalesGlobales();
    }
});

// Eventos para cambios en cantidad y precio
$(document).on('input', '.cantidad-input, .precio-input', function() {
    const card = $(this).closest('.producto-card');
    const cantidad = parseFloat(card.find('.cantidad-input').val()) || 0;
    const precio = parseFloat(card.find('.precio-input').val()) || 0;
    const subtotal = cantidad * precio;
    
    card.find('.subtotal-text').val(formatearMoneda(subtotal));
    card.closest('.producto-card').attr('data-subtotal', subtotal);
    calcularTotalesGlobales();
});

// Evento para cambio en porcentaje de IVA
$(document).on('input', '#iva', function() {
    calcularTotalesGlobales();
});
</script>