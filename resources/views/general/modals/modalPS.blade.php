<!-- Modal para agregar nuevo producto/servicio -->
    <div class="modal fade" id="nuevoProductoModal" tabindex="-1" aria-labelledby="nuevoProductoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="nuevoProductoModalLabel">
                        <i class="fas fa-plus-circle text-success me-2"></i>
                        Nuevo Producto/Servicio
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="nuevoProductoForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nuevo_clave" class="form-label required-label">Clave</label>
                            <input type="text" 
                                class="form-control form-control-sm" 
                                id="nuevo_clave" 
                                name="clave" 
                                maxlength="32"
                                required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="nuevo_descripcion" class="form-label required-label">Descripción</label>
                            <textarea class="form-control form-control-sm" 
                                    id="nuevo_descripcion" 
                                    name="descripcion" 
                                    rows="2"
                                    required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="nuevo_unidades" class="form-label required-label">Unidades</label>
                            <input type="text" 
                                class="form-control form-control-sm" 
                                id="nuevo_unidades" 
                                name="unidades" 
                                maxlength="10"
                                placeholder="PZA, M2, etc."
                                required>
                        </div>
                        
                        <div class="alert alert-danger d-none" id="productoError"></div>
                        <div class="alert alert-success d-none" id="productoSuccess"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-success btn-sm" id="guardarProductoBtn">
                            <i class="fas fa-save me-1"></i> Guardar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>