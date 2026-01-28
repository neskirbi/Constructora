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
    </style>
</head>
<body>
    <div class="main-container">
        @include('administradores.sidebar')
        
        <main class="main-content" id="mainContent">
            @include('administradores.navbar')

            <div class="content-area">
                <div class="container-fluid py-4">
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
                                               value="{{ date('Y-m-01') }}"
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
                                               value="{{ date('Y-m-t') }}"
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
                                            <button type="submit" class="btn btn-generar">
                                                <i class="fas fa-search me-1"></i> Generar Reporte
                                            </button>
                                            <button type="button" class="btn btn-exportar" onclick="exportarExcel()">
                                                <i class="fas fa-file-excel me-1"></i> Exportar Excel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

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
                                <li>Puedes exportar el resultado a Excel (CSV)</li> <!-- Cambiado de PDF a Excel -->
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
    </script>
</body>
</html>