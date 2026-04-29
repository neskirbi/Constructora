<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Editar Compra</title>
    <style>
        .required-label::after {
            content: " *";
            color: #dc3545;
        }
        .form-card {
            max-width: 1400px;
            margin: 0 auto;
        }
        .input-group-text {
            background-color: #f8f9fa;
        }
        .total-card {
            background-color: #e9ecef;
            border-radius: 8px;
            padding: 15px;
        }
        .form-control-sm, .input-group-sm .form-control {
            height: 38px;
        }
        .input-group-text {
            height: 38px;
        }
        .producto-card {
            border: 1px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0,0,0,.05);
            margin-bottom: 15px;
            height: 100%;
        }
        .producto-card .card-header {
            background-color: #f8f9fa;
            padding: 10px 15px;
            border-bottom: 1px solid #dee2e6;
        }
        .producto-card .card-header h6 {
            margin: 0;
            font-size: 0.95rem;
            color: #495057;
        }
        .producto-card .card-body {
            padding: 15px;
        }
        @media (max-width: 768px) {
            .producto-card .row > div {
                margin-bottom: 10px;
            }
        }
        .btn-remove-row {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            border-radius: 4px;
            background-color: #fff;
            border: 1px solid #dc3545;
            color: #dc3545;
            font-size: 0.9rem;
            text-decoration: none;
            cursor: pointer;
            height: 35px;
            margin: 0;
            line-height: 1;
        }
        .btn-remove-row:hover {
            background-color: #dc3545;
            color: #fff;
        }
        .btn-remove-row i {
            font-size: 0.9rem;
        }
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }
        .text-end {
            text-align: right;
        }
        .readonly-field {
            background-color: #f8f9fa;
            cursor: not-allowed;
        }
        .compra-estado {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .estado-rechazado {
            background: #fee;
            color: #dc3545;
            border: 1px solid #f5c6cb;
        }
        .estado-pendiente {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .estado-aprobado {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        /* Estilos para la sección de pago */
        .payment-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
        }
        .payment-section h6 {
            color: #495057;
            margin-bottom: 15px;
            font-weight: 600;
        }
        .payment-section .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }


        /* Hacer más grande el dropdown (contenedor de opciones) */
.select2-dropdown {
    font-size: 1rem;  /* Texto más grande */
    min-width: 400px;  /* Ancho mínimo más grande */
    width: auto !important;  /* Ancho automático */
}

/* Agrandar el campo de búsqueda dentro del dropdown */
.select2-container--default .select2-search--dropdown .select2-search__field {
    height: 45px;  /* Más alto */
    padding: 0.5rem 0.75rem;
    font-size: 1rem;  /* Texto más grande */
}

/* Agrandar las opciones del dropdown */
.select2-container--default .select2-results__option {
    padding: 12px 15px;  /* Más padding vertical y horizontal */
    font-size: 1rem;  /* Texto más grande */
    min-height: 45px;  /* Altura mínima más grande */
}

/* Agrandar el contenedor de resultados */
.select2-container--default .select2-results > .select2-results__options {
    max-height: 400px;  /* Altura máxima más grande (opcional) */
}

/* Opcional: hacer más ancho el dropdown en general */
.select2-container {
    width: 100% !important;
}

.select2-container--open .select2-dropdown {
    min-width: 350px;  /* Ancho mínimo del dropdown desplegado */
}
    </style>

    <!-- Select2 CSS -->
</head>
<body>
    <div class="main-container">
        @include('toast.toasts')
        @include('acompras.sidebar')
        
        <main class="main-content" id="mainContent">
            @include('acompras.navbar')

            <div class="content-area">
                <div class="container-fluid py-4">
                    <div class="card">
                        <div class="card-header">
                            
                            <div class="card-title">
                                <h5 class="mb-0">
                                <i class="fa fa-pencil" aria-hidden="true"></i> Editar Compra: {{ $compra->consecutivo }}
                            </h5>
                            </div>
                            <div class="card-tools">
                                @php
                                $estadoClase = '';
                                $estadoTexto = '';
                                $estadoIcono = '';
                                
                                if(isset($compra->verificado)) {
                                    if($compra->verificado == 0) {
                                        $estadoClase = 'estado-rechazado';
                                        $estadoTexto = 'Rechazado';
                                        $estadoIcono = 'fa-times-circle';
                                    } elseif($compra->verificado == 1) {
                                        $estadoClase = 'estado-pendiente';
                                        $estadoTexto = 'Pendiente';
                                        $estadoIcono = 'fa-clock';
                                    } elseif($compra->verificado == 2) {
                                        $estadoClase = 'estado-aprobado';
                                        $estadoTexto = 'Aprobado';
                                        $estadoIcono = 'fa-check-circle';
                                    }
                                }
                            @endphp
                            
                            @if(isset($compra->verificado))
                            <span class="compra-estado {{ $estadoClase }}">
                                <i class="fas {{ $estadoIcono }} me-1"></i>
                                {{ $estadoTexto }}
                            </span>
                            @endif
                            </div>
                          
                            
                            
                        </div>
                        
                        <div class="card-body">
                            <form action="{{ route('compras.update', $compra->id) }}" method="POST" id="compraForm">
                                @csrf
                                @method('PUT')
                                
                                <!-- Proveedor (solo visual, no editable en formulario) -->
                                
                                
                                <!-- Información General editable -->
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="consecutivo" class="form-label required-label">Consecutivo</label>
                                            <input type="text" 
                                                   class="form-control form-control-sm readonly-field @error('consecutivo') is-invalid @enderror" 
                                                   id="consecutivo" 
                                                   name="consecutivo" 
                                                   value="{{ old('consecutivo', $compra->consecutivo) }}"
                                                   required
                                                   readonly
                                                   noformat
                                                   style="height: 38px;">
                                            @error('consecutivo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="referencia" class="form-label">Referencia</label>
                                            <input type="text" 
                                                   class="form-control form-control-sm @error('referencia') is-invalid @enderror" 
                                                   id="referencia" 
                                                   name="referencia" 
                                                   value="{{ old('referencia', $compra->referencia) }}"
                                                   maxlength="1500"
                                                   placeholder="Folio, contrato, etc."
                                                   style="height: 38px;">
                                            @error('referencia')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="id_contrato" class="form-label required-label">Contrato</label>
                                            <select class="form-select form-select-sm contrato-select @error('id_contrato') is-invalid @enderror" 
                                                    id="id_contrato" 
                                                    name="id_contrato"
                                                    required
                                                    style="width: 100%;">
                                                <option value="">Seleccione un contrato</option>
                                                @foreach($contratos as $contrato)
                                                    <option value="{{ $contrato->id }}" {{ old('id_contrato', $compra->id_contrato) == $contrato->id ? 'selected' : '' }}>
                                                        {{ $contrato->consecutivo }} - {{ $contrato->refinterna ?? '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('id_contrato')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="id_proveedor" class="form-label required-label">Proveedor</label>
                                            <input type="text" 
                                                class="form-control form-control-sm @error('id_proveedor') is-invalid @enderror" 
                                                id="proveedor_busqueda" 
                                                placeholder="Escriba para buscar proveedor..."
                                                autocomplete="off"
                                                value="{{ $compra->proveedor_clave ?? '' }} - {{ $compra->proveedor_nombre ?? '' }}"
                                                style="height: 38px;">
                                            <input type="hidden" 
                                                id="id_proveedor" 
                                                name="id_proveedor" 
                                                value="{{ old('id_proveedor', $compra->id_proveedor ?? '') }}" 
                                                required>
                                            <div id="proveedor_resultados" class="list-group position-absolute w-100 shadow-sm" style="display: none; z-index: 1000; max-height: 300px; overflow-y: auto;"></div>
                                            @error('id_proveedor')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <button type="button" class="btn btn-success btn-block btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#nuevoProveedorModal">
                                                <i class="fas fa-plus-circle me-2"></i>
                                                Nuevo Proveedor
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                

                                <!-- Sección de Productos/Servicios editable -->
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <h6 class="fw-bold mb-3">
                                            <i class="fas fa-boxes me-2"></i>
                                            Productos / Servicios
                                        </h6>
                                    </div>
                                </div>

                                <!-- Contenedor de tarjetas de productos -->
                                <div id="productosContainer">
                                    @foreach($detalles as $index => $detalle)
                                    <div class="producto-card-wrapper mb-3" data-index="{{ $index }}">
                                        <div class="card producto-card">
                                            <div class="card-header">
                                                <h6 class="mb-0 float-start"><i class="fas fa-box me-2"></i>Producto #{{ $index + 1 }}</h6>
                                                <div class="card-tools float-end">
                                                    @if($loop->first && count($detalles) == 1)
                                                    <button type="button" class="btn-remove-row" style="display: none;">
                                                        <i class="fas fa-trash-alt me-1"></i>Eliminar
                                                    </button>
                                                    @else
                                                    <button type="button" class="btn-remove-row">
                                                        <i class="fas fa-trash-alt me-1"></i>Eliminar
                                                    </button>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3 mb-2">
                                                        <label class="form-label">Clave</label>
                                                        <input type="text" 
                                                            class="form-control form-control-sm producto-busqueda" 
                                                            placeholder="Escriba para buscar producto..."
                                                            autocomplete="off"
                                                            value="{{ $detalle->clave }}"
                                                            style="height: 38px;">
                                                        <input type="hidden" 
                                                            class="producto-id" 
                                                            name="productos[{{ $index }}][id_producto]" 
                                                            value="{{ $detalle->id_productoservicio }}">
                                                        <div class="producto-resultados list-group position-absolute w-100 shadow-sm" 
                                                            style="display: none; z-index: 1000; max-height: 200px; overflow-y: auto; background: white;"></div>
                                                    </div>
                                                    
                                                    <div class="col-md-4 mb-2">
                                                        <label class="form-label">Descripción</label>
                                                        <input type="text" 
                                                            class="form-control form-control-sm descripcion-input" 
                                                            value="{{ $detalle->descripcion }}"
                                                            readonly
                                                            placeholder="Descripción"
                                                            style="height: 38px; background-color: #f8f9fa;">
                                                    </div>
                                                    
                                                    <div class="col-md-2 mb-2">
                                                        <label class="form-label">Unidad</label>
                                                        <input type="text" 
                                                            class="form-control form-control-sm unidad-input" 
                                                            value="{{ $detalle->unidades }}"
                                                            readonly
                                                            placeholder="Unidad"
                                                            style="height: 38px; background-color: #f8f9fa;">
                                                    </div>
                                                    
                                                    <div class="col-md-3 mb-2">
                                                        <label class="form-label">Cantidad</label>
                                                        <input type="number" 
                                                            class="form-control form-control-sm cantidad-input text-end" 
                                                            name="productos[{{ $index }}][cantidad]" 
                                                            value="{{ $detalle->cantidad }}"
                                                            step="0.0000000000000001" 
                                                            min="0.01"
                                                            style="height: 38px;"
                                                            required>
                                                    </div>
                                                </div>
                                                
                                                <div class="row mt-2">
                                                    <div class="col-md-3">
                                                        <!-- Espacio vacío -->
                                                    </div>
                                                    <div class="col-md-1">
                                                        <!-- Espacio vacío -->
                                                    </div>
                                                    <div class="col-md-2">
                                                       
                                                    </div>
                                                    
                                                    <div class="col-md-3">
                                                        
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Precio Unitario</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text" style="height: 38px;">$</span>
                                                            <input type="number" 
                                                                class="form-control precio-input text-end" 
                                                                name="productos[{{ $index }}][precio]" 
                                                                value="{{ $detalle->ult_costo }}"
                                                                step="0.0000000000000001" 
                                                                style="height: 38px;"
                                                                required>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row mt-2">
                                                    <div class="col-md-3">
                                                        <!-- Espacio vacío -->
                                                    </div>
                                                    <div class="col-md-1">
                                                        <!-- Espacio vacío -->
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label">% Descuento</label>
                                                        <input type="number" 
                                                            class="form-control form-control-sm descuento-porcentaje text-end" 
                                                            name="productos[{{ $index }}][descuento_porcentaje]" 
                                                            step="0.01" 
                                                            min="0" 
                                                            max="100"
                                                            value="{{ $detalle->descuento_porcentaje ?? 0 }}"
                                                            style="height: 38px;"
                                                            noformat>
                                                    </div>
                                                    
                                                    <div class="col-md-3">
                                                        <label class="form-label">Monto Descuento</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text" style="height: 38px;">$</span>
                                                            <input type="number" 
                                                                class="form-control descuento-monto text-end" 
                                                                name="productos[{{ $index }}][descuento_monto]" 
                                                                step="0.01" 
                                                                min="0"
                                                                value="{{ $detalle->descuento_monto ?? 0 }}"
                                                                style="height: 38px;">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Subtotal</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text" style="height: 38px;">$</span>
                                                            <input type="text" 
                                                                class="form-control subtotal-text text-end" 
                                                                value="${{ number_format($detalle->cantidad * $detalle->ult_costo-$detalle->descuento_monto, 2) }}"
                                                                readonly
                                                                style="height: 38px; background-color: #f8f9fa;">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- NUEVA FILA: Fecha Entrega, Tipo Entrega y Comentarios -->
                                                <div class="row mt-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label">Fecha de Entrega</label>
                                                        <input type="date" 
                                                            class="form-control form-control-sm" 
                                                            name="productos[{{ $index }}][fecha_entrega]" 
                                                            value="{{ $detalle->fecha_entrega ?? '' }}"
                                                            style="height: 38px;">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Tipo de Entrega</label>
                                                        <select class="form-select form-select-sm" 
                                                                name="productos[{{ $index }}][tipo_entrega]" 
                                                                style="height: 38px;">
                                                            <option value="">Seleccionar</option>
                                                            <option value="recoleccion" {{ isset($detalle->tipo_entrega) && $detalle->tipo_entrega == 'recoleccion' ? 'selected' : '' }}>Recolección</option>
                                                            <option value="entrega" {{ isset($detalle->tipo_entrega) && $detalle->tipo_entrega == 'entrega' ? 'selected' : '' }}>Entrega</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label class="form-label">Comentarios</label>
                                                        <textarea class="form-control form-control-sm" 
                                                                name="productos[{{ $index }}][comentarios]" 
                                                                rows="2"
                                                                placeholder="Comentarios adicionales..."
                                                                style="resize: vertical;">{{ $detalle->comentarios ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="agregarProducto">
                                            <i class="fas fa-plus me-1"></i> Agregar Producto/Servicio
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#nuevoProductoModal">
                                            <i class="fas fa-plus-circle me-1"></i> Nuevo Producto
                                        </button>
                                    </div>
                                </div>

                                <!-- Resumen de Totales -->
                                <div class="row justify-content-end mb-4">
                                    <div class="col-md-5">
                                        <div class="total-card">
                                            <div class="row mb-2">
                                                <div class="col-6">
                                                    <p class="mb-0 fw-bold">Subtotal:</p>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <p class="mb-0" id="subtotalGlobal">${{ number_format($compra->costo_operado, 2) }}</p>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-6">
                                                    <label for="iva" class="fw-bold mb-0">IVA (%):</label>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" 
                                                               class="form-control form-control-sm text-end" 
                                                               id="iva" 
                                                               name="iva" 
                                                               value="{{ $compra->iva }}"
                                                               step="0.01"
                                                               min="0"
                                                               style="height: 38px;"
                                                               noformat>
                                                        <span class="input-group-text" style="height: 38px;">%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-6">
                                                    <p class="mb-0">IVA Calculado:</p>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <p class="mb-0" id="ivaCalculado">${{ number_format($compra->costo_operado*($compra->iva/100), 2) }}</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <p class="mb-0 fw-bold fs-6">TOTAL:</p>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <p class="mb-0 fw-bold fs-6" id="totalGlobal">${{ number_format($compra->total, 2) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Campos ocultos -->
                                <input type="hidden" name="costo_operado" id="costo_operado_hidden" value="{{ $compra->costo_operado }}">
                                <input type="hidden" name="total" id="total_hidden" value="{{ $compra->total }}">

                               
                                <!-- Sección de Método de Pago -->
                                <div class="payment-section">
                                    <h6>
                                        <i class="fas fa-credit-card me-2"></i>
                                        Información de Pago
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="metodo_pago" class="form-label required-label">Método de Pago</label>
                                                <select class="form-select form-select-sm @error('metodo_pago') is-invalid @enderror" 
                                                        id="metodo_pago" 
                                                        name="metodo_pago"
                                                        required
                                                        style="height: 38px;">
                                                    <option value="">Seleccione un método</option>
                                                    <option value="efectivo" {{ old('metodo_pago', $compra->metodo_pago ?? '') == 'efectivo' ? 'selected' : '' }}>
                                                        <i class="fas fa-money-bill-wave me-2"></i> Efectivo
                                                    </option>
                                                    <option value="transferencia" {{ old('metodo_pago', $compra->metodo_pago ?? '') == 'transferencia' ? 'selected' : '' }}>
                                                        <i class="fas fa-exchange-alt me-2"></i> Transferencia
                                                    </option>
                                                </select>
                                                @error('metodo_pago')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                <label for="empresa_pago" class="form-label">Empresa</label>
                                                <input type="text" 
                                                    class="form-control form-control-sm @error('empresa_pago') is-invalid @enderror" 
                                                    id="empresa_pago" 
                                                    name="empresa_pago" 
                                                    value="{{ old('empresa_pago', $compra->empresa_pago ?? '') }}"
                                                    placeholder="Ej: Banco XYZ, Transferencia #123, o nombre de la empresa"
                                                    maxlength="255"
                                                    style="height: 38px;">
                                                <small class="text-muted">Especifique el banco, número de transferencia o empresa relacionada</small>
                                                @error('empresa_pago')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('compras.index') }}" class="btn btn-secondary btn-sm">
                                                Cancelar
                                            </a>
                                            <button type="submit" class="btn btn-warning btn-sm">
                                                <i class="fas fa-save me-1"></i> Actualizar Compra
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @include('general.modals.modalPS')
    @include('general.modals.modalProveedores')
    
    @include('footer')
    <!-- INCLUIR SCRIPT COMÚN -->
    @include('acompras.compras.scripts')
    
    <script>
    $(document).ready(function() {
        // Actualizar contador de productos
        window.productCount = {{ count($detalles) }};

        // Agregar nueva tarjeta
        $('#agregarProducto').on('click', function() {
            console.log('listerner');
            agregarProducto();
        });

        
    });
    </script>
    @include('general.modals.scripts')
</body>
</html>