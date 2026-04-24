<script>
document.addEventListener('DOMContentLoaded', function() {
   
    // Variable global para el contador de productos
    window.productCount = {{ isset($detalles) ? count($detalles) : 1 }};

    // VALIDACIÓN DEL FORMULARIO
    $('#destajoForm').on('submit', function(event) {
        let hasProducts = false;
        
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
                        <div class="col-md-9">
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
                        <div class="col-md-9">
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
            actualizarBotonesEliminar();
        }
    };

    // Función para actualizar subtotal
    window.actualizarSubtotalFila = function(card) {
        const cantidad = parseFloat($(card).find('.cantidad-input').val()) || 0;
        const precio = parseFloat($(card).find('.precio-input').val()) || 0;
        const subtotal = cantidad * precio;
        
        $(card).find('.subtotal-text').val('$' + subtotal.toFixed(2));
        $(card).closest('.producto-card').attr('data-subtotal', subtotal);
        
        calcularTotalesGlobales();
    };

    // Eventos
    $(document).on('input', '.cantidad-input, .precio-input', function() {
        const card = $(this).closest('.producto-card');
        actualizarSubtotalFila(card);
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
            const btnRemove = $(this).closest('.producto-card-wrapper').find('.btn-remove-row');
            if (btnRemove.length) {
                btnRemove.css('display', cards.length > 1 ? 'inline-flex' : 'none');
            }
        });
    };

    // Eliminar fila
    $(document).on('click', '.btn-remove-row', function() {
        if ($('.producto-card').length > 1) {
            $(this).closest('.producto-card-wrapper').remove();
            calcularTotalesGlobales();
            actualizarBotonesEliminar();
        }
    });

    // Calcular subtotales iniciales
    $('.producto-card').each(function() {
        const cantidad = parseFloat($(this).find('.cantidad-input').val()) || 0;
        const precio = parseFloat($(this).find('.precio-input').val()) || 0;
        const subtotal = cantidad * precio;
        $(this).attr('data-subtotal', subtotal);
    });
    
    calcularTotalesGlobales();
    actualizarBotonesEliminar();
});

// Inicialización de búsqueda de proveedores
$(document).ready(function() {
    $('#proveedor_busqueda').on('keyup', function() {
        CargarListaProveedores($(this).val());
    });
    
    $(document).on('click', '.proveedor-item', function() {
        const proveedorId = $(this).data('id');
        const proveedorClave = $(this).data('clave');
        const proveedorNombre = $(this).data('nombre');
        const proveedorTexto = proveedorClave + ' - ' + proveedorNombre;
        
        $('#id_proveedor').val(proveedorId);
        $('#proveedor_busqueda').val(proveedorTexto);
        $('#proveedor_resultados').hide();
    });
    
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#proveedor_busqueda, #proveedor_resultados').length) {
            $('#proveedor_resultados').hide();
        }
    });
    
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
    $(document).on('input', '.producto-busqueda', function() {
        const termino = $(this).val().trim();
        const card = $(this).closest('.producto-card');
        
        if (termino.length === 0) {
            card.find('.producto-resultados').hide();
            return;
        }
        
        if (termino.length >= 2) {
            CargarListaProductos(termino, card);
        }
    });
    
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
    
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.producto-busqueda, .producto-resultados').length) {
            $('.producto-resultados').hide();
        }
    });
    
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


    if ($('#id_contrato').length) {
        $('#id_contrato').select2({
            theme: 'default',
            placeholder: 'Seleccione un contrato',
            allowClear: true,
            width: '100%',
            dropdownAutoWidth: true,
            language: {
                noResults: function() {
                    return "No se encontraron contratos";
                },
                searching: function() {
                    return "Buscando...";
                }
            },
            // Para mejorar la búsqueda en textos largos
            matcher: function(params, data) {
                // Si no hay término de búsqueda, mostrar todos
                if ($.trim(params.term) === '') {
                    return data;
                }
                
                // Convertir a minúsculas para búsqueda insensible a mayúsculas
                var term = params.term.toLowerCase();
                var text = data.text.toLowerCase();
                
                // Buscar en el texto completo
                if (text.indexOf(term) > -1) {
                    return data;
                }
                
                return null;
            }
        });
        
        // Ajustar el ancho del dropdown cuando se abre
        $('#id_contrato').on('select2:open', function() {
            $('.select2-container--open .select2-dropdown').css('min-width', '350px');
        });
    }
});
</script>