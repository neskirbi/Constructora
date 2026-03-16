<script>
// ==============================================
// FUNCIONES COMUNES PARA CREATE Y SHOW
// ==============================================

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
$('#iva').on('input', calcularTotalesGlobales);


</script>