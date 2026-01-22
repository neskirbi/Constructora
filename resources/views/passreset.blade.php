<!DOCTYPE html>
<html lang="en">
<head>
    @include('header')
    <title>Restaurar</title>
    <style>
        .password-match {
            color: green;
            font-size: 12px;
            margin-top: 5px;
        }
        .password-mismatch {
            color: red;
            font-size: 12px;
            margin-top: 5px;
        }
        .error-message {
            color: #d9534f;
            background-color: #f2dede;
            border: 1px solid #ebccd1;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .alert-info {
            background-color: #e7f3fe;
            color: #31708f;
            border: 1px solid #bce8f1;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="row">
        &nbsp;
    </div>
    <div class="row">
        &nbsp;
    </div>
    <div class="row">
        &nbsp;
    </div>
    <div class="row">
        <!-- left column -->
        <div class="col-md-3">
        </div>
        <div class="col-md-6">
            <!-- jquery validation -->
            <div class="card card-primary">
                <div class="card-header">
                <h3 class="card-title">Cambiar Contraseña</h3>
                </div>
                
                <div class="alert-info">
                    Has iniciado sesión con una contraseña temporal. Por favor, establece una nueva contraseña.
                </div>
                
                @if(session('error'))
                    <div class="error-message">
                        {{ session('error') }}
                    </div>
                @endif
                
                <!-- form start -->
                <form action="{{ url('update-password') }}" method="post" onsubmit="return validateForm()">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">
                    
                    <div class="card-body">
                       
                        <div class="form-group">
                            <label for="pass">Nueva Contraseña</label>
                            <input required autocomplete="false" onkeyup="validatePasswordMatch();" type="password" class="form-control" id="pass" name="newPassword" minlength="4" maxlength="8" placeholder="Contraseña (4-8 caracteres)">
                        </div>

                        <div class="form-group">
                            <label for="passconfirm">Confirmar Contraseña</label>
                            <input required autocomplete="false" onkeyup="validatePasswordMatch();" type="password" class="form-control" id="pass2" name="newPassword_confirmation" minlength="4" maxlength="8" placeholder="Confirmar Contraseña">
                            <div id="passwordMatch"></div>
                        </div>
                       
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Guardar</button>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
        <div class="col-md-3">
        </div>
    </div>
    
    <script>
        function validatePasswordMatch() {
            const password = document.getElementById('pass').value;
            const confirmPassword = document.getElementById('pass2').value;
            const matchText = document.getElementById('passwordMatch');
            const submitBtn = document.getElementById('submitBtn');
            
            // Validar longitud mínima
            if (password.length < 4 || confirmPassword.length < 4) {
                matchText.textContent = 'La contraseña debe tener al menos 4 caracteres';
                matchText.className = 'password-mismatch';
                submitBtn.disabled = true;
                return;
            }
            
            // Validar longitud máxima
            if (password.length > 8 || confirmPassword.length > 8) {
                matchText.textContent = 'La contraseña no debe exceder 8 caracteres';
                matchText.className = 'password-mismatch';
                submitBtn.disabled = true;
                return;
            }
            
            if (confirmPassword.length === 0) {
                matchText.textContent = '';
                matchText.className = '';
                submitBtn.disabled = true;
                return;
            }
            
            if (password === confirmPassword) {
                matchText.textContent = '✓ Las contraseñas coinciden';
                matchText.className = 'password-match';
                submitBtn.disabled = false;
            } else {
                matchText.textContent = '✗ Las contraseñas no coinciden';
                matchText.className = 'password-mismatch';
                submitBtn.disabled = true;
            }
        }
        
        function validateForm() {
            const password = document.getElementById('pass').value;
            const confirmPassword = document.getElementById('pass2').value;
            
            // Validaciones básicas
            if (password.length < 4 || password.length > 8) {
                alert('La contraseña debe tener entre 4 y 8 caracteres.');
                return false;
            }
            
            if (password !== confirmPassword) {
                alert('Las contraseñas no coinciden.');
                return false;
            }
            
            return true;
        }
    </script>
</body>
</html>