<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Resultado del Reporte</title>
    <style>
        .total-row {
            background-color: #e8f4f8;
            font-weight: bold;
        }
        .table-responsive {
            max-height: 600px;
            overflow-y: auto;
        }
        .badge-status {
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
            font-size: 0.75rem;
        }
        .status-verificado { background-color: #d4edda; color: #155724; }
        .status-pendiente { background-color: #fff3cd; color: #856404; }
        .status-rechazado { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="main-container">
        @include('administradores.sidebar')
        
        <main class="main-content" id="mainContent">
            @include('administradores.navbar')

            <div class="content-area">
                <div class="container-fluid py-4">
                    <!-- Encabezado del Reporte -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">
                                        <i class="fas fa-chart-bar me-2"></i>Reporte de Ingresos
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
                        
                        <div class="card-body">
                            <!-- Resumen -->
                            <div class="row mb-4">
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
                                                        Total con IVA
                                                    </div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                        ${{ number_format($totales['total_con_iva'], 2) }}
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-calculator fa-2x text-gray-300"></i>
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
                                                        Líquido a Cobrar
                                                    </div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                        ${{ number_format($totales['liquido_cobrar'], 2) }}
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

                            <!-- Tabla de Resultados -->
                            @if($ingresos->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th># Contrato</th>
                                            <th>Obra</th>
                                            <th>Cliente</th>
                                            <th>Estimación</th>
                                            <th>Período</th>
                                            <th>Factura</th>
                                            <th>Importe Estimación</th>
                                            <th>IVA</th>
                                            <th>Total con IVA</th>
                                            <th>Líquido a Cobrar</th>
                                            <th>Fecha Cobro</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($ingresos as $ingreso)
                                        <tr>
                                            <td>{{ $ingreso->contrato_no }}</td>
                                            <td>{{ $ingreso->obra }}</td>
                                            <td>{{ $ingreso->cliente }}</td>
                                            <td>{{ $ingreso->estimacion }}</td>
                                            <td>
                                                {{ date('d/m/Y', strtotime($ingreso->periodo_del)) }} -<br>
                                                {{ date('d/m/Y', strtotime($ingreso->periodo_al)) }}
                                            </td>
                                            <td>
                                                @if($ingreso->factura)
                                                    {{ $ingreso->factura }}<br>
                                                    <small>{{ date('d/m/Y', strtotime($ingreso->fecha_factura)) }}</small>
                                                @else
                                                    <span class="text-muted">Sin factura</span>
                                                @endif
                                            </td>
                                            <td>${{ number_format($ingreso->importe_de_estimacion, 2) }}</td>
                                            <td>${{ number_format($ingreso->iva, 2) }}</td>
                                            <td>${{ number_format($ingreso->total_estimacion_con_iva, 2) }}</td>
                                            <td class="text-success">${{ number_format($ingreso->liquido_a_cobrar, 2) }}</td>
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
                                            <td colspan="6" class="text-end"><strong>TOTALES:</strong></td>
                                            <td><strong>${{ number_format($totales['importe_estimacion'], 2) }}</strong></td>
                                            <td><strong>${{ number_format($totales['iva'], 2) }}</strong></td>
                                            <td><strong>${{ number_format($totales['total_con_iva'], 2) }}</strong></td>
                                            <td><strong>${{ number_format($totales['liquido_cobrar'], 2) }}</strong></td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="fas fa-search fa-4x text-muted mb-4"></i>
                                <h4 class="text-muted">No se encontraron ingresos</h4>
                                <p class="text-muted">
                                    No hay registros de ingresos en el período seleccionado.
                                </p>
                                <a href="{{ route('reportes.ingresos') }}" class="btn btn-primary">
                                    <i class="fas fa-arrow-left me-1"></i> Volver al formulario
                                </a>
                            </div>
                            @endif

                            <!-- Botones de acción -->
                            @if($ingresos->count() > 0)
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('reportes.ingresos') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left me-1"></i> Nuevo Reporte
                                        </a>
                                        
                                        <div class="btn-group">
                                            <form action="{{ route('reportes.ingresos.exportar.excel') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id_contrato" value="{{ $idContrato }}">
                                                <input type="hidden" name="fecha_desde" value="{{ $fechaDesde }}">
                                                <input type="hidden" name="fecha_hasta" value="{{ $fechaHasta }}">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fas fa-file-excel me-1"></i> Exportar a Excel
                                                </button>
                                            </form>
                                            
                                            <!--<button type="button" class="btn btn-primary" onclick="window.print()">
                                                <i class="fas fa-print me-1"></i> Imprimir
                                            </button>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @include('footer')
</body>
</html>