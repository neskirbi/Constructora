<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')

    
    <!-- Estilos personalizados -->
    <style>
       
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
                
                
              
                
            </div>
        </main>
    </div>

    @include('footer')
</body>
</html>