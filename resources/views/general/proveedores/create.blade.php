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
                            @include('general.forms.form_proveedor')
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    

    @include('footer')
    
   
</body>
</html>