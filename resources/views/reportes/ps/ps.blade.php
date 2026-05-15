<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Reporte de Productos y Servicios</title>
    
    <style>
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }
        
        .form-control, .form-select {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px 15px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
            outline: none;
        }
        
        .btn-exportar {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-exportar:hover {
            background: linear-gradient(135deg, #27ae60, #219653);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
        }
        
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #3498db;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .radio-group {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .radio-option input[type="radio"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        .radio-option label {
            margin: 0;
            cursor: pointer;
            font-weight: normal;
        }
        
        .filtro-grupo {
            display: none;
            margin-top: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        
        .filtro-grupo.activo {
            display: block;
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
                <div class="page-header">
                    <h1 class="page-title">Reporte de Productos y Servicios</h1>
                    <p class="page-subtitle">Exportar catálogo de productos y servicios a Excel</p>
                </div>

                <div class="card">
                    <div class="info-box">
                        <p><strong>Nota:</strong> Seleccione el tipo de filtro para exportar los productos y servicios. 
                           El reporte incluirá: Clave, Descripción, Unidades, Último Costo, Fecha de Creación y Fecha de Modificación.
                           Los datos se ordenarán por clave.</p>
                    </div>

                    <form action="{{ route('reportes.ps.exportar') }}" method="POST" id="exportForm" target="_blank">
                        @csrf
                        
                        <div class="radio-group">
                            <div class="radio-option">
                                <input type="radio" name="filtro" value="todos" id="todos" checked>
                                <label for="todos">Todos los registros</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" name="filtro" value="clave_unica" id="clave_unica">
                                <label for="clave_unica">Clave específica</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" name="filtro" value="rango_clave" id="rango_clave">
                                <label for="rango_clave">Rango de claves</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" name="filtro" value="busqueda" id="busqueda">
                                <label for="busqueda">Búsqueda por texto</label>
                            </div>
                        </div>

                        <!-- Filtro: Clave única -->
                        <div id="grupo_clave_unica" class="filtro-grupo">
                            <label class="form-label">Clave del producto/servicio</label>
                            <input type="text" name="clave_unica" class="form-control" placeholder="Ej: MAT-001">
                        </div>

                        <!-- Filtro: Rango de claves -->
                        <div id="grupo_rango_clave" class="filtro-grupo">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Clave desde</label>
                                    <input type="text" name="clave_desde" class="form-control" placeholder="Ej: MAT-001">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Clave hasta</label>
                                    <input type="text" name="clave_hasta" class="form-control" placeholder="Ej: MAT-999">
                                </div>
                            </div>
                        </div>

                        <!-- Filtro: Búsqueda por texto -->
                        <div id="grupo_busqueda" class="filtro-grupo">
                            <label class="form-label">Buscar por clave o descripción</label>
                            <input type="text" name="buscar_texto" class="form-control" placeholder="Ingrese texto a buscar...">
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <button type="submit" class="btn-exportar" id="btnExportar">
                                    <i class="fas fa-file-excel"></i> Exportar a Excel
                                </button>
                                
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i> 
                                        El archivo se generará en formato Excel (.xlsx) y se abrirá en nueva pestaña
                                    </small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    @include('footer')
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radios = document.querySelectorAll('input[name="filtro"]');
            const grupos = {
                clave_unica: document.getElementById('grupo_clave_unica'),
                rango_clave: document.getElementById('grupo_rango_clave'),
                busqueda: document.getElementById('grupo_busqueda')
            };
            
            function mostrarFiltro() {
                // Ocultar todos
                Object.values(grupos).forEach(grupo => {
                    if (grupo) grupo.classList.remove('activo');
                });
                
                const seleccionado = document.querySelector('input[name="filtro"]:checked').value;
                if (grupos[seleccionado]) {
                    grupos[seleccionado].classList.add('activo');
                }
            }
            
            radios.forEach(radio => {
                radio.addEventListener('change', mostrarFiltro);
            });
            
            mostrarFiltro();
            
            const form = document.getElementById('exportForm');
            const btnExportar = document.getElementById('btnExportar');
            
            form.addEventListener('submit', function(e) {
                const filtro = document.querySelector('input[name="filtro"]:checked').value;
                
                if (filtro === 'clave_unica') {
                    const claveUnica = document.querySelector('input[name="clave_unica"]').value.trim();
                    if (!claveUnica) {
                        e.preventDefault();
                        alert('Ingrese la clave del producto/servicio');
                        return false;
                    }
                }
                
                if (filtro === 'rango_clave') {
                    const claveDesde = document.querySelector('input[name="clave_desde"]').value.trim();
                    const claveHasta = document.querySelector('input[name="clave_hasta"]').value.trim();
                    if (!claveDesde || !claveHasta) {
                        e.preventDefault();
                        alert('Ingrese el rango de claves (desde y hasta)');
                        return false;
                    }
                }
                
                if (filtro === 'busqueda') {
                    const buscarTexto = document.querySelector('input[name="buscar_texto"]').value.trim();
                    if (!buscarTexto) {
                        e.preventDefault();
                        alert('Ingrese el texto a buscar');
                        return false;
                    }
                }
                
                btnExportar.classList.add('loading');
                const originalText = btnExportar.innerHTML;
                btnExportar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando reporte...';
                
                setTimeout(() => {
                    btnExportar.classList.remove('loading');
                    btnExportar.innerHTML = originalText;
                }, 3000);
            });
        });
    </script>
</body>
</html>