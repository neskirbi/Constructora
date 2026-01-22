<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <style>
        :root {
            --primary-blue: #0d1b48;
            --light-blue: #3949ab;
            --accent-gold: #ffb74d;
        }
        
        body {
            background: #ff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .registro-container {
            max-width: 450px;
            width: 100%;
        }
        
        .registro-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        
        .registro-header {
            background: var(--primary-blue);
            color: white;
            text-align: center;
            padding: 30px 20px;
        }
        
        .registro-icon {
            font-size: 3.5rem;
            margin-bottom: 15px;
            color: var(--accent-gold);
        }
        
        .registro-body {
            padding: 30px;
        }
        
        .form-control {
            border-radius: 8px;
            border: 2px solid #e0e0e0;
            padding: 12px 15px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--light-blue);
            box-shadow: 0 0 0 0.2rem rgba(57, 73, 171, 0.25);
        }
        
        .btn-registro {
            background: var(--primary-blue);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            width: 100%;
        }
        
        .btn-registro:hover {
            background: var(--light-blue);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .registro-footer {
            text-align: center;
            padding: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 0.9rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        
        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-right: none;
        }
        
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 5px;
        }
        
        .password-toggle {
            cursor: pointer;
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-left: none;
            color: #666;
        }
        
        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: var(--light-blue);
            text-decoration: none;
        }
    </style>
</head>
<body>
@include('toast.toasts')  
    <div class="registro-container">
        <div class="registro-card">
            <!-- Encabezado -->
            <div class="registro-header">
                <div class="registro-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h2 class="h4 mb-2">Nuevo Administrador</h2>
                <p class="mb-0 opacity-75">Registro de personal autorizado</p>
            </div>
            
            <!-- Formulario -->
            <div class="registro-body">
                <form method="POST" action="{{ url('reg') }}">
                    @csrf
                    
                    <!-- Nombres -->
                    <div class="mb-3">
                        <label class="form-label">Nombres</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   name="nombres" 
                                   placeholder="Ingresa los nombres" 
                                   required 
                                   autofocus>
                        </div>
                        @error('nombres')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Apellidos -->
                    <div class="mb-3">
                        <label class="form-label">Apellidos</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-users"></i>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   name="apellidos" 
                                   placeholder="Ingresa los apellidos" 
                                   required>
                        </div>
                        @error('apellidos')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" 
                                   class="form-control" 
                                   name="mail" 
                                   placeholder="ejemplo@constructora.com" 
                                   required>
                        </div>
                        @error('mail')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Contraseña (visible) -->
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   name="pass" 
                                   id="password" 
                                   placeholder="Ingresa la contraseña" 
                                   required>
                            <span class="input-group-text password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </span>
                        </div>
                        @error('pass')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                  
                    
                    <!-- Botón de registro -->
                    <button type="submit" class="btn btn-registro mb-3">
                        <i class="fas fa-user-plus me-2"></i> Registrar Administrador
                    </button>
                    
                    <!-- Volver al login -->
                    <a href="{{ url('/') }}" class="back-link">
                        <i class="fas fa-arrow-left me-1"></i> Volver al inicio de sesión
                    </a>
                </form>
            </div>
            
            <!-- Pie -->
            <div class="registro-footer">
                <p class="mb-0">
                    <i class="fas fa-shield-alt me-1"></i> Sistema de administración interna
                </p>
                <small class="text-muted">© {{ date('Y') }} Constructora Royal</small>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mostrar contraseña por defecto
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            passwordInput.type = 'text';
            eyeIcon.className = 'fas fa-eye-slash';
            
            // Efecto al enviar formulario
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const btn = this.querySelector('.btn-registro');
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Registrando...';
                btn.disabled = true;
            });
        });
        
        // Función para mostrar/ocultar contraseña
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.className = 'fas fa-eye-slash';
            } else {
                passwordInput.type = 'password';
                eyeIcon.className = 'fas fa-eye';
            }
        }
    </script>
</body>
</html>