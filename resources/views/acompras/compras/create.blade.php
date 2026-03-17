<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Nueva Compra</title>
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
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-plus-circle me-2 text-success"></i>
                                    Nueva Compra
                                </h5>
                                <a href="{{ route('compras.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-arrow-left me-1"></i> Regresar
                                </a>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <form action="{{ route('compras.store') }}" method="POST" id="compraForm">
                                @csrf
                                
                                <!-- Información General -->
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="consecutivo" class="form-label required-label">Consecutivo</label>
                                            <input type="number" 
                                                   class="form-control form-control-sm @error('consecutivo') is-invalid @enderror" 
                                                   id="consecutivo" 
                                                   name="consecutivo" 
                                                   value="{{ old('consecutivo', $siguienteConsecutivo) }}"
                                                   min="1"
                                                   required
                                                   noformat
                                                   style="height: 38px;">
                                            @error('consecutivo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="referencia" class="form-label required-label">Referencia</label>
                                            <input type="text" 
                                                   class="form-control form-control-sm @error('referencia') is-invalid @enderror" 
                                                   id="referencia" 
                                                   name="referencia" 
                                                   value="{{ old('referencia') }}"
                                                   maxlength="1500"
                                                   placeholder="Folio, contrato, etc."
                                                   required
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
                                                    <option value="{{ $contrato->id }}" {{ old('id_contrato') == $contrato->id ? 'selected' : '' }}>
                                                        {{ $contrato->contrato_no }} - {{ $contrato->obra ?? '' }}
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
                                                    <option value="{{ $proveedor->id }}" {{ old('id_proveedor') == $proveedor->id ? 'selected' : '' }}>
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

                                <!-- Sección de Productos/Servicios -->
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
                                    <!-- Las tarjetas se generarán dinámicamente desde el script -->
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
                                                    <p class="mb-0" id="subtotalGlobal">$0.00</p>
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
                                                               value="{{ old('iva', 16) }}"
                                                               step="0.01"
                                                               min="0"
                                                               noformat
                                                               style="height: 38px;">
                                                        <span class="input-group-text" style="height: 38px;">%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-6">
                                                    <p class="mb-0">IVA Calculado:</p>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <p class="mb-0" id="ivaCalculado">$0.00</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <p class="mb-0 fw-bold fs-6">TOTAL:</p>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <p class="mb-0 fw-bold fs-6" id="totalGlobal">$0.00</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Campos ocultos para enviar los totales calculados -->
                                <input type="hidden" name="costo_operado" id="costo_operado_hidden">
                                <input type="hidden" name="total" id="total_hidden">

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('compras.index') }}" class="btn btn-secondary btn-sm">
                                                Cancelar
                                            </a>
                                            <button type="submit" class="btn btn-success btn-sm" id="btnSubmit">
                                                <i class="fas fa-save me-1"></i> Guardar Compra
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
        // Inicializar con una tarjeta
        $('#productosContainer').append(crearTarjetaProducto(0));
        window.productCount = 1;
        
        // Reinicializar Select2
        initSelect2();

        // Agregar nueva tarjeta
        $('#agregarProducto').on('click', function() {
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