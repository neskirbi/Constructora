<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Reporte de Ingresos</title>
    <style>
        .report-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }
        .btn-generar {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }
        .btn-generar:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .btn-exportar {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: white;
        }
        .btn-exportar:hover {
            background-color: #138496;
            border-color: #117a8b;
        }
        
        /* Estilos para el área de resultados */
        #resultados-container {
            display: none; /* Oculto por defecto */
            margin-top: 20px;
        }
        
        /* Estilos para la tabla del reporte (copiados de resultado.blade.php) */
        .total-row {
            background-color: #e8f4f8;
            font-weight: bold;
        }
        .badge-status {
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
            font-size: 0.75rem;
        }
        .status-verificado { background-color: #d4edda; color: #155724; }
        .status-pendiente { background-color: #fff3cd; color: #856404; }
        .status-rechazado { background-color: #f8d7da; color: #721c24; }
        
        .monto {
            text-align: right;
            font-family: monospace;
        }
        .monto-positivo {
            color: #198754;
        }
        .monto-negativo {
            color: #dc3545;
        }
        
        /* Spinner de carga */
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            display: none;
        }
        
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="main-container">
        @include('administradores.sidebar')
        
        <main class="main-content" id="mainContent">
            @include('administradores.navbar')

            <div class="content-area">
                <div class="container-fluid py-4">
                    <!-- Spinner de carga -->
                    <div class="spinner-overlay" id="loadingSpinner">
                        <div class="spinner"></div>
                    </div>
                    
                    <!-- Título -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h1 class="h3 mb-1 text-gray-800">
                                <i class="fas fa-file-alt me-2"></i>Reporte de Ingresos
                            </h1>
                            <p class="text-muted mb-0">Generar reporte por contrato y período</p>
                        </div>
                    </div>

                    <!-- Card de configuración -->
                    <div class="card shadow mb-4">
                        <div class="card-header report-card py-3">
                            <h6 class="m-0 font-weight-bold text-white">
                                <i class="fas fa-cog me-2"></i>Configurar Reporte
                            </h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('reportes.ingresos.generar') }}" method="POST" id="reportForm">
                                @csrf
                                
                                <div class="row">
                                    <!-- Selección de Contrato -->
                                    <div class="col-md-6 mb-3">
                                        <label for="id_contrato" class="form-label">
                                            <i class="fas fa-file-contract me-1"></i> Contrato
                                        </label>
                                        <select class="form-select" id="id_contrato" name="id_contrato">
                                            <option value="todos">Todos los contratos</option>
                                            @foreach($contratos as $contrato)
                                                <option value="{{ $contrato->id }}">
                                                    {{ $contrato->contrato_no }} - {{ $contrato->obra }} ({{ $contrato->cliente }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Selecciona "Todos los contratos" para reporte general</small>
                                    </div>
                                    
                                    <!-- Fecha Desde -->
                                    <div class="col-md-3 mb-3">
                                        <label for="fecha_desde" class="form-label">
                                            <i class="fas fa-calendar-alt me-1"></i> Fecha Desde
                                        </label>
                                        <input type="date" 
                                               class="form-control" 
                                               id="fecha_desde" 
                                               name="fecha_desde" 
                                               value="{{ date('Y-m-d') }}"
                                               required>
                                    </div>
                                    
                                    <!-- Fecha Hasta -->
                                    <div class="col-md-3 mb-3">
                                        <label for="fecha_hasta" class="form-label">
                                            <i class="fas fa-calendar-alt me-1"></i> Fecha Hasta
                                        </label>
                                        <input type="date" 
                                               class="form-control" 
                                               id="fecha_hasta" 
                                               name="fecha_hasta" 
                                               value="{{ date('Y-m-d') }}"
                                               required>
                                    </div>
                                </div>
                                
                                <!-- Botones -->
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-secondary" onclick="limpiarFormulario()">
                                                <i class="fas fa-broom me-1"></i> Limpiar
                                            </button>
                                            <!--<button type="button" class="btn btn-generar" onclick="generarReporte()">
                                                <i class="fas fa-search me-1"></i> Generar Reporte
                                            </button>-->
                                            <button type="button" class="btn btn-exportar" onclick="exportarExcel()">
                                                <i class="fas fa-file-excel me-1"></i> Exportar Excel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Contenedor para resultados -->
                    <div id="resultados-container" style="overflow-x:scroll; "></div>

                    <!-- Información -->
                    <div class="card shadow">
                        <div class="card-body">
                            <h6 class="card-title text-primary">
                                <i class="fas fa-info-circle me-2"></i>Instrucciones
                            </h6>
                            <br>
                            <ul class="text-muted">
                                <li>Selecciona un contrato específico o "Todos los contratos" para el reporte general</li>
                                <li>Define el período de fechas para filtrar los ingresos</li>
                                <li>El reporte mostrará todos los ingresos registrados en el período seleccionado</li>
                                <li>Los resultados se cargarán debajo del formulario</li>
                                <li>Puedes exportar el resultado a Excel (CSV)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @include('footer')
    <script>
        // Establecer fecha máxima como hoy
        document.addEventListener('DOMContentLoaded', function() {
            const hoy = new Date().toISOString().split('T')[0];
            document.getElementById('fecha_hasta').max = hoy;
            document.getElementById('fecha_desde').max = hoy;
        });
        
        function limpiarFormulario() {
            document.getElementById('id_contrato').value = 'todos';
            document.getElementById('fecha_desde').value = '';
            document.getElementById('fecha_hasta').value = '';
            limpiarReporte();
        }
        
        function limpiarReporte() {
            document.getElementById('resultados-container').innerHTML = '';
            document.getElementById('resultados-container').style.display = 'none';
        }
        
        function generarReporte() {
            // Validar formulario
            const fechaDesde = document.getElementById('fecha_desde').value;
            const fechaHasta = document.getElementById('fecha_hasta').value;
            
            if (!fechaDesde || !fechaHasta) {
                alert('Por favor, selecciona ambas fechas.');
                return;
            }
            
            if (new Date(fechaHasta) < new Date(fechaDesde)) {
                alert('La fecha "Hasta" no puede ser anterior a la fecha "Desde".');
                return;
            }
            
            // Mostrar spinner
            document.getElementById('loadingSpinner').style.display = 'flex';
            
            // Obtener datos del formulario
            const formData = new FormData(document.getElementById('reportForm'));
            
            // Enviar solicitud AJAX
            fetch('{{ route("reportes.ingresos.generar") }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                // Mostrar resultados
                const container = document.getElementById('resultados-container');
                container.innerHTML = data.html;
                container.style.display = 'block';
                
                // Desplazar hacia los resultados
                container.scrollIntoView({ behavior: 'smooth' });
                
                // Ocultar spinner
                document.getElementById('loadingSpinner').style.display = 'none';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al generar el reporte. Por favor, intenta nuevamente.');
                document.getElementById('loadingSpinner').style.display = 'none';
            });
        }
        
        function exportarExcel() {
            // Crear un formulario temporal para exportar a Excel
            const form = document.getElementById('reportForm');
            const tempForm = document.createElement('form');
            tempForm.method = 'POST';
            tempForm.action = '{{ route("reportes.ingresos.exportar.excel") }}';
            tempForm.target = '_blank';
            
            // Copiar los campos del formulario original
            const campos = ['id_contrato', 'fecha_desde', 'fecha_hasta'];
            campos.forEach(campo => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = campo;
                input.value = document.getElementById(campo).value;
                tempForm.appendChild(input);
            });
            
            // Añadir token CSRF
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            tempForm.appendChild(csrfToken);
            
            document.body.appendChild(tempForm);
            tempForm.submit();
            document.body.removeChild(tempForm);
        }
        
        // Permitir usar Enter en el formulario
        document.getElementById('reportForm').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                generarReporte();
            }
        });
    </script>
</body>
</html>