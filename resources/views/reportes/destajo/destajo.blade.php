<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Reporte de Destajos</title>
    
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
                    <h1 class="page-title">Reporte de Destajos</h1>
                    <p class="page-subtitle">Exportar destajos a Excel por periodo de fechas</p>
                </div>

                <div class="card">
                    <div class="info-box">
                        <p><strong>Nota:</strong> Seleccione un periodo de fechas para exportar los destajos. Puede filtrar por contrato específico o exportar todos los destajos del periodo. El archivo se abrirá en una nueva pestaña.</p>
                    </div>

                    <form action="{{ route('reportes.destajo.exportar') }}" method="POST" id="exportForm" target="_blank">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Inicio *</label>
                                <input type="date" 
                                       name="fecha_inicio" 
                                       class="form-control" 
                                       required 
                                       value="{{ date('Y-m-01') }}"
                                       max="{{ date('Y-m-d') }}">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Fin *</label>
                                <input type="date" 
                                       name="fecha_fin" 
                                       class="form-control" 
                                       required 
                                       value="{{ date('Y-m-d') }}"
                                       max="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label">Contrato (Opcional)</label>
                                <select name="contrato_id" class="form-select">
                                    <option value="">-- Todos los contratos --</option>
                                    @foreach($contratos as $contrato)
                                        <option value="{{ $contrato->id }}">
                                            {{ $contrato->refinterna }} - {{ $contrato->obra }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Deje en blanco para exportar todos los destajos del periodo</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn-exportar" id="btnExportar">
                                    <i class="fas fa-file-excel"></i> Exportar a Excel
                                </button>
                                
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i> 
                                        El archivo incluirá todos los campos del reporte de destajos y se abrirá en nueva pestaña
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
            const form = document.getElementById('exportForm');
            const btnExportar = document.getElementById('btnExportar');
            
            form.addEventListener('submit', function(e) {
                const fechaInicio = document.querySelector('input[name="fecha_inicio"]').value;
                const fechaFin = document.querySelector('input[name="fecha_fin"]').value;
                
                if (fechaInicio > fechaFin) {
                    e.preventDefault();
                    alert('La fecha de inicio no puede ser mayor a la fecha de fin');
                    return false;
                }
                
                // Solo mostrar indicador de carga sin deshabilitar el botón
                btnExportar.classList.add('loading');
                const originalText = btnExportar.innerHTML;
                btnExportar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando reporte...';
                
                // Restaurar el botón después de 3 segundos por si algo falla
                setTimeout(() => {
                    btnExportar.classList.remove('loading');
                    btnExportar.innerHTML = originalText;
                }, 3000);
                
                // El botón no se deshabilita, solo cambia temporalmente de apariencia
            });
            
            // Restaurar botón si el usuario cancela o hay error
            form.addEventListener('reset', function() {
                btnExportar.classList.remove('loading');
                btnExportar.innerHTML = '<i class="fas fa-file-excel"></i> Exportar a Excel';
            });
        });
    </script>
</body>
</html>