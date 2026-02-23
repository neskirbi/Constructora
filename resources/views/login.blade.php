<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Login</title>
    <style>
        :root {
            --primary-blue: #0d1b48;
            --light-blue: #3949ab;
            --accent-gold: #ffb74d;
        }
        
        body {
            background: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            max-width: 400px;
            width: 100%;
        }
        
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        
        .login-header {
            background: var(--primary-blue);
            color: white;
            text-align: center;
            padding: 30px 20px;
        }
        
        .login-icon {
            font-size: 3.5rem;
            margin-bottom: 15px;
            color: var(--accent-gold);
        }
        
        .login-body {
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
        
        .btn-login {
            background: var(--primary-blue);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            width: 100%;
        }
        
        .btn-login:hover {
            background: var(--light-blue);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .login-footer {
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
            transition: all 0.3s;
        }
        
        .password-toggle {
            cursor: pointer;
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-left: none;
            border-radius: 0 8px 8px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 15px;
            color: #666;
            transition: all 0.3s;
        }
        
        .password-toggle:hover {
            color: var(--primary-blue);
            background: #e9ecef;
        }
        
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 5px;
        }
        
        /* Estilo para el input de contraseña cuando está junto al botón */
        .password-input-group {
            display: flex;
            width: 100%;
        }
        
        .password-input-group .form-control {
            border-right: none;
            border-radius: 8px 0 0 8px;
        }
        
        .password-input-group .form-control:focus {
            border-right: none;
            outline: none;
        }
        
        .password-input-group .form-control:focus + .password-toggle {
            border-color: var(--light-blue);
            border-left: none;
        }
    </style>
</head>
<body>
@include('toast.toasts')  
    <div class="login-container">
        <div class="login-card">
            <!-- Encabezado -->
            <div class="login-header">
                <div class="login-icon">
                    <i class="fas fa-hard-hat"></i>
                </div>
                <h2 class="h4 mb-2">{{Empresa()}}</h2>
                <p class="mb-0 opacity-75">Sistema de Administración Interna</p>
            </div>
            
            <!-- Formulario -->
            <div class="login-body">
                <form method="POST" action="{{ url('login') }}">
                    @csrf
                    
                    <!-- Usuario -->
                    <div class="mb-4">
                        <label class="form-label">Usuario</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   name="username" 
                                   placeholder="Ingresa tu usuario" 
                                   required 
                                   autofocus>
                        </div>
                        @error('username')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Contraseña con opción de ver/ocultar -->
                    <div class="mb-4">
                        <label class="form-label">Contraseña</label>
                        <div class="password-input-group">
                            <span class="input-group-text" style="border-right: none; border-radius: 8px 0 0 8px;">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" 
                                   class="form-control" 
                                   name="password" 
                                   id="password"
                                   placeholder="Ingresa tu contraseña" 
                                   required>
                            <span class="password-toggle" id="togglePassword">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </span>
                        </div>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Recordarme -->
                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Recordar sesión</label>
                    </div>
                    
                    <!-- Botón de login -->
                    <button type="submit" class="btn btn-login mb-3" id="submitBtn">
                        <i class="fas fa-sign-in-alt me-2"></i> Iniciar Sesión
                    </button>
                
                </form>
            </div>
            
            <!-- Pie -->
            <div class="login-footer">
                <p class="mb-0">
                    <i class="fas fa-shield-alt me-1"></i> Acceso restringido al personal autorizado
                </p>
                <small class="text-muted">© {{ date('Y') }} Constructora Royal</small>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Efecto al enviar formulario
            const form = document.querySelector('form');
            const submitBtn = document.getElementById('submitBtn');
            
            form.addEventListener('submit', function(e) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Verificando...';
                submitBtn.disabled = true;
            });
            
            // Mostrar/ocultar contraseña - Versión mejorada
            const passwordInput = document.getElementById('password');
            const togglePassword = document.getElementById('togglePassword');
            const toggleIcon = document.getElementById('toggleIcon');
            
            togglePassword.addEventListener('click', function() {
                // Cambiar el tipo de input
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Cambiar el icono
                if (type === 'text') {
                    toggleIcon.classList.remove('fa-eye');
                    toggleIcon.classList.add('fa-eye-slash');
                    togglePassword.setAttribute('title', 'Ocultar contraseña');
                } else {
                    toggleIcon.classList.remove('fa-eye-slash');
                    toggleIcon.classList.add('fa-eye');
                    togglePassword.setAttribute('title', 'Mostrar contraseña');
                }
            });
            
            // Opcional: Permitir también con la tecla Enter en el botón
            togglePassword.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
            
            // Hacer el botón accesible con teclado
            togglePassword.setAttribute('role', 'button');
            togglePassword.setAttribute('tabindex', '0');
            togglePassword.setAttribute('aria-label', 'Mostrar u ocultar contraseña');
            
            // Prevenir que el formulario se envíe al hacer clic en el botón de toggle
            togglePassword.addEventListener('mousedown', function(e) {
                e.preventDefault();
            });
        });
    </script>
</body>
</html>