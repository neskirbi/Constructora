<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Nuevo Proveedor</title>
    <style>
        .required-label::after {
            content: " *";
            color: #dc3545;
        }
        .form-card {
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="main-container">
       @include('toast.toasts')
        @if(Guard() == 'adestajos')
            @include('adestajos.sidebar')
        @elseif(Guard() == 'acompras')
            @include('acompras.sidebar')
        @endif
        
        <main class="main-content" id="mainContent">
            @if(Guard() == 'adestajos')
                @include('adestajos.navbar')
            @elseif(Guard() == 'acompras')
                @include('acompras.navbar')
            @endif

            <div class="content-area">
                <div class="container-fluid py-4">
                    <div class="card shadow-sm form-card">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-plus-circle me-2 text-success"></i>
                                    Nuevo Proveedor
                                </h5>
                                <a href="{{ route('proveedoresds.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-arrow-left me-1"></i> Volver
                                </a>
                            </div>
                        </div>
                        
                        <div class="card-body">
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
                                                   value="{{ old('clave', $siguienteClave) }}"
                                                   min="1"
                                                   step="1"
                                                   required
                                                   noformat>
                                            @error('clave')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Identificador único del proveedor. Clave sugerida: <strong>{{ $siguienteClave }}</strong></small>
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
                                                        id="especialidad" 
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
                                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#nuevaEspecialidadModal">
                                                    <i class="fas fa-plus"></i> Nueva
                                                </button>
                                            </div>
                                            @error('especialidad')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Puede seleccionar una existente o agregar una nueva</small>
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
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal para agregar nueva especialidad -->
    <div class="modal fade" id="nuevaEspecialidadModal" tabindex="-1" aria-labelledby="nuevaEspecialidadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="nuevaEspecialidadModalLabel">
                        <i class="fas fa-plus-circle text-success me-2"></i>
                        Nueva Especialidad
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nuevaEspecialidad" class="form-label required-label">Nombre de la especialidad</label>
                        <input type="text" 
                               class="form-control form-control-sm" 
                               id="nuevaEspecialidad" 
                               maxlength="100"
                               placeholder="Ingrese la nueva especialidad">
                        <small class="text-muted">La especialidad se agregará a las opciones disponibles</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </button>
                    <button type="button" class="btn btn-success btn-sm" id="guardarNuevaEspecialidad">
                        <i class="fas fa-save me-1"></i> Agregar
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('footer')
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
        });
    </script>
</body>
</html>