<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')

    
    <!-- Estilos personalizados -->
    <style>
        .required-field::after {
            content: " *";
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="main-container">
        @include('toast.toasts')

        @include('administradores.sidebar')
        

        <!-- Contenido principal -->
        <main class="main-content" id="mainContent">
            @include('administradores.navbar')

            <!-- Área de contenido -->
            <div class="content-area">
                <div class="container-fluid py-4">
                    <!-- Título y botón volver -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="h3 mb-0">
                            <i class="fas fa-user-plus me-2"></i>Agregar Nuevo Administrador
                        </h1>
                        <a href="{{ route('administradores.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver a la lista
                        </a>
                    </div>
                    
                    <!-- Formulario -->
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-user-circle me-2"></i>Información del Administrador
                            </h5>
                        </div>
                        <div class="card-body">
                            <form id="formAgregarAdministrador" method="POST" action="{{ route('administradores.store') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nombres" class="form-label required-field">Nombres</label>
                                        <input type="text" class="form-control" id="nombres" name="nombres" required>
                                        <div class="invalid-feedback">Por favor ingresa los nombres.</div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="apellidos" class="form-label required-field">Apellidos</label>
                                        <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                                        <div class="invalid-feedback">Por favor ingresa los apellidos.</div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="mail" class="form-label required-field">Correo Electrónico</label>
                                        <input type="email" class="form-control" id="mail" name="mail" required>
                                        <div class="invalid-feedback">Por favor ingresa un correo válido.</div>
                                        <small class="form-text text-muted">Se enviará una contraseña temporal a este correo.</small>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="tipo_administrador" class="form-label required-field">Área de Administración</label>
                                        <select class="form-select" id="tipo_administrador" name="tipo_administrador" required>
                                            <option value="">Selecciona un área</option>
                                            <option value="administrador">Administración General</option>
                                            <option value="aingresos">Administración de Ingresos</option>
                                            <option value="adestajos">Administración de Destajos</option>
                                            <option value="acompras">Administración de Compras</option>
                                        </select>
                                        <div class="invalid-feedback">Por favor selecciona un área.</div>
                                    </div>
                                    
                                    <!-- Campo para administrador principal (solo para administradores generales) -->
                                    <div class="col-12 mb-3" id="campoPrincipal" style="display: none;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="principal" name="principal" value="1">
                                            <label class="form-check-label" for="principal">
                                                <strong>Marcar como Administrador Principal</strong>
                                            </label>
                                            <small class="form-text text-muted d-block">
                                                <i class="fas fa-info-circle"></i> El administrador principal tiene acceso completo a todas las funciones del sistema.
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <!-- Información sobre contraseña -->
                                    <div class="col-12 mt-4">
                                        <div class="alert alert-warning">
                                            <h6 class="alert-heading"><i class="fas fa-key me-2"></i>Información sobre la contraseña:</h6>
                                            <p class="mb-0">
                                                Al guardar el administrador, se generará automáticamente una contraseña temporal 
                                                que será enviada al correo electrónico proporcionado. El administrador deberá 
                                                cambiar su contraseña en el primer inicio de sesión.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('administradores.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Guardar Administrador
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Información de las áreas -->
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h6 class="card-title text-primary">
                                        <i class="fas fa-user-shield me-2"></i>Administración General
                                    </h6>
                                    <p class="card-text small">Acceso completo a todas las funciones del sistema.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-success">
                                <div class="card-body">
                                    <h6 class="card-title text-success">
                                        <i class="fas fa-sign-in-alt me-2"></i>Administración de Ingresos
                                    </h6>
                                    <p class="card-text small">Manejo exclusivo del módulo de ingresos.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-warning">
                                <div class="card-body">
                                    <h6 class="card-title text-warning">
                                        <i class="fas fa-hammer me-2"></i>Administración de Destajos
                                    </h6>
                                    <p class="card-text small">Manejo exclusivo del módulo de destajos.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-info">
                                <div class="card-body">
                                    <h6 class="card-title text-info">
                                        <i class="fas fa-shopping-cart me-2"></i>Administración de Compras
                                    </h6>
                                    <p class="card-text small">Manejo exclusivo del módulo de compras.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @include('footer')
    
    <!-- Script para el formulario -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formAgregarAdministrador');
            const tipoAdministrador = document.getElementById('tipo_administrador');
            const campoPrincipal = document.getElementById('campoPrincipal');
            
            // Mostrar/ocultar campo de administrador principal
            tipoAdministrador.addEventListener('change', function() {
                if (this.value === 'administrador') {
                    campoPrincipal.style.display = 'block';
                } else {
                    campoPrincipal.style.display = 'none';
                    document.getElementById('principal').checked = false;
                }
            });
            
            // Validación del formulario
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                form.classList.add('was-validated');
            });
            
            // Validación en tiempo real
            const inputs = form.querySelectorAll('input[required], select[required]');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (!this.checkValidity()) {
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                });
                
                input.addEventListener('input', function() {
                    if (this.checkValidity()) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else {
                        this.classList.remove('is-valid');
                    }
                });
            });
        });
    </script>
</body>
</html>