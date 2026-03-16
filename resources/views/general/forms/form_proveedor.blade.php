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
<form action="{{ route('proveedoresds.store') }}" method="POST" id="proveedorForm">
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
               name="especialidad"
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
                <button type="submit" class="btn btn-success btn-sm">
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
    select.removeAttribute('name'); // Quitar el name del select
    
    input.style.display = 'block';
    input.disabled = false;
    input.setAttribute('name', 'especialidad'); // Poner el name en el input
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
    select.setAttribute('name', 'especialidad'); // Poner el name en el select
    
    input.style.display = 'none';
    input.disabled = true;
    input.removeAttribute('name'); // Quitar el name del input
    input.value = ''; // Limpiar el input
    
    btnNueva.style.display = 'block';
    btnVolver.style.display = 'none';
}

// Si hay un error de validación y venía del input, mostrar el input
document.addEventListener('DOMContentLoaded', function() {
    const oldValue = "{{ old('especialidad') }}";
    if (oldValue) {
        const select = document.getElementById('especialidadSelect');
        const options = Array.from(select.options).map(opt => opt.value);
        
        if (!options.includes(oldValue)) {
            mostrarInputEspecialidad();
            document.getElementById('especialidadInput').value = oldValue;
        }
    }
});

////////////////////////////////////////////////////////////////////////
    function StartFormProveedor(){
        // Enfocar el campo clave automáticamente
        document.getElementById('clave').focus();
        
        // Validación del formulario
        const form = document.getElementById('proveedorForm');
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
        
        // Manejar nueva especialidad
        document.getElementById('guardarNuevaEspecialidad').addEventListener('click', function() {
            const nuevaEspecialidad = document.getElementById('nuevaEspecialidad').value.trim();
            
            if (nuevaEspecialidad === '') {
                alert('Por favor ingrese un nombre para la especialidad');
                return;
            }
            
            // Verificar si ya existe
            const select = document.getElementById('especialidad');
            let existe = false;
            
            for (let i = 0; i < select.options.length; i++) {
                if (select.options[i].value.toUpperCase() === nuevaEspecialidad.toUpperCase()) {
                    existe = true;
                    break;
                }
            }
            
            if (existe) {
                alert('Esta especialidad ya existe en la lista');
                return;
            }
            
            // Agregar la nueva opción al select
            const option = document.createElement('option');
            option.value = nuevaEspecialidad;
            option.text = nuevaEspecialidad;
            option.selected = true;
            select.add(option);
            
            // Cerrar modal y limpiar
            const modal = bootstrap.Modal.getInstance(document.getElementById('nuevaEspecialidadModal'));
            modal.hide();
            document.getElementById('nuevaEspecialidad').value = '';
        });
        
        // Limpiar campo al cerrar el modal
        const modal = document.getElementById('nuevaEspecialidadModal');
        modal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('nuevaEspecialidad').value = '';
        });
    }

 StartFormProveedor();
</script>