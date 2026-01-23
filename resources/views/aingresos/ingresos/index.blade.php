<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')    
    <title>{{Empresa()}} | Ingresos</title>

    
    <!-- Estilos personalizados -->
    <style>
       
    </style>
</head>
<body>
    <div class="main-container">
        @include('toast.toasts')

        @include('aingresos.sidebar')
        

        <!-- Contenido principal -->
        <main class="main-content" id="mainContent">
            @include('aingresos.navbar')

            <!-- Área de contenido -->
            <div class="content-area">
                
                
              
                
            </div>
        </main>
    </div>

    @include('footer')
</body>
</html>