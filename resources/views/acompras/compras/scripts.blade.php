{{-- resources/views/acompras/compras/scripts.blade.php --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
   
// Variable global para el contador de productos
    window.productCount = {{ isset($detalles) ? count($detalles) : 1 }};


    
    // VALIDACIÓN DEL FORMULARIO (una sola vez)
    $('#compraForm').on('submit', function(event) {
        let hasProducts = false;
        
        // Buscar productos agregados (los que tienen ID)
        $('.producto-id').each(function() {
            if ($(this).val() && $(this).val() !== '') {
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
    // Función para crear una nueva tarjeta de producto
    // Busca esta función
    window.crearTarjetaProducto = function(index) {
   
    

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
                        <input type="text" 
                               class="form-control form-control-sm producto-busqueda" 
                               placeholder="Escriba para buscar producto..."
                               autocomplete="off"
                               style="height: 38px;">
                        <input type="hidden" 
                               class="producto-id" 
                               name="productos[${index}][id_producto]" 
                               required>
                        <div class="producto-resultados list-group position-absolute w-100 shadow-sm" 
                             style="display: none; z-index: 1000; max-height: 200px; overflow-y: auto; background: white;"></div>
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
                       
                    </div>
                    <div class="col-md-3">
                       
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
                            value="0.00"
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
                                value="0.00"
                                style="height: 38px;">
                        </div>
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

                <!-- NUEVA FILA: Fecha Entrega, Tipo Entrega y Comentarios -->
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label class="form-label">Fecha de Entrega</label>
                        <input type="date" 
                            class="form-control form-control-sm" 
                            name="productos[${index}][fecha_entrega]" 
                            style="height: 38px;">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tipo de Entrega</label>
                        <select class="form-select form-select-sm" 
                                name="productos[${index}][tipo_entrega]" 
                                style="height: 38px;">
                            <option value="">Seleccionar</option>
                            <option value="recoleccion">Recolección</option>
                            <option value="entrega">Entrega</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Comentarios</label>
                        <textarea class="form-control form-control-sm" 
                                  name="productos[${index}][comentarios]" 
                                  rows="2"
                                  placeholder="Comentarios adicionales..."
                                  style="resize: vertical;"></textarea>
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
            actualizarBotonesEliminar();
        }
    };

    
    // Función para actualizar subtotal de una fila (VERSIÓN CORREGIDA)
    // Función para calcular monto basado en porcentaje
window.calcularDescuentoPorPorcentaje = function(card) {
    const cantidad = parseFloat($(card).find('.cantidad-input').val()) || 0;
    const precio = parseFloat($(card).find('.precio-input').val()) || 0;
    let descuentoPorcentaje = parseFloat($(card).find('.descuento-porcentaje').val()) || 0;
    
    // Calcular subtotal sin descuento
    let subtotalSinDescuento = cantidad * precio;
    
    // Calcular monto basado en porcentaje
    let descuentoMonto = subtotalSinDescuento * (descuentoPorcentaje / 100);
    
    // Actualizar campo de monto
    $(card).find('.descuento-monto').val(descuentoMonto.toFixed(2));
    
    // Calcular subtotal con descuento
    let subtotal = subtotalSinDescuento - descuentoMonto;
    
    // Actualizar subtotal
    $(card).find('.subtotal-text').val('$' + subtotal.toFixed(2));
    $(card).closest('.producto-card').attr('data-subtotal', subtotal);
    
    return subtotal;
};

// Función para calcular porcentaje basado en monto
window.calcularDescuentoPorMonto = function(card) {
    const cantidad = parseFloat($(card).find('.cantidad-input').val()) || 0;
    const precio = parseFloat($(card).find('.precio-input').val()) || 0;
    let descuentoMonto = parseFloat($(card).find('.descuento-monto').val()) || 0;
    
    // Calcular subtotal sin descuento
    let subtotalSinDescuento = cantidad * precio;
    
    // Calcular porcentaje basado en monto
    let descuentoPorcentaje = 0;
    if (subtotalSinDescuento > 0) {
        descuentoPorcentaje = (descuentoMonto / subtotalSinDescuento) * 100;
        $(card).find('.descuento-porcentaje').val(descuentoPorcentaje.toFixed(2));
    }
    
    // Calcular subtotal con descuento
    let subtotal = subtotalSinDescuento - descuentoMonto;
    
    // Actualizar subtotal
    $(card).find('.subtotal-text').val('$' + subtotal.toFixed(2));
    $(card).closest('.producto-card').attr('data-subtotal', subtotal);
    
    return subtotal;
};

// Función principal actualizada (más simple)
window.actualizarSubtotalFila = function(card, campoModificado = null) {
    if (campoModificado === 'porcentaje') {
        calcularDescuentoPorPorcentaje(card);
    } else if (campoModificado === 'monto') {
        calcularDescuentoPorMonto(card);
    } else {
        // Si no se especifica campo, intentar con el que tenga valor
        const descuentoPorcentaje = parseFloat($(card).find('.descuento-porcentaje').val()) || 0;
        const descuentoMonto = parseFloat($(card).find('.descuento-monto').val()) || 0;
        
        if (descuentoMonto > 0) {
            calcularDescuentoPorMonto(card);
        } else if (descuentoPorcentaje > 0) {
            calcularDescuentoPorPorcentaje(card);
        } else {
            // Si no hay descuentos, solo actualizar subtotal sin descuento
            const cantidad = parseFloat($(card).find('.cantidad-input').val()) || 0;
            const precio = parseFloat($(card).find('.precio-input').val()) || 0;
            const subtotal = cantidad * precio;
            
            $(card).find('.subtotal-text').val('$' + subtotal.toFixed(2));
            $(card).closest('.producto-card').attr('data-subtotal', subtotal);
        }
    }
    
    calcularTotalesGlobales();
};

// Eventos actualizados
$(document).on('input', '.descuento-porcentaje', function() {
    const card = $(this).closest('.producto-card');
    actualizarSubtotalFila(card, 'porcentaje');
});

$(document).on('input', '.descuento-monto', function() {
    const card = $(this).closest('.producto-card');
    actualizarSubtotalFila(card, 'monto');
});

$(document).on('input', '.cantidad-input, .precio-input', function() {
    const card = $(this).closest('.producto-card');
    // Al cambiar cantidad o precio, recalcular con el método apropiado
    const descuentoMonto = parseFloat($(card).find('.descuento-monto').val()) || 0;
    const descuentoPorcentaje = parseFloat($(card).find('.descuento-porcentaje').val()) || 0;
    
    if (descuentoMonto > 0) {
        actualizarSubtotalFila(card, 'monto');
    } else if (descuentoPorcentaje > 0) {
        actualizarSubtotalFila(card, 'porcentaje');
    } else {
        actualizarSubtotalFila(card);
    }
});

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

 
    
    // Calcular subtotales iniciales para cada tarjeta
    $('.producto-card').each(function() {
        const cantidad = parseFloat($(this).find('.cantidad-input').val()) || 0;
        const precio = parseFloat($(this).find('.precio-input').val()) || 0;
        const subtotal = cantidad * precio;
        $(this).attr('data-subtotal', subtotal);
    });
    
    calcularTotalesGlobales();
    actualizarBotonesEliminar();

   
});



// Inicialización de búsqueda de proveedores - RESPUESTA INMEDIATA
$(document).ready(function() {
    
    // Evento de búsqueda - keydown para respuesta inmediata
    $('#proveedor_busqueda').on('keyup', function() {
        CargarListaProveedores( $(this).val());       
        
    });
    
    // Seleccionar proveedor
    $(document).on('click', '.proveedor-item', function() {
        const proveedorId = $(this).data('id');
        const proveedorClave = $(this).data('clave');
        const proveedorNombre = $(this).data('nombre');
        const proveedorTexto = proveedorClave + ' - ' + proveedorNombre;
        
        $('#id_proveedor').val(proveedorId);
        $('#proveedor_busqueda').val(proveedorTexto);
        $('#proveedor_resultados').hide();
    });
    
    // Ocultar resultados al hacer clic fuera
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#proveedor_busqueda, #proveedor_resultados').length) {
            $('#proveedor_resultados').hide();
        }
    });
    
    // Navegación con teclado
    $(document).on('keydown', '#proveedor_busqueda', function(e) {
        const resultados = $('#proveedor_resultados .proveedor-item');
        const selected = resultados.filter('.active');
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (selected.length === 0) {
                resultados.first().addClass('active');
            } else {
                selected.removeClass('active');
                selected.next().addClass('active');
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (selected.length === 0) {
                resultados.last().addClass('active');
            } else {
                selected.removeClass('active');
                selected.prev().addClass('active');
            }
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (selected.length > 0) {
                selected.click();
            }
        } else if (e.key === 'Escape') {
            $('#proveedor_resultados').hide();
        }
    });
});


// Inicialización de búsqueda de productos
$(document).ready(function() {
    let searchTimeoutProductos;
    
    // Evento de búsqueda de productos (delegación para productos dinámicos)
    $(document).on('input', '.producto-busqueda', function() {
        const termino = $(this).val().trim();
        const card = $(this).closest('.producto-card');
        
       CargarListaProductos(termino, card);
    });
    
    // Seleccionar producto
    $(document).on('click', '.producto-item', function() {
        const productoId = $(this).data('id');
        const productoClave = $(this).data('clave');
        const productoDescripcion = $(this).data('descripcion');
        const productoUnidad = $(this).data('unidad');
        const productoPrecio = $(this).data('precio');
        
        const card = $(this).closest('.producto-card');
        
        card.find('.producto-id').val(productoId);
        card.find('.producto-busqueda').val(productoClave);
        card.find('.descripcion-input').val(productoDescripcion);
        card.find('.unidad-input').val(productoUnidad);
        card.find('.precio-input').val(productoPrecio);
        card.find('.producto-resultados').hide();
        
        actualizarSubtotalFila(card);
    });
    
    // Ocultar resultados de productos al hacer clic fuera
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.producto-busqueda, .producto-resultados').length) {
            $('.producto-resultados').hide();
        }
    });
    
    // Navegación con teclado para productos
    $(document).on('keydown', '.producto-busqueda', function(e) {
        const card = $(this).closest('.producto-card');
        const resultados = card.find('.producto-resultados .producto-item');
        const selected = resultados.filter('.active');
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (selected.length === 0) {
                resultados.first().addClass('active');
            } else {
                selected.removeClass('active');
                selected.next().addClass('active');
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (selected.length === 0) {
                resultados.last().addClass('active');
            } else {
                selected.removeClass('active');
                selected.prev().addClass('active');
            }
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (selected.length > 0) {
                selected.click();
            }
        } else if (e.key === 'Escape') {
            card.find('.producto-resultados').hide();
        }
    });
});
</script>