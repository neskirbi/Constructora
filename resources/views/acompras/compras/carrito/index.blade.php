<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Carrito de Compras</title>
</head>
<body>
    <div class="main-container">
        @include('toast.toasts')
        @include('acompras.sidebar')
        
        <main class="main-content" id="mainContent">
            @include('acompras.navbar')

            <div class="content-area">
                <div class="container-fluid py-4">
                    <!-- Título -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4>
                            <i class="fas fa-shopping-cart text-primary me-2"></i>
                            Carrito de Compras
                            <span class="badge bg-primary ms-2">{{ $carrito->count() }} items</span>
                        </h4>
                        <div>
                            <a href="{{ url('compras') }}" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-arrow-left me-1"></i> Volver a Compras
                            </a>
                            @if($carrito->count() > 0)
                            <button type="button" class="btn btn-danger" onclick="confirmarVaciar()">
                                <i class="fas fa-trash me-1"></i> Vaciar Carrito
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Resumen -->
                    @if($carrito->count() > 0)
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>Items:</strong> {{ $resumen['total_items'] }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Subtotal:</strong> ${{ number_format($resumen['subtotal'], 2) }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>IVA (16%):</strong> ${{ number_format($resumen['iva'], 2) }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Total:</strong> ${{ number_format($resumen['total'], 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-success" onclick="confirmarCompra()">
                                <i class="fas fa-check me-1"></i> Confirmar Compra
                            </button>
                            <button type="button" class="btn btn-info" onclick="buscarPrecios()">
                                <i class="fas fa-search-dollar me-1"></i> Buscar Precios en Catálogo
                            </button>
                            <button type="button" class="btn btn-warning" onclick="asignarContrato()">
                                <i class="fas fa-file-contract me-1"></i> Asignar Contrato
                            </button>
                        </div>
                    </div>
                    @endif

                    <!-- Zona de carga de Excel -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0">
                                <i class="fas fa-file-excel text-success me-2"></i>
                                Cargar Excel
                            </h6>
                        </div>
                        <div class="card-body">
                            <form id="excelForm" action="{{ url('carrito/procesar-excel') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="drop-zone" id="dropZone" style="border: 2px dashed #dee2e6; border-radius: 12px; padding: 40px 20px; text-align: center; cursor: pointer; background: #f8f9fa;">
                                            <i class="fas fa-cloud-upload-alt" style="font-size: 3rem; color: #6c757d;"></i>
                                            <p class="mb-1"><strong>Arrastra tu archivo Excel aquí</strong></p>
                                            <p class="text-muted small mb-2">o haz clic para seleccionarlo</p>
                                            <input type="file" name="archivo_excel" id="archivo_excel" 
                                                   accept=".xlsx,.xls,.csv" style="display:none;" required>
                                            <span id="nombreArchivo" class="text-primary fw-bold"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="contrato_id_carga" class="form-label">Contrato (opcional):</label>
                                            <select name="contrato_id" id="contrato_id_carga" class="form-select" required>
                                                <option value="">Seleccionar contrato</option>
                                                @foreach($contratos as $contrato)
                                                <option value="{{ $contrato->id }}">
                                                    {{ $contrato->consecutivo }} - {{ $contrato->contrato_no }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="fas fa-upload me-1"></i> Cargar
                                        </button>
                                    </div>
                                </div>
                            </form>
                            
                            <div class="mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Columnas: Clave, Descripción, Unidad, Cantidad, Observaciones, Link
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de items del carrito -->
                    @if($carrito->count() > 0)
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0"><i class="fas fa-list me-2"></i> Items en el Carrito</h6>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Clave</th>
                                        <th>Descripción</th>
                                        <th>Unidad</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th>Subtotal</th>
                                        <th>Contrato</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($carrito as $item)
                                    <tr>
                                        <td><strong>{{ $item->clave }}</strong></td>
                                        <td>{{ $item->descripcion }}</td>
                                        <td>{{ $item->unidad }}</td>
                                        <td>{{ number_format($item->cantidad, 2) }}</td>
                                        <td>
                                            @if($item->precio_unitario > 0)
                                            ${{ number_format($item->precio_unitario, 2) }}
                                            @else
                                            <span class="text-danger">Sin precio</span>
                                            @endif
                                        </td>
                                        <td>${{ number_format($item->subtotal ?? 0, 2) }}</td>
                                        <td>
                                            @if($item->contrato)
                                            <span class="badge bg-info">{{ $item->contrato->refinterna }}</span>
                                            @else
                                            <span class="badge bg-warning">Sin contrato</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="editarPrecio('{{ $item->id }}', '{{ $item->clave }}', '{{ $item->precio_unitario }}')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="eliminarItem('{{ $item->id }}', '{{ $item->clave }}')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-cart" style="font-size: 4rem; color: #dee2e6;"></i>
                        <h5 class="mt-3 text-muted">El carrito está vacío</h5>
                        <p class="text-muted">Carga un archivo Excel para comenzar.</p>
                    </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Editar Precio -->
    <div class="modal fade" id="editarPrecioModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editarPrecioForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Precio</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Clave:</strong> <span id="editClave"></span></p>
                        <div class="mb-3">
                            <label for="editPrecio" class="form-label">Precio Unitario:</label>
                            <input type="number" name="precio_unitario" id="editPrecio" class="form-control" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Asignar Contrato -->
    <div class="modal fade" id="asignarContratoModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ url('compras/carrito/asignar-contrato') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Asignar Contrato</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="contrato_id" class="form-label">Seleccionar Contrato:</label>
                            <select name="contrato_id" id="contrato_id" class="form-select" required>
                                <option value="">Seleccionar...</option>
                                @foreach($contratos as $contrato)
                                <option value="{{ $contrato->id }}">
                                    {{ $contrato->consecutivo }} - {{ $contrato->contrato_no }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning">Asignar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Confirmar Compra -->
    <div class="modal fade" id="confirmarCompraModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Compra</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Crear compra con {{ $carrito->count() }} items?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form method="POST" action="{{ url('compras/carrito/confirmar') }}">
                        @csrf
                        <button type="submit" class="btn btn-success">Confirmar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('footer')

    <script>
        // Drag and drop
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('archivo_excel');
        const nombreArchivo = document.getElementById('nombreArchivo');

        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.borderColor = '#0d6efd';
            this.style.background = '#e7f1ff';
        });

        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.style.borderColor = '#dee2e6';
            this.style.background = '#f8f9fa';
        });

        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.style.borderColor = '#dee2e6';
            this.style.background = '#f8f9fa';
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                nombreArchivo.textContent = files[0].name;
            }
        });

        dropZone.addEventListener('click', function() {
            fileInput.click();
        });

        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                nombreArchivo.textContent = this.files[0].name;
            }
        });

        function editarPrecio(id, clave, precio) {
            document.getElementById('editClave').textContent = clave;
            document.getElementById('editPrecio').value = precio || 0;
            document.getElementById('editarPrecioForm').action = '{{ url("compras/carrito/actualizar-precio") }}/' + id;
            new bootstrap.Modal(document.getElementById('editarPrecioModal')).show();
        }

        function buscarPrecios() {
            if (confirm('¿Buscar precios en catálogo?')) {
                fetch('{{ url("compras/carrito/buscar-precios") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(() => window.location.reload());
            }
        }

        function confirmarCompra() {
            new bootstrap.Modal(document.getElementById('confirmarCompraModal')).show();
        }

        function confirmarVaciar() {
            if (confirm('¿Vaciar todo el carrito?')) {
                fetch('{{ url("compras/carrito/vaciar") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(() => window.location.reload());
            }
        }

        function eliminarItem(id, clave) {
            if (confirm('¿Eliminar ' + clave + '?')) {
                fetch('{{ url("compras/carrito/eliminar") }}/' + id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(() => window.location.reload());
            }
        }

        function asignarContrato() {
            new bootstrap.Modal(document.getElementById('asignarContratoModal')).show();
        }
    </script>
</body>
</html>