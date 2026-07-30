<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Solicitud de Cotización #{{ $requisicion->consecutivo }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1e3c72;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #1e3c72;
        }
        .header .folio {
            font-size: 16px;
            margin-top: 5px;
            font-weight: bold;
        }
        .header .fecha {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px 30px;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        .info-grid .item {
            display: flex;
        }
        .info-grid .label {
            font-weight: bold;
            width: 120px;
            color: #555;
        }
        .info-grid .value {
            flex: 1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th {
            background-color: #1e3c72;
            color: white;
            padding: 10px 12px;
            text-align: left;
            font-size: 11px;
        }
        table td {
            padding: 8px 12px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 11px;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SOLICITUD DE COTIZACIÓN</h1>
        <div class="folio">
            REQUISICIÓN #{{ $requisicion->consecutivo }}
        </div>
        <div class="fecha">
            Fecha: {{ \Carbon\Carbon::parse($requisicion->created_at)->format('d/m/Y') }}
        </div>
    </div>

    <div class="info-grid">
        <div class="item">
            <span class="label">Contrato:</span>
            <span class="value">{{ $requisicion->contrato->refinterna ?? 'N/A' }}</span>
        </div>
        <div class="item">
            <span class="label">Empresa:</span>
            <span class="value">{{ $requisicion->contrato->empresa ?? 'N/A' }}</span>
        </div>
        <div class="item">
            <span class="label">Frente:</span>
            <span class="value">{{ $requisicion->contrato->frente ?? 'N/A' }}</span>
        </div>
        <div class="item">
            <span class="label">Cliente:</span>
            <span class="value">{{ $requisicion->contrato->cliente ?? 'N/A' }}</span>
        </div>
        <div class="item" style="grid-column: span 2;">
            <span class="label">Dirección de Entrega:</span>
            <span class="value">{{ $requisicion->direccion_entrega ?? $requisicion->contrato->lugar ?? 'N/A' }}</span>
        </div>
        @if($requisicion->contratista)
        <div class="item" style="grid-column: span 2;">
            <span class="label">Contratista:</span>
            <span class="value">{{ $requisicion->contratista }}</span>
        </div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%;">Clave</th>
                <th style="width: 55%;">Descripción</th>
                <th style="width: 10%;">Unidad</th>
                <th style="width: 25%;" class="text-center">Cantidad</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requisicion->detalles as $detalle)
            <tr>
                <td>{{ $detalle->clave }}</td>
                <td>{{ $detalle->descripcion }}</td>
                <td>{{ $detalle->unidad }}</td>
                <td class="text-center">{{ number_format($detalle->cantidad, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Favor de cotizar los materiales y/o servicios solicitados, indicando precios y tiempos de entrega.</p>
        <p>Documento generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>