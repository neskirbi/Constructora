<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Orden de Compra #{{ $compra->numeracion }}</title>
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
        .badge-pago {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 11px;
        }
        .badge-efectivo {
            background: #d4edda;
            color: #155724;
        }
        .badge-transferencia {
            background: #cce5ff;
            color: #004085;
        }
        .badge-pendiente {
            background: #fff3cd;
            color: #856404;
        }
        .badge-aprobado {
            background: #d4edda;
            color: #155724;
        }
        .badge-rechazado {
            background: #f8d7da;
            color: #721c24;
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
        table .text-right {
            text-align: right;
        }
        .totales {
            margin-top: 15px;
            text-align: right;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        .totales .row {
            display: flex;
            justify-content: flex-end;
            gap: 30px;
            padding: 3px 0;
        }
        .totales .label {
            font-weight: normal;
            color: #555;
        }
        .totales .value {
            font-weight: bold;
            min-width: 100px;
            text-align: right;
        }
        .totales .total {
            font-size: 16px;
            color: #1e3c72;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #888;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        /* Tabla de firmas */
        .firmas-table {
            width: 100%;
            margin-top: 40px;
            border-collapse: collapse;
        }
        .firmas-table td {
            text-align: center;
            padding: 0 20px;
            vertical-align: top;
        }
        .firmas-table .firma-linea {
            border-top: 1px solid #333;
            padding-top: 8px;
            margin-top: 30px;
            width: 100%;
        }
        .firmas-table .firma-label {
            font-size: 11px;
            font-weight: bold;
            margin-top: 5px;
        }
        .firmas-table .firma-sub {
            font-size: 9px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ORDEN DE COMPRA</h1>
        <div class="folio">
            {{ $prefijo }}{{ $compra->numeracion }}
        </div>
        <div class="fecha">
            Fecha: {{ \Carbon\Carbon::parse($compra->created_at)->format('d/m/Y H:i') }}
            &nbsp;|&nbsp;
            Método de Pago:
            <span class="badge-pago {{ strtolower($compra->metodo_pago) == 'efectivo' ? 'badge-efectivo' : 'badge-transferencia' }}">
                {{ $compra->metodo_pago ?? 'No definido' }}
            </span>
            &nbsp;|&nbsp;
            Estatus:
            <span class="badge-pago 
                @if($compra->verificado == 1) badge-pendiente
                @elseif($compra->verificado == 2) badge-aprobado
                @elseif($compra->verificado == 0) badge-rechazado
                @endif
            ">
                @if($compra->verificado == 1) Pendiente
                @elseif($compra->verificado == 2) Aprobado
                @elseif($compra->verificado == 0) Rechazado
                @else Sin definir
                @endif
            </span>
        </div>
    </div>

    <div class="info-grid">
        <div class="item">
            <span class="label">Proveedor:</span>
            <span class="value">{{ $proveedor->nombre ?? 'N/A' }}</span>
        </div>
        <div class="item">
            <span class="label">Contrato:</span>
            <span class="value">{{ $contrato->refinterna ?? 'N/A' }}</span>
        </div>
        <div class="item">
            <span class="label">RFC:</span>
            <span class="value">{{ $proveedor->rfc ?? 'N/A' }}</span>
        </div>
        <div class="item">
            <span class="label">Empresa:</span>
            <span class="value">{{ $contrato->empresa ?? 'N/A' }}</span>
        </div>
        <div class="item">
            <span class="label">Teléfono:</span>
            <span class="value">{{ $proveedor->telefono ?? 'N/A' }}</span>
        </div>
        <div class="item">
            <span class="label">Frente:</span>
            <span class="value">{{ $contrato->frente ?? 'N/A' }}</span>
        </div>
        <div class="item" style="grid-column: span 2;">
            <span class="label">Dirección:</span>
            <span class="value">{{ $contrato->lugar ?? $contrato->calle_numero ?? 'N/A' }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%;">Clave</th>
                <th style="width: 35%;">Descripción</th>
                <th style="width: 10%;">Unidad</th>
                <th style="width: 10%;" class="text-right">Cantidad</th>
                <th style="width: 15%;" class="text-right">Precio Unit.</th>
                <th style="width: 10%;" class="text-right">Dto. %</th>
                <th style="width: 10%;" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalles as $detalle)
            <tr>
                <td>{{ $detalle->clave }}</td>
                <td>{{ $detalle->descripcion }}</td>
                <td>{{ $detalle->unidades }}</td>
                <td class="text-right">{{ number_format($detalle->cantidad, 2) }}</td>
                <td class="text-right">${{ number_format($detalle->ult_costo, 2) }}</td>
                <td class="text-right">{{ number_format($detalle->descuento_porcentaje, 2) }}%</td>
                <td class="text-right">${{ number_format(($detalle->cantidad * $detalle->ult_costo) - $detalle->descuento_monto, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totales">
        <div class="row">
            <span class="label">Subtotal:</span>
            <span class="value">${{ number_format($compra->costo_operado + $compra->iva, 2) }}</span>
        </div>
        <div class="row">
            <span class="label">Descuentos:</span>
            <span class="value">$0.00</span>
        </div>
        <div class="row">
            <span class="label">IVA (16%):</span>
            <span class="value">${{ number_format($compra->iva, 2) }}</span>
        </div>
        <div class="row total">
            <span class="label"><strong>TOTAL:</strong></span>
            <span class="value"><strong>${{ number_format($compra->total, 2) }}</strong></span>
        </div>
    </div>

    <!-- Tabla de firmas en columnas -->
    <table class="firmas-table">
        <tr>
            <td>
                <div class="firma-label">Proveedor</div>
                <div class="firma-sub">Nombre y Firma</div>
                <div class="firma-linea">_________________________</div>
            </td>
            <td>
                <div class="firma-label">Compras</div>
                <div class="firma-sub">Autorización</div>
                <div class="firma-linea">_________________________</div>
            </td>
            <td>
                <div class="firma-label">Gerencia de Obras</div>
                <div class="firma-sub">Vo.Bo.</div>
                <div class="firma-linea">_________________________</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        <p>Este documento es una orden de compra válida. Favor de entregar los materiales en la dirección indicada.</p>
        <p>Orden generada el {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>