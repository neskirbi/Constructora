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
        .select2-container--default .select2-selection--single {
            height: 38px !important;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px !important;
            padding-left: 12px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
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
        .compra-header {
            padding: 15px 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .compra-consecutivo {
            font-size: 1.8rem;
            font-weight: 700;
            color: #0d6efd;
            margin: 0;
        }
        .compra-proveedor {
            font-size: 1.1rem;
            color: #495057;
            font-weight: 500;
            margin-bottom: 20px;
            padding: 10px 15px;
            background: #e7f1ff;
            border-radius: 8px;
            border-left: 4px solid #0d6efd;
        }
        .compra-proveedor i {
            color: #0d6efd;
            margin-right: 8px;
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
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
    <div class="main-container">
        @include('toast.toasts')
        @include('acompras.sidebar')
        
        <main class="main-content" id="mainContent">
            @include('acompras.navbar')

            <div class="content-area">
                <div class="container-fluid py-4">
                    <div class="card shadow-sm form-card">
                        <div class="compra-header">
                            <div class="compra-consecutivo">
                                <i class="fas fa-hashtag me-2"></i>
                                Editar Compra #{{ $compra->consecutivo }}
                            </div>
                            
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
                        
                        <div class="card-body">
                            <form action="{{ route('compras.update', $compra->id) }}" method="POST" id="compraForm">
                                @csrf
                                @method('PUT')
                                
                                <!-- Proveedor (solo visual, no editable en formulario) -->
                                <div class="compra-proveedor">
                                    <i class="fas fa-building"></i>
                                    <strong>Proveedor:</strong> {{ $compra->proveedor_nombre ?? 'Proveedor no encontrado' }}
                                    @if(isset($compra->proveedor_clave))
                                    <span class="text-muted ms-2">({{ $compra->proveedor_clave }})</span>
                                    @endif
                                </div>
                                
                                <!-- Información General editable -->
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="consecutivo" class="form-label required-label">Consecutivo</label>
                                            <input type="number" 
                                                   class="form-control form-control-sm readonly-field @error('consecutivo') is-invalid @enderror" 
                                                   id="consecutivo" 
                                                   name="consecutivo" 
                                                   value="{{ old('consecutivo', $compra->consecutivo) }}"
                                                   min="1"
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
                                            <select class="form-select form-select-sm @error('id_contrato') is-invalid @enderror" 
                                                    id="id_contrato" 
                                                    name="id_contrato"
                                                    required
                                                    style="height: 38px;">
                                                <option value="">Seleccione un contrato</option>
                                                @foreach($contratos as $contrato)
                                                    <option value="{{ $contrato->id }}" {{ old('id_contrato', $compra->id_contrato) == $contrato->id ? 'selected' : '' }}>
                                                        {{ $contrato->consecutivo }} - {{ $contrato->orefinternabra ?? '' }}
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
                                            <select class="form-select form-select-sm @error('id_proveedor') is-invalid @enderror" 
                                                    id="id_proveedor" 
                                                    name="id_proveedor"
                                                    required
                                                    style="height: 38px;">
                                                <option value="">Seleccione un proveedor</option>
                                                @foreach($proveedores as $proveedor)
                                                    <option value="{{ $proveedor->id }}" {{ old('id_proveedor', $compra->id_proveedor) == $proveedor->id ? 'selected' : '' }}>
                                                        {{ $proveedor->clave }} - {{ $proveedor->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('id_proveedor')
                                                <div class="invalid-feedback">{{ $message }}</div>
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
                                                        <select class="form-select form-select-sm producto-select" 
                                                                name="productos[{{ $index }}][id_producto]" 
                                                                style="width: 100%; height: 38px;"
                                                                required>
                                                            <option value="">Seleccionar</option>
                                                            @foreach($productos as $producto)
                                                                <option value="{{ $producto->id }}" 
                                                                        data-clave="{{ $producto->clave }}"
                                                                        data-descripcion="{{ $producto->descripcion }}"
                                                                        data-unidad="{{ $producto->unidades }}"
                                                                        data-precio="{{ $producto->ult_costo }}"
                                                                        {{ $detalle->id_productoservicio == $producto->id ? 'selected' : '' }}>
                                                                    {{ $producto->clave }}
                                                                </option>
                                                            @endforeach
                                                        </select>
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
                                                    <div class="col-md-3">
                                                        <!-- Espacio vacío -->
                                                    </div>
                                                    <div class="col-md-3">
                                                        <!-- Espacio vacío -->
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Subtotal</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text" style="height: 38px;">$</span>
                                                            <input type="text" 
                                                                class="form-control subtotal-text text-end" 
                                                                value="${{ number_format($detalle->cantidad * $detalle->ult_costo, 2) }}"
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
                                                               value="{{ old('iva', $compra->iva ? round(($compra->iva / $compra->costo_operado) * 100, 2) : 16) }}"
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
                                                    <p class="mb-0" id="ivaCalculado">${{ number_format($compra->iva, 2) }}</p>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

        // Validación del formulario
        $('#compraForm').on('submit', function(event) {
            let hasProducts = false;
            
            $('.producto-select').each(function() {
                if ($(this).val()) {
                    hasProducts = true;
                }
            });
            
            if (!hasProducts) {
                event.preventDefault();
                alert('Debe agregar al menos un producto o servicio');
                return false;
            }
            
            if (!this.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            $(this).addClass('was-validated');
        });
    });
    </script>
    @include('general.modals.scripts')
</body>
</html>