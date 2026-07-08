<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Reporte de Proveedores</title>
    
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
                    <h1 class="page-title">Reporte de Proveedores</h1>
                    <p class="page-subtitle">Exportar catálogo de proveedores a Excel</p>
                </div>

                <div class="card">
                    <div class="info-box">
                        <p><strong>Nota:</strong> Seleccione el tipo de filtro para exportar los proveedores. 
                           El reporte incluirá: Clave, Nombre, Teléfono, Clasificación, Especialidad, Calle, Estatus, Fecha de Creación y Fecha de Modificación.</p>
                    </div>

                    <form action="{{ route('reportes.proveedores.exportar') }}" method="POST" id="exportForm" target="_blank">
                        @csrf
                        
                        <div class="radio-group">
                            <div class="radio-option">
                                <input type="radio" name="filtro" value="todos" id="todos" checked>
                                <label for="todos">Todos los registros</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" name="filtro" value="rango_clave" id="rango_clave">
                                <label for="rango_clave">Rango de claves</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" name="filtro" value="busqueda" id="busqueda">
                                <label for="busqueda">Buscar por nombre o clave</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" name="filtro" value="estatus" id="estatus">
                                <label for="estatus">Por estatus</label>
                            </div>
                        </div>

                        <!-- Filtro: Rango de claves -->
                        <div id="grupo_rango_clave" class="filtro-grupo">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Clave desde</label>
                                    <input type="number" noformat step="0.01" name="clave_desde" class="form-control" placeholder="Ej: 1">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Clave hasta</label>
                                    <input type="number" noformat step="0.01" name="clave_hasta" class="form-control" placeholder="Ej: 200">
                                </div>
                            </div>
                            <small class="text-muted d-block mt-1">
                                <i class="fas fa-info-circle"></i> Rango numérico (ej: 1 a 200)
                            </small>
                        </div>

                        <!-- Filtro: Búsqueda por texto -->
                        <div id="grupo_busqueda" class="filtro-grupo">
                            <label class="form-label">Buscar por nombre o clave</label>
                            <input type="text" name="buscar_texto" class="form-control" placeholder="Ingrese nombre o clave a buscar...">
                            <small class="text-muted d-block mt-1">
                                <i class="fas fa-info-circle"></i> Busca coincidencias en nombre o clave
                            </small>
                        </div>

                        <!-- Filtro: Estatus -->
                        <div id="grupo_estatus" class="filtro-grupo">
                            <label class="form-label">Seleccionar estatus</label>
                            <select name="estatus" class="form-select">
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
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
                rango_clave: document.getElementById('grupo_rango_clave'),
                busqueda: document.getElementById('grupo_busqueda'),
                estatus: document.getElementById('grupo_estatus')
            };
            
            function mostrarFiltro() {
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
                
                if (filtro === 'rango_clave') {
                    const claveDesde = document.querySelector('input[name="clave_desde"]').value.trim();
                    const claveHasta = document.querySelector('input[name="clave_hasta"]').value.trim();
                    if (!claveDesde || !claveHasta) {
                        e.preventDefault();
                        alert('Ingrese el rango de claves (desde y hasta)');
                        return false;
                    }
                    if (parseFloat(claveDesde) > parseFloat(claveHasta)) {
                        e.preventDefault();
                        alert('La clave "desde" debe ser menor o igual que la clave "hasta"');
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