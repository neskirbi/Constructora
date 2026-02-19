<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Nuevo Destajo</title>
    <style>
        .required-label::after {
            content: " *";
            color: #dc3545;
        }
        .form-card {
            max-width: 1200px;
            margin: 0 auto;
        }
        .input-group-text {
            background-color: #f8f9fa;
        }
        .table-productos {
            font-size: 0.9rem;
        }
        .table-productos th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .btn-remove-row {
            color: #dc3545;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-remove-row:hover {
            color: #a71d2a;
            transform: scale(1.1);
        }
        .total-card {
            background-color: #e9ecef;
            border-radius: 8px;
            padding: 15px;
        }
        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
        .clave-columna {
            width: 15%;
        }
        .descripcion-columna {
            width: 30%;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
    <div class="main-container">
        @include('toast.toasts')
        @include('adestajos.sidebar')
        
        <main class="main-content" id="mainContent">
            @include('adestajos.navbar')

            <div class="content-area">
                <div class="container-fluid py-4">
                    <div class="card shadow-sm form-card">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-plus-circle me-2 text-success"></i>
                                    Nuevo Destajo
                                </h5>
                                <a href="{{ route('destajos.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-arrow-left me-1"></i> Regresar
                                </a>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <form action="{{ route('destajos.store') }}" method="POST" id="destajoForm">
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
                                                   required>
                                            @error('consecutivo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <!-- Campo Referencia después de consecutivo -->
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="referencia" class="form-label">Referencia</label>
                                            <input type="text" 
                                                   class="form-control form-control-sm @error('referencia') is-invalid @enderror" 
                                                   id="referencia" 
                                                   name="referencia" 
                                                   value="{{ old('referencia') }}"
                                                   maxlength="1500"
                                                   placeholder="Folio, contrato, etc.">
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
                                                    required>
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
                                                    required>
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

                                <div class="table-responsive mb-3">
                                    <table class="table table-bordered table-productos" id="productosTable">
                                        <thead>
                                            <tr>
                                                <th width="15%">Clave</th>
                                                <th width="30%">Descripción</th>
                                                <th width="10%">Unidad</th>
                                                <th width="10%">Cantidad</th>
                                                <th width="12%">Precio Unitario</th>
                                                <th width="12%">Subtotal</th>
                                                <th width="11%">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="productosBody">
                                            <tr class="producto-row">
                                                <td>
                                                    <select class="form-select form-select-sm producto-select" 
                                                            name="productos[0][id_producto]" 
                                                            data-index="0"
                                                            required>
                                                        <option value="">Seleccionar</option>
                                                        @foreach($productos as $producto)
                                                            <option value="{{ $producto->id }}" 
                                                                    data-clave="{{ $producto->clave }}"
                                                                    data-descripcion="{{ $producto->descripcion }}"
                                                                    data-unidad="{{ $producto->unidades }}"
                                                                    data-precio="{{ $producto->ult_costo }}">
                                                                {{ $producto->clave }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" 
                                                           class="form-control form-control-sm descripcion-input" 
                                                           readonly
                                                           placeholder="Descripción">
                                                </td>
                                                <td>
                                                    <input type="text" 
                                                           class="form-control form-control-sm unidad-input" 
                                                           readonly
                                                           placeholder="Unidad">
                                                </td>
                                                <td>
                                                    <input type="number" 
                                                           class="form-control form-control-sm cantidad-input" 
                                                           name="productos[0][cantidad]" 
                                                           step="0.01" 
                                                           min="0.01"
                                                           value="1"
                                                           required>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">$</span>
                                                        <input type="number" 
                                                               class="form-control precio-input" 
                                                               name="productos[0][precio]" 
                                                               step="0.01" 
                                                               min="0"
                                                               required>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">$</span>
                                                        <input type="text" 
                                                               class="form-control subtotal-text" 
                                                               readonly>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <i class="fas fa-trash-alt btn-remove-row" style="display: none;"></i>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="7">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" id="agregarProducto">
                                                        <i class="fas fa-plus me-1"></i> Agregar Producto/Servicio
                                                    </button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
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
                                                               min="0">
                                                        <span class="input-group-text">%</span>
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
                                            <a href="{{ route('destajos.index') }}" class="btn btn-secondary btn-sm">
                                                Cancelar
                                            </a>
                                            <button type="submit" class="btn btn-success btn-sm" id="btnSubmit">
                                                <i class="fas fa-save me-1"></i> Guardar Destajo
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

    @include('footer')
    
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let productCount = 1;
            let productosData = @json($productos);
            
            // Inicializar Select2
            $('.producto-select').select2({
                placeholder: 'Seleccionar producto/servicio',
                allowClear: true,
                width: '100%',
                templateResult: formatOption,
                templateSelection: formatOption
            });

            function formatOption(option) {
                if (!option.id) return option.text;
                return $('<span>' + option.text + '</span>');
            }

            // Función para actualizar subtotal de una fila
            function actualizarSubtotalFila(row) {
                const cantidad = parseFloat(row.querySelector('.cantidad-input').value) || 0;
                const precio = parseFloat(row.querySelector('.precio-input').value) || 0;
                const subtotal = cantidad * precio;
                row.querySelector('.subtotal-text').value = '$' + subtotal.toFixed(2);
                row.setAttribute('data-subtotal', subtotal);
                calcularTotalesGlobales();
            }

            // Función para calcular todos los totales
            function calcularTotalesGlobales() {
                let subtotalGlobal = 0;
                document.querySelectorAll('.producto-row').forEach(row => {
                    subtotalGlobal += parseFloat(row.getAttribute('data-subtotal') || 0);
                });

                const ivaPorcentaje = parseFloat(document.getElementById('iva').value) || 0;
                const ivaCalculado = subtotalGlobal * (ivaPorcentaje / 100);
                const totalGlobal = subtotalGlobal + ivaCalculado;

                document.getElementById('subtotalGlobal').textContent = '$' + subtotalGlobal.toFixed(2);
                document.getElementById('ivaCalculado').textContent = '$' + ivaCalculado.toFixed(2);
                document.getElementById('totalGlobal').textContent = '$' + totalGlobal.toFixed(2);
                
                // Actualizar campos ocultos
                document.getElementById('costo_operado_hidden').value = subtotalGlobal.toFixed(2);
                document.getElementById('total_hidden').value = totalGlobal.toFixed(2);
            }

            // Evento cuando se selecciona un producto
            $(document).on('select2:select', '.producto-select', function(e) {
                const row = $(this).closest('tr')[0];
                const selectedOption = e.params.data.element;
                
                if (selectedOption) {
                    const clave = selectedOption.getAttribute('data-clave');
                    const descripcion = selectedOption.getAttribute('data-descripcion');
                    const unidad = selectedOption.getAttribute('data-unidad');
                    const precio = selectedOption.getAttribute('data-precio');
                    
                    // En la fila, la clave ya se muestra en el select, pero podemos actualizar la descripción
                    row.querySelector('.descripcion-input').value = descripcion || '';
                    row.querySelector('.unidad-input').value = unidad || '';
                    row.querySelector('.precio-input').value = precio || 0;
                    
                    actualizarSubtotalFila(row);
                }
            });

            // Eventos para cambios en cantidad y precio
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('cantidad-input') || 
                    e.target.classList.contains('precio-input')) {
                    const row = e.target.closest('tr');
                    actualizarSubtotalFila(row);
                }
            });

            // Evento para cambio en porcentaje de IVA
            document.getElementById('iva').addEventListener('input', calcularTotalesGlobales);

            // Agregar nueva fila
            document.getElementById('agregarProducto').addEventListener('click', function() {
                const tbody = document.getElementById('productosBody');
                const newRow = document.createElement('tr');
                newRow.className = 'producto-row';
                
                const index = productCount;
                
                newRow.innerHTML = `
                    <td>
                        <select class="form-select form-select-sm producto-select" 
                                name="productos[${index}][id_producto]" 
                                data-index="${index}"
                                required>
                            <option value="">Seleccionar</option>
                            @foreach($productos as $producto)
                                <option value="{{ $producto->id }}" 
                                        data-clave="{{ $producto->clave }}"
                                        data-descripcion="{{ $producto->descripcion }}"
                                        data-unidad="{{ $producto->unidades }}"
                                        data-precio="{{ $producto->ult_costo }}">
                                    {{ $producto->clave }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm descripcion-input" readonly placeholder="Descripción">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm unidad-input" readonly placeholder="Unidad">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm cantidad-input" name="productos[${index}][cantidad]" step="0.01" min="0.01" value="1" required>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control precio-input" name="productos[${index}][precio]" step="0.01" min="0" required>
                        </div>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">$</span>
                            <input type="text" class="form-control subtotal-text" readonly>
                        </div>
                    </td>
                    <td class="text-center">
                        <i class="fas fa-trash-alt btn-remove-row"></i>
                    </td>
                `;
                
                tbody.appendChild(newRow);
                
                // Inicializar Select2 en la nueva fila
                $(newRow).find('.producto-select').select2({
                    placeholder: 'Seleccionar producto/servicio',
                    allowClear: true,
                    width: '100%',
                    templateResult: formatOption,
                    templateSelection: formatOption
                });
                
                productCount++;
                
                // Mostrar botones de eliminar en todas las filas excepto la primera si solo hay una
                actualizarBotonesEliminar();
            });

            // Eliminar fila
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-remove-row')) {
                    const row = e.target.closest('tr');
                    if (document.querySelectorAll('.producto-row').length > 1) {
                        row.remove();
                        calcularTotalesGlobales();
                        actualizarBotonesEliminar();
                    }
                }
            });

            // Función para actualizar visibilidad de botones eliminar
            function actualizarBotonesEliminar() {
                const rows = document.querySelectorAll('.producto-row');
                rows.forEach((row, index) => {
                    const btnRemove = row.querySelector('.btn-remove-row');
                    if (btnRemove) {
                        btnRemove.style.display = rows.length > 1 ? 'inline-block' : 'none';
                    }
                });
            }

            // Validación del formulario
            const form = document.getElementById('destajoForm');
            form.addEventListener('submit', function(event) {
                const rows = document.querySelectorAll('.producto-row');
                let hasProducts = false;
                
                rows.forEach(row => {
                    const select = row.querySelector('.producto-select');
                    if (select && select.value) {
                        hasProducts = true;
                    }
                });
                
                if (!hasProducts) {
                    event.preventDefault();
                    alert('Debe agregar al menos un producto o servicio');
                    return false;
                }
                
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                form.classList.add('was-validated');
            });

            // Inicializar primera fila
            actualizarBotonesEliminar();
            calcularTotalesGlobales();
        });
    </script>
</body>
</html>