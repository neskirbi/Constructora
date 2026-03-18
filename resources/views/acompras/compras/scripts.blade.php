{{-- resources/views/acompras/compras/scripts.blade.php --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variable global para el contador de productos
    window.productCount = {{ isset($detalles) ? count($detalles) : 1 }};
    
    // Inicializar Select2 para selects de productos
    window.initSelect2 = function(selector = '.producto-select') {
        $(selector).select2({
            placeholder: 'Seleccionar producto/servicio',
            allowClear: true,
            width: '100%',
            templateResult: formatOption,
            templateSelection: formatOption
        });
    }

    // Inicializar Select2 para proveedor
    window.initProveedorSelect2 = function() {
        $('#id_proveedor').select2({
            placeholder: 'Seleccionar proveedor',
            allowClear: true,
            width: '100%',
            templateResult: formatOptionProveedor,
            templateSelection: formatOptionProveedor
        });
    }

    function formatOption(option) {
        if (!option.id) return option.text;
        return $('<span>' + option.text + '</span>');
    }

    function formatOptionProveedor(option) {
        if (!option.id) return option.text;
        return $('<span>' + option.text + '</span>');
    }

    // Función para crear una nueva tarjeta de producto
    // Busca esta función
    window.crearTarjetaProducto = function(index) {
            let productosData = window.productosData || @json($productos ?? []);
            let options = '<option value="">Seleccionar</option>';
            
            productosData.forEach(function(producto) {
                options += `<option value="${producto.id}" 
                                data-clave="${producto.clave}"
                                data-descripcion="${producto.descripcion}"
                                data-unidad="${producto.unidades}"
                                data-precio="${producto.ult_costo}">
                                ${producto.clave}
                            </option>`;
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
                                        step="0.0000000000000001" 
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
                                <div class="col-md-1">
                                    <!-- Espacio vacío -->
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">% Descuento</label>
                                    <input type="number" 
                                        class="form-control form-control-sm descuento-porcentaje text-end" 
                                        name="productos[${index}][descuento_porcentaje]" 
                                        step="0.01" 
                                        min="0" 
                                        max="100"
                                        value="0"
                                        style="height: 38px;"
                                        noformat>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Monto Descuento</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="height: 38px;">$</span>
                                        <input type="number" 
                                            class="form-control descuento-monto text-end" 
                                            name="productos[${index}][descuento_monto]" 
                                            step="0.01" 
                                            min="0"
                                            value="0"
                                            style="height: 38px;">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Precio Unitario</label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="height: 38px;">$</span>
                                        <input type="number" 
                                            class="form-control precio-input text-end" 
                                            name="productos[${index}][precio]" 
                                            step="0.0000000000000001" 
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
        
    };

        

    // Función para agregar producto
    window.agregarProducto = function() {
        const container = document.getElementById('productosContainer');
        if (container) {
            $(container).append(crearTarjetaProducto(window.productCount));
            window.productCount++;
            
            // Reinicializar Select2 para los nuevos selects
            initSelect2('.producto-select');
            actualizarBotonesEliminar();
        }
    };

    
    // Función para actualizar subtotal de una fila (VERSIÓN CORREGIDA)
    window.actualizarSubtotalFila = function(card, campoModificado = null) {
        const cantidad = parseFloat($(card).find('.cantidad-input').val()) || 0;
        const precio = parseFloat($(card).find('.precio-input').val()) || 0;
        let descuentoPorcentaje = parseFloat($(card).find('.descuento-porcentaje').val()) || 0;
        let descuentoMonto = parseFloat($(card).find('.descuento-monto').val()) || 0;
        
        // Calcular subtotal sin descuento
        let subtotalSinDescuento = cantidad * precio;
        let subtotal = subtotalSinDescuento;
        
        // Aplicar lógica de descuentos según qué campo se modificó
        if (campoModificado === 'porcentaje' && descuentoPorcentaje > 0) {
            // Si se modificó el porcentaje, calcular el monto
            descuentoMonto = subtotalSinDescuento * (descuentoPorcentaje / 100);
            $(card).find('.descuento-monto').val(descuentoMonto.toFixed(2));
            subtotal = subtotalSinDescuento - descuentoMonto;
        } 
        else if (campoModificado === 'monto' && descuentoMonto > 0) {
            // Si se modificó el monto, calcular el porcentaje
            if (subtotalSinDescuento > 0) {
                descuentoPorcentaje = (descuentoMonto / subtotalSinDescuento) * 100;
                $(card).find('.descuento-porcentaje').val(descuentoPorcentaje.toFixed(2));
            }
            subtotal = subtotalSinDescuento - descuentoMonto;
        }
        else if (descuentoMonto > 0) {
            // Si hay monto pero no se especificó campo, usar el monto
            subtotal = subtotalSinDescuento - descuentoMonto;
        }
        else if (descuentoPorcentaje > 0) {
            // Si hay porcentaje pero no se especificó campo, usar el porcentaje
            descuentoMonto = subtotalSinDescuento * (descuentoPorcentaje / 100);
            $(card).find('.descuento-monto').val(descuentoMonto.toFixed(2));
            subtotal = subtotalSinDescuento - descuentoMonto;
        }
        
        $(card).find('.subtotal-text').val('$' + subtotal.toFixed(2));
        $(card).closest('.producto-card').attr('data-subtotal', subtotal);
        calcularTotalesGlobales();
    };

    // Función para calcular todos los totales
    window.calcularTotalesGlobales = function() {
        let subtotalGlobal = 0;
        $('.producto-card').each(function() {
            subtotalGlobal += parseFloat($(this).attr('data-subtotal') || 0);
        });

        const ivaPorcentaje = parseFloat($('#iva').val()) || 0;
        const ivaCalculado = subtotalGlobal * (ivaPorcentaje / 100);
        const totalGlobal = subtotalGlobal + ivaCalculado;

        $('#subtotalGlobal').text('$' + subtotalGlobal.toFixed(2));
        $('#ivaCalculado').text('$' + ivaCalculado.toFixed(2));
        $('#totalGlobal').text('$' + totalGlobal.toFixed(2));
        
        $('#costo_operado_hidden').val(subtotalGlobal.toFixed(2));
        $('#total_hidden').val(totalGlobal.toFixed(2));
    };

    // Función para actualizar visibilidad de botones eliminar
    window.actualizarBotonesEliminar = function() {
        const cards = $('.producto-card');
        cards.each(function() {
            const btnRemove = $(this).find('.btn-remove-row');
            if (btnRemove.length) {
                btnRemove.css('display', cards.length > 1 ? 'inline-flex' : 'none');
            }
        });
    };

    // Evento cuando se selecciona un producto
    $(document).on('select2:select', '.producto-select', function(e) {
        const card = $(this).closest('.producto-card');
        const selectedOption = e.params.data.element;
        
        if (selectedOption) {
            const descripcion = selectedOption.getAttribute('data-descripcion');
            const unidad = selectedOption.getAttribute('data-unidad');
            const precio = selectedOption.getAttribute('data-precio');
            
            card.find('.descripcion-input').val(descripcion || '');
            card.find('.unidad-input').val(unidad || '');
            card.find('.precio-input').val(precio || 0);

            
            
            actualizarSubtotalFila(card);
        }
    });

    // Eventos para cambios en cantidad y precio
    $(document).on('input', '.cantidad-input, .precio-input', function() {
        const card = $(this).closest('.producto-card');
        actualizarSubtotalFila(card);
    });

    // Evento para cambio en porcentaje de IVA
    $(document).on('input', '#iva', function() {
        calcularTotalesGlobales();
    });

    // Eliminar fila
    $(document).on('click', '.btn-remove-row', function() {
        if ($('.producto-card').length > 1) {
            $(this).closest('.producto-card-wrapper').remove();
            calcularTotalesGlobales();
            actualizarBotonesEliminar();
        }
    });

    // Inicializar
    window.productosData = @json($productos ?? []);
    initSelect2();
    initProveedorSelect2();
    
    // Calcular subtotales iniciales para cada tarjeta
    $('.producto-card').each(function() {
        const cantidad = parseFloat($(this).find('.cantidad-input').val()) || 0;
        const precio = parseFloat($(this).find('.precio-input').val()) || 0;
        const subtotal = cantidad * precio;
        $(this).attr('data-subtotal', subtotal);
    });
    
    calcularTotalesGlobales();
    actualizarBotonesEliminar();

    // Evento para cambio en porcentaje de descuento
    $(document).on('input', '.descuento-porcentaje', function() {
        const card = $(this).closest('.producto-card');
        actualizarSubtotalFila(card, 'porcentaje');
    });

    // Evento para cambio en monto de descuento
    $(document).on('input', '.descuento-monto', function() {
        const card = $(this).closest('.producto-card');
        actualizarSubtotalFila(card, 'monto');
    });
});



</script>