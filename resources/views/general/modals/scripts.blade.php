<script>
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
</script>