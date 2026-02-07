<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Editar Proveedor</title>
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
        @include('adestajos.sidebar')
        
        <main class="main-content" id="mainContent">
            @include('adestajos.navbar')

            <div class="content-area">
                <div class="container-fluid py-4">
                    <div class="card shadow-sm form-card">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-edit me-2 text-warning"></i>
                                    Editar Proveedor
                                </h5>
                                <a href="{{ route('proveedoresds.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-arrow-left me-1"></i> Regresar
                                </a>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <form action="{{ route('proveedoresds.update', $proveedor->id) }}" method="POST" id="proveedorForm">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="clave" class="form-label required-label">Clave</label>
                                            <input type="text" 
                                                   class="form-control form-control-sm @error('clave') is-invalid @enderror" 
                                                   id="clave" 
                                                   name="clave" 
                                                   value="{{ old('clave', $proveedor->clave) }}"
                                                   maxlength="50"
                                                   required>
                                            @error('clave')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="nombre" class="form-label required-label">Nombre</label>
                                            <input type="text" 
                                                   class="form-control form-control-sm @error('nombre') is-invalid @enderror" 
                                                   id="nombre" 
                                                   name="nombre" 
                                                   value="{{ old('nombre', $proveedor->nombre) }}"
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
                                                   value="{{ old('calle', $proveedor->calle) }}"
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
                                                   value="{{ old('telefono', $proveedor->telefono) }}"
                                                   maxlength="20"
                                                   required>
                                            @error('telefono')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="clasificacion" class="form-label required-label">Clasificación</label>
                                            <input type="text" 
                                                   class="form-control form-control-sm @error('clasificacion') is-invalid @enderror" 
                                                   id="clasificacion" 
                                                   name="clasificacion" 
                                                   value="{{ old('clasificacion', $proveedor->clasificacion) }}"
                                                   maxlength="100"
                                                   required>
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
                                                    <option value="{{ $option }}" {{ old('estatus', $proveedor->estatus) == $option ? 'selected' : '' }}>
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
                                            <select class="form-select form-select-sm @error('especialidad') is-invalid @enderror" 
                                                    id="especialidad" 
                                                    name="especialidad"
                                                    required>
                                                <option value="">Seleccione una especialidad</option>
                                                @foreach($especialidadOptions as $option)
                                                    <option value="{{ $option }}" {{ old('especialidad', $proveedor->especialidad) == $option ? 'selected' : '' }}>
                                                        {{ $option }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('especialidad')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('proveedoresds.index') }}" class="btn btn-secondary btn-sm">
                                                Cancelar
                                            </a>
                                            <button type="submit" class="btn btn-warning btn-sm">
                                                Actualizar
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

    @include('footer')
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('clave').focus();
            
            const form = document.getElementById('proveedorForm');
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    </script>
</body>
</html>