@if($ingresos->count() > 0)
<!-- Resumen mejorado -->
<div class="row mb-4" id="resumen-reporte">
    <div class="col-md-3">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Estimaciones
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $totales['count'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-list-ol fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Importe
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            ${{ number_format($totales['importe_estimacion'], 2) }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Importe Facturado
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            ${{ number_format($totales['importe_facturado'], 2) }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-invoice-dollar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Líquido Cobrado
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            ${{ number_format($totales['liquido_cobrado'], 2) }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Encabezado del Reporte -->
<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Resultado del Reporte de Ingresos
                </h5>
                <small class="text-white-50">
                    @if($idContrato && $idContrato != 'todos')
                        Contrato: {{ $contratoSeleccionado->contrato_no ?? 'N/A' }} | 
                        Cliente: {{ $contratoSeleccionado->cliente ?? 'N/A' }}
                    @else
                        Todos los contratos
                    @endif
                </small>
            </div>
            <div class="text-end">
                <small class="text-white-50">
                    Período: {{ date('d/m/Y', strtotime($fechaDesde)) }} - {{ date('d/m/Y', strtotime($fechaHasta)) }}
                </small>
            </div>
        </div>
    </div>
    
    <div class="card-body p-0">
        <!-- CONTENEDOR CON SCROLL HORIZONTAL -->
        <div style="width: 100%; overflow-x: auto; border: 3px solid #000; background: white;">
            <!-- Tabla con ancho fijo (se expande dentro del contenedor) -->
            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                <table class="table table-bordered table-hover mb-0" style="min-width: {{ max(1400, 14 * 120) }}px;">
                    <thead class="thead-light" style="position: sticky; top: 0; z-index: 1; background-color: #f8f9fa;">
                        <tr>
                            <th style="min-width: 120px; width: 120px;"># Contrato</th>
                            <th style="min-width: 200px; width: 200px;">Obra</th>
                            <th style="min-width: 150px; width: 150px;">Cliente</th>
                            <th style="min-width: 100px; width: 100px;">Estimación</th>
                            <th style="min-width: 120px; width: 120px;">Importe Estimación</th>
                            <th style="min-width: 150px; width: 150px;">Período</th>
                            <th style="min-width: 120px; width: 120px;">Fecha Factura</th>
                            <th style="min-width: 120px; width: 120px;">Factura</th>
                            <th style="min-width: 100px; width: 100px;">IVA</th>
                            <th style="min-width: 130px; width: 130px;">Total con IVA</th>
                            <th style="min-width: 130px; width: 130px;">Importe Facturado</th>
                            <th style="min-width: 130px; width: 130px;">Líquido a Cobrar</th>
                            <th style="min-width: 120px; width: 120px;">Fecha Cobro</th>
                            <th style="min-width: 100px; width: 100px;">Estado</th>
                            <!-- Agrega más columnas aquí cuando las necesites -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ingresos as $ingreso)
                        <tr>
                            <td>{{ $ingreso->contrato_no ?? 'N/A' }}</td>
                            <td>{{ $ingreso->obra ?? 'N/A' }}</td>
                            <td>{{ $ingreso->cliente ?? 'Sin cliente' }}</td>
                            <td>{{ $ingreso->no_estimacion ?? 'N/A' }}</td>
                            <td class="monto">${{ number_format($ingreso->importe_estimacion ?? 0, 2) }}</td>
                            <td>
                                @if($ingreso->periodo_del && $ingreso->periodo_al)
                                    {{ date('d/m/Y', strtotime($ingreso->periodo_del)) }}<br>
                                    {{ date('d/m/Y', strtotime($ingreso->periodo_al)) }}
                                @else
                                    <span class="text-muted">No especificado</span>
                                @endif
                            </td>
                            <td>
                                @if($ingreso->fecha_factura)
                                    {{ date('d/m/Y', strtotime($ingreso->fecha_factura)) }}
                                @endif
                            </td>
                            <td>
                                @if($ingreso->factura)
                                    {{ $ingreso->factura }}
                                @else
                                    <span class="text-muted">Sin factura</span>
                                @endif
                            </td>
                            
                            <td class="monto">${{ number_format($ingreso->iva ?? 0, 2) }}</td>
                            <td class="monto monto-positivo">${{ number_format($ingreso->total_estimacion_con_iva ?? 0, 2) }}</td>
                            <td class="monto">${{ number_format($ingreso->importe_facturado ?? 0, 2) }}</td>
                            <td class="monto monto-positivo">${{ number_format($ingreso->liquido_a_cobrar ?? 0, 2) }}</td>
                            <td>
                                @if($ingreso->fecha_cobro)
                                    {{ date('d/m/Y', strtotime($ingreso->fecha_cobro)) }}
                                @else
                                    <span class="text-muted">Pendiente</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusClass = 'badge-status status-pendiente';
                                    $statusText = 'Pendiente';
                                    
                                    if($ingreso->verificado == '2') {
                                        $statusClass = 'badge-status status-verificado';
                                        $statusText = 'Verificado';
                                    } elseif($ingreso->verificado == '0') {
                                        $statusClass = 'badge-status status-rechazado';
                                        $statusText = 'Rechazado';
                                    }
                                @endphp
                                <span class="{{ $statusClass }}">{{ $statusText }}</span>
                            </td>
                        </tr>
                        @endforeach
                        
                        <!-- Fila de totales -->
                        <tr class="total-row">
                            <td colspan="4" class="text-end"><strong>TOTALES:</strong></td>
                            <td class="monto"><strong>${{ number_format($totales['importe_estimacion'], 2) }}</strong></td>
                            <td colspan="3"></td>
                            <td class="monto"><strong>${{ number_format($totales['iva'], 2) }}</strong></td>
                            <td class="monto"><strong>${{ number_format($totales['total_con_iva'], 2) }}</strong></td>
                            <td class="monto"><strong>${{ number_format($totales['importe_facturado'], 2) }}</strong></td>
                            <td class="monto"><strong>${{ number_format($totales['liquido_cobrar'], 2) }}</strong></td>
                            <td colspan="2"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Indicador de scroll -->
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="alert alert-info py-2 mb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            Usa el scroll horizontal para ver todas las columnas.
                        </small>
                        <small class="text-muted">
                            <i class="fas fa-expand-alt me-1"></i>
                            <span id="info-ancho">Ancho total: {{ 14 * 120 }}px</span>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="row mt-4 p-3">
            <div class="col-md-12">
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" onclick="limpiarReporte()">
                        <i class="fas fa-times me-1"></i> Cerrar Reporte
                    </button>
                    
                    <div class="btn-group">
                        <form action="{{ route('reportes.ingresos.exportar.excel') }}" method="POST" id="exportForm">
                            @csrf
                            <input type="hidden" name="id_contrato" value="{{ $idContrato }}">
                            <input type="hidden" name="fecha_desde" value="{{ $fechaDesde }}">
                            <input type="hidden" name="fecha_hasta" value="{{ $fechaHasta }}">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-file-excel me-1"></i> Exportar a Excel
                            </button>
                        </form>
                        
                        <button type="button" class="btn btn-primary" onclick="window.print()">
                            <i class="fas fa-print me-1"></i> Imprimir
                        </button>
                        
                        <button type="button" class="btn btn-info" onclick="ajustarAnchoTabla()">
                            <i class="fas fa-arrows-alt-h me-1"></i> Auto Ajustar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="text-center py-5">
            <i class="fas fa-search fa-4x text-muted mb-4"></i>
            <h4 class="text-muted">No se encontraron ingresos</h4>
            <p class="text-muted">
                No hay registros de ingresos en el período seleccionado.
            </p>
            <button type="button" class="btn btn-primary" onclick="limpiarReporte()">
                <i class="fas fa-arrow-left me-1"></i> Volver al formulario
            </button>
        </div>
    </div>
</div>
@endif

<style>
    /* Contenedor principal - Fondo blanco para mejor contraste */
    .card-body.p-0 {
        background: white;
    }
    
    /* Estilos para la tabla con scroll */
    .table-responsive {
        position: relative;
    }
    
    .table-responsive table {
        margin-bottom: 0;
        table-layout: fixed;
    }
    
    .table-responsive thead th {
        white-space: nowrap;
        position: sticky;
        top: 0;
        background-color: #f8f9fa;
        z-index: 10;
        text-align: center;
        vertical-align: middle;
        border-bottom: 2px solid #dee2e6 !important;
        font-weight: 600;
        padding: 12px 8px;
    }
    
    .table-responsive tbody td {
        padding: 8px;
        vertical-align: middle;
        border-right: 1px solid #dee2e6;
    }
    
    .table-responsive tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }
    
    .table-responsive tbody tr:hover {
        background-color: #e9ecef;
    }
    
    .total-row {
        background-color: #e8f4f8;
        font-weight: bold;
        position: sticky;
        bottom: 0;
        z-index: 5;
        border-top: 2px solid #000 !important;
    }
    
    .badge-status {
        padding: 0.25rem 0.5rem;
        border-radius: 20px;
        font-size: 0.75rem;
        white-space: nowrap;
        display: inline-block;
        text-align: center;
        min-width: 80px;
    }
    .status-verificado { background-color: #d4edda; color: #155724; }
    .status-pendiente { background-color: #fff3cd; color: #856404; }
    .status-rechazado { background-color: #f8d7da; color: #721c24; }
    
    .monto {
        text-align: right;
        font-family: monospace;
        white-space: nowrap;
    }
    .monto-positivo {
        color: #198754;
        font-weight: 500;
    }
    .monto-negativo {
        color: #dc3545;
        font-weight: 500;
    }
    
    /* Scrollbar personalizado */
    div[style*="overflow-x: auto"]::-webkit-scrollbar {
        height: 12px;
    }
    
    div[style*="overflow-x: auto"]::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 6px;
    }
    
    div[style*="overflow-x: auto"]::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 6px;
        border: 2px solid #f1f1f1;
    }
    
    div[style*="overflow-x: auto"]::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    .table-responsive::-webkit-scrollbar {
        width: 10px;
    }
    
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 6px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb {
        background: #666;
        border-radius: 6px;
    }
</style>

<script>
    // Función para formatear números como moneda
    function formatCurrency(value) {
        return '$' + parseFloat(value).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }
    
    // Aplicar formato a todas las celdas con clase 'monto'
    document.addEventListener('DOMContentLoaded', function() {
        const montos = document.querySelectorAll('.monto');
        montos.forEach(function(monto) {
            const text = monto.textContent.trim();
            if (text && !text.startsWith('$')) {
                monto.textContent = formatCurrency(text);
            }
        });
        
        // Calcular y mostrar el ancho real de la tabla
        actualizarInfoAncho();
        
        // Ajustar cabeceras fijas
        ajustarCabecerasFijas();
    });
    
    // Función para ajustar dinámicamente el ancho de la tabla
    function ajustarAnchoTabla() {
        const tabla = document.querySelector('.table-responsive table');
        const contenedor = document.querySelector('div[style*="overflow-x: auto"]');
        
        if (!tabla || !contenedor) return;
        
        // Calcular el ancho total de la tabla
        let anchoTotal = 0;
        const columnas = tabla.querySelectorAll('th');
        
        columnas.forEach(function(columna) {
            // Obtener el ancho real de cada columna
            const estilo = window.getComputedStyle(columna);
            const ancho = parseFloat(estilo.width) || columna.offsetWidth;
            anchoTotal += ancho;
        });
        
        // Añadir un margen extra
        anchoTotal += 50;
        
        // Aplicar el nuevo ancho mínimo
        tabla.style.minWidth = anchoTotal + 'px';
        
        // Actualizar la información
        actualizarInfoAncho();
        
        // Mostrar mensaje
        alert('Tabla ajustada a ' + anchoTotal + 'px de ancho');
    }
    
    // Actualizar la información del ancho
    function actualizarInfoAncho() {
        const tabla = document.querySelector('.table-responsive table');
        const info = document.getElementById('info-ancho');
        
        if (tabla && info) {
            const ancho = tabla.offsetWidth || tabla.style.minWidth;
            info.textContent = 'Ancho total: ' + ancho;
        }
    }
    
    // Ajustar cabeceras fijas cuando hay scroll
    function ajustarCabecerasFijas() {
        const tablaContainer = document.querySelector('.table-responsive');
        const thead = tablaContainer.querySelector('thead');
        
        if (thead) {
            // Asegurar que las cabeceras tengan el mismo ancho que las columnas
            const ths = thead.querySelectorAll('th');
            const tds = tablaContainer.querySelector('tbody tr:first-child td');
            
            if (tds) {
                for (let i = 0; i < ths.length; i++) {
                    if (tds[i]) {
                        const anchoTd = tds[i].offsetWidth;
                        ths[i].style.width = anchoTd + 'px';
                        ths[i].style.minWidth = anchoTd + 'px';
                    }
                }
            }
        }
    }
    
    // Ajustar cuando se redimensiona la ventana
    window.addEventListener('resize', function() {
        ajustarCabecerasFijas();
        actualizarInfoAncho();
    });
    
    // Mostrar/ocultar columnas (ejemplo para cuando agregues más)
    function alternarColumna(numeroColumna) {
        const tabla = document.querySelector('.table-responsive table');
        const columnas = tabla.querySelectorAll('th:nth-child(' + numeroColumna + '), td:nth-child(' + numeroColumna + ')');
        
        columnas.forEach(function(columna) {
            if (columna.style.display === 'none') {
                columna.style.display = '';
            } else {
                columna.style.display = 'none';
            }
        });
        
        ajustarAnchoTabla();
    }
</script>