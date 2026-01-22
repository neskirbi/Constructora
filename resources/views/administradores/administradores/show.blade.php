<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    
    <style>
        .required-field::after {
            content: " *";
            color: #dc3545;
        }
        
        .admin-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        
        .admin-avatar {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: bold;
            border: 4px solid white;
        }
        
        .badge-admin {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
        
        .email-status {
            margin-top: 5px;
            font-size: 0.875rem;
        }
        
        .email-unchanged {
            color: #6c757d;
        }
        
        .email-changed {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="main-container">
        @include('toast.toasts')
        @include('administradores.sidebar')
        
        <main class="main-content" id="mainContent">
            @include('administradores.navbar')

            <div class="content-area">
                <div class="container-fluid py-4">
                    <div class="admin-header">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center">
                                    <div class="admin-avatar me-4">
                                        {{ substr($administrador->nombres, 0, 1) }}{{ substr($administrador->apellidos, 0, 1) }}
                                    </div>
                                    <div>
                                        <h1 class="h2 mb-1">{{ $administrador->nombres }} {{ $administrador->apellidos }}</h1>
                                        <div class="d-flex align-items-center flex-wrap gap-2">
                                            @php
                                                $badgeClass = [
                                                    'administrador' => 'bg-primary',
                                                    'aingresos' => 'bg-success',
                                                    'adestajos' => 'bg-warning',
                                                    'acompras' => 'bg-info'
                                                ][$tipo] ?? 'bg-secondary';
                                                
                                                $tipoNombre = [
                                                    'administrador' => 'Administrador General',
                                                    'aingresos' => 'Administrador de Ingresos',
                                                    'adestajos' => 'Administrador de Destajos',
                                                    'acompras' => 'Administrador de Compras'
                                                ][$tipo] ?? $tipo;
                                            @endphp
                                            
                                            <span class="badge badge-admin {{ $badgeClass }}">{{ $tipoNombre }}</span>
                                            
                                            @if(isset($administrador->principal) && $administrador->principal)
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-crown me-1"></i>Administrador Principal
                                                </span>
                                            @endif
                                            
                                            <span class="text-white-50">
                                                <i class="far fa-calendar me-1"></i>
                                                Registrado: {{ $administrador->created_at->format('d/m/Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <div>
                                    <a href="{{ route('administradores.index') }}" class="btn btn-light">
                                        <i class="fas fa-arrow-left me-2"></i>Volver a la lista
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-edit me-2"></i>Editar Administrador
                            </h5>
                        </div>
                        <div class="card-body">
                            <form id="formEditarAdministrador" method="POST" action="{{ route('administradores.update', $administrador->id) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="tipo_original" value="{{ $tipo }}">
                                <input type="hidden" id="email_changed" name="email_changed" value="0">
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nombres" class="form-label required-field">Nombres</label>
                                        <input type="text" class="form-control" id="nombres" name="nombres" 
                                               value="{{ old('nombres', $administrador->nombres) }}" required>
                                        <div class="invalid-feedback">Por favor ingresa los nombres.</div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="apellidos" class="form-label required-field">Apellidos</label>
                                        <input type="text" class="form-control" id="apellidos" name="apellidos" 
                                               value="{{ old('apellidos', $administrador->apellidos) }}" required>
                                        <div class="invalid-feedback">Por favor ingresa los apellidos.</div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="mail" class="form-label required-field">Correo Electrónico</label>
                                        <input type="email" class="form-control" id="mail" name="mail" 
                                               value="{{ old('mail', $administrador->mail) }}" required>
                                        <div class="invalid-feedback">Por favor ingresa un correo válido.</div>
                                        <div id="emailStatus" class="email-status email-unchanged">
                                            <i class="fas fa-info-circle me-1"></i>Email sin cambios
                                        </div>
                                    </div>
                                    <!-- Campo para administrador principal -->
                                    <div class="col-6 mb-3" id="campoPrincipal" style="{{ $tipo == 'administrador' ? '' : 'display: none;' }}">
                                        <br><div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="principal" name="principal" value="1"
                                                {{ (isset($administrador->principal) && $administrador->principal) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="principal">
                                                <strong>Marcar como Administrador Principal</strong>
                                            </label>
                                            <small class="form-text text-muted d-block">
                                                <i class="fas fa-info-circle"></i> El administrador principal tiene acceso completo a todas las funciones.
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                
                                
                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('administradores.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Guardar Cambios
                                    </button>
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
            const form = document.getElementById('formEditarAdministrador');
            const mailInput = document.getElementById('mail');
            const emailStatus = document.getElementById('emailStatus');
            const emailChangedInput = document.getElementById('email_changed');
            const mailOriginal = '{{ $administrador->mail }}';
            
            // Mostrar estado inicial
            updateEmailStatus();
            
            // Escuchar cambios en el email
            mailInput.addEventListener('input', updateEmailStatus);
            mailInput.addEventListener('blur', updateEmailStatus);
            
            function updateEmailStatus() {
                const nuevoMail = mailInput.value.trim();
                const esMismoMail = nuevoMail === mailOriginal;
                
                if (esMismoMail) {
                    // Email sin cambios
                    emailStatus.innerHTML = '<i class="fas fa-check-circle me-1"></i>Email sin cambios';
                    emailStatus.className = 'email-status email-unchanged';
                    emailChangedInput.value = '0';
                } else {
                    // Email cambiado
                    emailStatus.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>Email modificado - se validará duplicado';
                    emailStatus.className = 'email-status email-changed';
                    emailChangedInput.value = '1';
                }
            }
            
            // Validación del formulario
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                form.classList.add('was-validated');
            });
        });
    </script>
</body>
</html>