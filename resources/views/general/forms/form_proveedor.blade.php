<?php 
 // Obtener especialidades únicas de la base de datos
    $especialidadOptions = DB::table('proveedores_servicios')
        ->select('especialidad')
        ->distinct()
        ->whereNotNull('especialidad')
        ->where('especialidad', '!=', '')
        ->orderBy('especialidad')
        ->pluck('especialidad')
        ->toArray();
    $estatusOptions = ['Activo', 'Inactivo', 'Suspendido'];        
    $clasificacionOptions = ['ADMON', 'COMPRAS', 'DESTAJO', 'MATERIALES', 'SERVICIOS'];
?>

<!-- Agregar jQuery y SweetAlert2 si no los tienes -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<form action="{{ route('proveedoresds.guardar') }}" method="POST" id="proveedorForm">
    @csrf
    
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="clave" class="form-label required-label">Clave</label>
                <input type="number" 
                        class="form-control form-control-sm @error('clave') is-invalid @enderror" 
                        id="clave" 
                        name="clave" 
                        value="{{ old('clave', SiguienteClaveProveedor()) }}"
                        min="1"
                        step="1"
                        required
                        noformat>
                @error('clave')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Identificador único del proveedor. Clave sugerida: <strong>{{ SiguienteClaveProveedor() }}</strong></small>
            </div>
            
            <div class="mb-3">
                <label for="nombre" class="form-label required-label">Nombre</label>
                <input type="text" 
                        class="form-control form-control-sm @error('nombre') is-invalid @enderror" 
                        id="nombre" 
                        name="nombre" 
                        value="{{ old('nombre') }}"
                        maxlength="255"
                        required>
                @error('nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="calle" class="form-label required-label">Dirección</label>
                <input type="text" 
                        class="form-control form-control-sm @error('calle') is-invalid @enderror" 
                        id="calle" 
                        name="calle" 
                        value="{{ old('calle') }}"
                        maxlength="255"
                        required>
                @error('calle')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="telefono" class="form-label required-label">Teléfono</label>
                <input type="text" 
                        class="form-control form-control-sm @error('telefono') is-invalid @enderror" 
                        id="telefono" 
                        name="telefono" 
                        value="{{ old('telefono') }}"
                        maxlength="20"
                        required>
                @error('telefono')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="clasificacion" class="form-label required-label">Clasificación</label>
                <select class="form-select form-select-sm @error('clasificacion') is-invalid @enderror" 
                        id="clasificacion" 
                        name="clasificacion"
                        required>
                    <option value="">Seleccione una clasificación</option>
                    @foreach($clasificacionOptions as $option)
                        <option value="{{ $option }}" {{ old('clasificacion') == $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                    @endforeach
                </select>
                @error('clasificacion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="estatus" class="form-label required-label">Estatus</label>
                <select class="form-select form-select-sm @error('estatus') is-invalid @enderror" 
                        id="estatus" 
                        name="estatus"
                        required>
                    <option value="">Seleccione un estatus</option>
                    @foreach($estatusOptions as $option)
                        <option value="{{ $option }}" {{ old('estatus') == $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                    @endforeach
                </select>
                @error('estatus')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="especialidad" class="form-label required-label">Especialidad</label>
                <div class="input-group input-group-sm">
                    <select class="form-select form-select-sm @error('especialidad') is-invalid @enderror" 
                            id="especialidadSelect" 
                            name="especialidad"
                            required>
                        <option value="">Seleccione una especialidad</option>
                        @if(!empty($especialidadOptions) && count($especialidadOptions) > 0)
                            @foreach($especialidadOptions as $option)
                                <option value="{{ $option }}" {{ old('especialidad') == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    
                    <!-- Input para nueva especialidad (oculto inicialmente) -->
                    <input type="text" 
                           class="form-control form-control-sm @error('especialidad') is-invalid @enderror" 
                           id="especialidadInput" 
                           maxlength="100"
                           placeholder="Ingrese nueva especialidad"
                           style="display: none;"
                           value="{{ old('especialidad') }}">
                    
                    <!-- Botón para cambiar a input -->
                    <button class="btn btn-outline-secondary" type="button" id="btnNuevaEspecialidad" onclick="mostrarInputEspecialidad()">
                        <i class="fas fa-plus"></i> Nueva
                    </button>
                    
                    <!-- Botón para volver al select (oculto inicialmente) -->
                    <button class="btn btn-outline-secondary" type="button" id="btnVolverSelect" onclick="volverSelectEspecialidad()" style="display: none;">
                        <i class="fas fa-list"></i> Volver al listado
                    </button>
                </div>
                @error('especialidad')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Puede seleccionar una existente o hacer clic en "Nueva" para crear una</small>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('proveedoresds.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-times me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success btn-sm" id="btnGuardar">
                    <i class="fas fa-save me-1"></i> Guardar Proveedor
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    function mostrarInputEspecialidad() {
        const select = document.getElementById('especialidadSelect');
        const input = document.getElementById('especialidadInput');
        const btnNueva = document.getElementById('btnNuevaEspecialidad');
        const btnVolver = document.getElementById('btnVolverSelect');
        
        select.style.display = 'none';
        select.disabled = true;
        select.removeAttribute('name'); // Quitar name del select
        
        input.style.display = 'block';
        input.disabled = false;
        input.setAttribute('name', 'especialidad'); // Poner name en el input
        input.focus();
        
        btnNueva.style.display = 'none';
        btnVolver.style.display = 'block';
    }

    function volverSelectEspecialidad() {
        const select = document.getElementById('especialidadSelect');
        const input = document.getElementById('especialidadInput');
        const btnNueva = document.getElementById('btnNuevaEspecialidad');
        const btnVolver = document.getElementById('btnVolverSelect');
        
        select.style.display = 'block';
        select.disabled = false;
        select.setAttribute('name', 'especialidad'); // Poner name en el select
        
        input.style.display = 'none';
        input.disabled = true;
        input.removeAttribute('name'); // Quitar name del input
        input.value = ''; // Limpiar el input
        
        btnNueva.style.display = 'block';
        btnVolver.style.display = 'none';
    }

    $(document).ready(function() {
        console.log('Document ready ejecutado');
        
        // Enfocar el campo clave automáticamente
        $('#clave').focus();
        
        // Si hay un error de validación y venía del input, mostrar el input
        const oldValue = "{{ old('especialidad') }}";
        if (oldValue) {
            const select = document.getElementById('especialidadSelect');
            const options = Array.from(select.options).map(opt => opt.value);
            
            if (!options.includes(oldValue)) {
                mostrarInputEspecialidad();
                document.getElementById('especialidadInput').value = oldValue;
            }
        }

        // Evento submit del formulario
        $('#proveedorForm').on('submit', function(e) {
            e.preventDefault();
            console.log('Submit ejecutado');
            
            const form = $(this);
            const submitBtn = $('#btnGuardar');
            const originalText = submitBtn.html();
            
            // Validar el formulario
            if (!this.checkValidity()) {
                console.log('Formulario no válido');
                form.addClass('was-validated');
                return;
            }
            
            console.log('Formulario válido, enviando...');
            
            // Deshabilitar botón
            submitBtn.prop('disabled', true);
            submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i> Guardando...');
            
            // Limpiar errores anteriores
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            
            // Crear FormData
            const formData = new FormData(this);
            
            // Debug: Ver qué datos se envían
            console.log('Datos del formulario:');
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
            
            // Enviar con AJAX
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    console.log('Respuesta éxito:', response);
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            // window.location.href = response.redirect; // <-- COMENTADA
                            // Opcional: puedes limpiar el formulario
                            $('#proveedorForm')[0].reset();
                            $('#clave').focus();
                        });
                    }
                },
                error: function(xhr) {
                    console.log('Error response:', xhr);
                    
                    if (xhr.status === 422) {
                        // Errores de validación
                        const errors = xhr.responseJSON.errors;
                        console.log('Errores validación:', errors);
                        
                        // Mostrar errores en cada campo
                        $.each(errors, function(field, messages) {
                            const input = $(`#${field}`);
                            input.addClass('is-invalid');
                            
                            if (messages.length > 0) {
                                input.after(`<div class="invalid-feedback">${messages[0]}</div>`);
                            }
                        });
                        
                        const firstError = Object.values(errors)[0][0];
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de validación',
                            text: firstError
                        });
                        
                    } else {
                        let errorMsg = 'Error al guardar el proveedor';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMsg
                        });
                    }
                    
                    // Restaurar botón
                    submitBtn.prop('disabled', false);
                    submitBtn.html(originalText);
                }
            });
        });
    });
</script>