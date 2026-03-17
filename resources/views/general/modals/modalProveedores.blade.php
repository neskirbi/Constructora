

    <!-- Modal Nuevo Proveedor -->
    <div class="modal fade" id="nuevoProveedorModal" tabindex="-1" aria-labelledby="nuevoProveedorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h5 class="modal-title" id="nuevoProveedorModalLabel">
                            <i class="fas fa-plus-circle me-2 text-success"></i>
                            Nuevo Proveedor
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                
                <div class="modal-body">
                    @include('general.forms.form_proveedor')
                </div>
                
                <div class="modal-footer bg-white"></div>
            </div>
        </div>
    </div>