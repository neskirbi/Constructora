<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Reporte de Ingresos</title>
    
    <!-- Estilos personalizados -->
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
            text-decoration: none;
        }
        
        .btn-exportar:hover {
            background: linear-gradient(135deg, #27ae60, #219653);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
        }
        
        .btn-exportar i {
            font-size: 18px;
        }
        
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #3498db;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .info-box p {
            margin: 0;
            color: #555;
            font-size: 14px;
        }
        
        .loading {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        /* Botón a la izquierda */
        .button-container {
            display: flex;
            justify-content: flex-start;
        }
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
                <div class="page-header">
                    <h1 class="page-title">Reporte de Ingresos</h1>
                    <p class="page-subtitle">Exportar ingresos a Excel por contrato y período</p>
                </div>

                <div class="card">
                    <div class="info-box">
                        <p><strong>Nota:</strong> Seleccione un contrato y período de fechas para exportar los ingresos a Excel. El archivo se abrirá en una nueva pestaña.</p>
                    </div>

                    <form action="{{ route('reportes.ingresos.exportar.excel') }}" method="POST" id="exportForm" target="_blank">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Contrato *</label>
                                <select name="id_contrato" id="id_contrato" class="form-select" required>
                                    <option value="todos">-- Todos los contratos --</option>
                                    @foreach($contratos as $contrato)
                                        <option value="{{ $contrato->id }}">
                                            {{ $contrato->consecutivo }} - {{ $contrato->obra }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Selecciona "Todos los contratos" para reporte general</small>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Fecha Desde *</label>
                                <input type="date" 
                                       name="fecha_desde" 
                                       id="fecha_desde"
                                       class="form-control" 
                                       required 
                                       value="{{ date('Y-m-01') }}"
                                       max="{{ date('Y-m-d') }}">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Fecha Hasta *</label>
                                <input type="date" 
                                       name="fecha_hasta" 
                                       id="fecha_hasta"
                                       class="form-control" 
                                       required 
                                       value="{{ date('Y-m-d') }}"
                                       max="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="button-container">
                                    <button type="submit" class="btn-exportar" id="btnExportar">
                                        <i class="fas fa-file-excel"></i> Exportar a Excel
                                    </button>
                                </div>
                                
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i> 
                                        El archivo se generará y se abrirá en una nueva pestaña
                                    </small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Información adicional -->
                <div class="card mt-4">
                    <div class="info-box" style="border-left-color: #2ecc71;">
                        <h6 class="mb-2" style="color: #27ae60; font-weight: 600;">
                            <i class="fas fa-info-circle me-2"></i>Instrucciones
                        </h6>
                        <ul class="mb-0" style="color: #555; font-size: 14px;">
                            <li>Selecciona un contrato específico o "Todos los contratos" para el reporte general</li>
                            <li>Define el período de fechas para filtrar los ingresos</li>
                            <li>El archivo Excel incluirá todos los ingresos registrados en el período seleccionado</li>
                            <li>El reporte se abrirá automáticamente en una nueva pestaña</li>
                        </ul>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @include('footer')
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hoy = new Date().toISOString().split('T')[0];
            document.getElementById('fecha_hasta').max = hoy;
            document.getElementById('fecha_desde').max = hoy;
            
            const form = document.getElementById('exportForm');
            const btnExportar = document.getElementById('btnExportar');
            
            // Validación de fechas en tiempo real
            document.getElementById('fecha_desde').addEventListener('change', function() {
                document.getElementById('fecha_hasta').min = this.value;
            });
            
            form.addEventListener('submit', function(e) {
                const fechaDesde = document.getElementById('fecha_desde').value;
                const fechaHasta = document.getElementById('fecha_hasta').value;
                
                if (!fechaDesde || !fechaHasta) {
                    e.preventDefault();
                    alert('Por favor, selecciona ambas fechas.');
                    return false;
                }
                
                if (new Date(fechaHasta) < new Date(fechaDesde)) {
                    e.preventDefault();
                    alert('La fecha "Hasta" no puede ser anterior a la fecha "Desde".');
                    return false;
                }
                
                // Mostrar indicador de carga
                btnExportar.classList.add('loading');
                const originalText = btnExportar.innerHTML;
                btnExportar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando Excel...';
                
                // Restaurar el botón después de 3 segundos
                setTimeout(() => {
                    btnExportar.classList.remove('loading');
                    btnExportar.innerHTML = originalText;
                }, 3000);
            });
        });
    </script>
</body>
</html>