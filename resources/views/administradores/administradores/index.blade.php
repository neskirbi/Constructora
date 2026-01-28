<!DOCTYPE html>
<html lang="es">
<head>
    @include('header')
    <title>{{Empresa()}} | Administradores</title>
    
    <!-- Estilos personalizados -->
   
</head>
<body>
    <div class="main-container">
        @include('toast.toasts')
        @include('administradores.sidebar')        

        <!-- Contenido principal -->
        <main class="main-content" id="mainContent">
            @include('administradores.navbar')

            <!-- Área de contenido -->
            <div class="content-area">                
              <div class="container-fluid py-4">
        <!-- Título y botón agregar -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-users me-2"></i>Gestor de Usuarios
            </h1>
            <a class="btn btn-primary" href="{{url('administradores/create')}}">
                <i class="fas fa-plus me-2"></i>Agregar Usuario
            </a>
        </div>
        
        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tipo de Usuario</label>
                        <select class="form-select" id="tipoFiltro">
                            <option value="all">Todos los tipos</option>
                            <option value="administrador">Administradores</option>
                            <option value="ingreso">Ingresos</option>
                            <option value="destajo">Destajos</option>
                            <option value="compra">Compras</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Buscar por nombre</label>
                        <input type="text" class="form-control" id="buscarNombre" placeholder="Escribe para buscar...">
                    </div>
                    <div class="col-md-4 mb-3 d-flex align-items-end">
                        <button class="btn btn-secondary w-100" onclick="resetFiltros()">
                            <i class="fas fa-redo me-2"></i>Limpiar Filtros
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Administradores -->
        <div class="card mb-4 usuario-section" data-tipo="administrador">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-user-shield me-2"></i>Administradores ({{ $administradores->count() }})
                </h5>
                <span class="badge bg-light text-primary">Principal: {{ $administradores->where('principal', 1)->count() }}</span>
            </div>
            <div class="card-body">
                @if($administradores->isEmpty())
                    <div class="text-center py-3 text-muted">
                        <i class="fas fa-users-slash fa-2x mb-3"></i>
                        <p>No hay administradores registrados</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre Completo</th>
                                    <th>Email</th>
                                    <th>Contraseña Temporal</th>
                                    <th>Tipo</th>
                                    <th>Fecha Registro</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($administradores as $admin)
                                <tr class="table-row" data-nombre="{{ strtolower($admin->nombres . ' ' . $admin->apellidos) }}" data-email="{{ strtolower($admin->mail) }}">
                                    <td>
                                        <strong>{{ $admin->nombres }} {{ $admin->apellidos }}</strong>
                                        @if($admin->principal)
                                            <span class="badge bg-warning ms-2">Principal</span>
                                        @endif
                                    </td>
                                    <td>{{ $admin->mail }}</td>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="form-control pass-temp-field readonly-input" 
                                                value="{{ $admin->passtemp ?? 'Sin contraseña temporal' }}" 
                                                data-invalido="true" readonly>
                                            <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button"
                                                    onclick="GenerarPass(this)"
                                                    data-user-id="{{ $admin->id }}"
                                                    data-user-type="administradores">
                                                <i class="fas fa-sync-alt"></i> 
                                            </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-primary">Administrador</span></td>
                                    <td>{{ $admin->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-outline-primary" href="{{url('administradores')}}/{{$admin->id}}" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger delete-user-btn" 
                                                title="Eliminar"
                                                data-user-id="{{ $admin->id }}"
                                                data-user-name="{{ $admin->nombres }} {{ $admin->apellidos }}"
                                                data-user-type="administradores">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Ingresos -->
        <div class="card mb-4 usuario-section" data-tipo="ingreso">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>Administración de Ingresos ({{ $ingresos->count() }})
                </h5>
            </div>
            <div class="card-body">
                @if($ingresos->isEmpty())
                    <div class="text-center py-3 text-muted">
                        <i class="fas fa-users-slash fa-2x mb-3"></i>
                        <p>No hay usuarios de ingresos registrados</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre Completo</th>
                                    <th>Email</th>
                                    <th>Contraseña Temporal</th>                                    
                                    <th>Tipo</th>
                                    <th>Fecha Registro</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ingresos as $ingreso)
                                <tr class="table-row" data-nombre="{{ strtolower($ingreso->nombres . ' ' . $ingreso->apellidos) }}" data-email="{{ strtolower($ingreso->mail) }}">
                                    <td><strong>{{ $ingreso->nombres }} {{ $ingreso->apellidos }}</strong></td>
                                    <td>{{ $ingreso->mail }}</td>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="form-control pass-temp-field readonly-input" 
                                                value="{{ $ingreso->passtemp ?? 'Sin contraseña temporal' }}" 
                                                data-invalido="true" readonly>
                                            <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button"
                                                    onclick="GenerarPass(this)"
                                                    data-user-id="{{ $ingreso->id }}"
                                                    data-user-type="aingresos">
                                                <i class="fas fa-sync-alt"></i> 
                                            </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-success">Ingreso</span></td>
                                    <td>{{ $ingreso->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-outline-primary" href="{{url('administradores')}}/{{$ingreso->id}}" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                         <button class="btn btn-sm btn-outline-danger delete-user-btn" 
                                                title="Eliminar"
                                                data-user-id="{{ $ingreso->id }}"
                                                data-user-name="{{ $ingreso->nombres }} {{ $ingreso->apellidos }}"
                                                data-user-type="Ingreso">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Destajos -->
        <div class="card mb-4 usuario-section" data-tipo="destajo">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>Administración de Destajos ({{ $destajos->count() }})
                </h5>
            </div>
            <div class="card-body">
                @if($destajos->isEmpty())
                    <div class="text-center py-3 text-muted">
                        <i class="fas fa-users-slash fa-2x mb-3"></i>
                        <p>No hay usuarios de destajo registrados</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre Completo</th>
                                    <th>Email</th>
                                    <th>Contraseña Temporal</th>
                                    <th>Tipo</th>
                                    <th>Fecha Registro</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($destajos as $destajo)
                                <tr class="table-row" data-nombre="{{ strtolower($destajo->nombres . ' ' . $destajo->apellidos) }}" data-email="{{ strtolower($destajo->mail) }}">
                                    <td><strong>{{ $destajo->nombres }} {{ $destajo->apellidos }}</strong></td>
                                    <td>{{ $destajo->mail }}</td>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="form-control pass-temp-field readonly-input" 
                                                value="{{ $destajo->passtemp ?? 'Sin contraseña temporal' }}" 
                                                data-invalido="true" readonly>
                                            <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button"
                                                    onclick="GenerarPass(this)"
                                                    data-user-id="{{ $destajo->id }}"
                                                    data-user-type="adestajos">
                                                <i class="fas fa-sync-alt"></i> 
                                            </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-warning">Destajo</span></td>
                                    <td>{{ $destajo->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-outline-primary" href="{{url('administradores')}}/{{$destajo->id}}" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger delete-user-btn" 
                                                title="Eliminar"
                                                data-user-id="{{ $destajo->id }}"
                                                data-user-name="{{ $destajo->nombres }} {{ $destajo->apellidos }}"
                                                data-user-type="Destajo">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Compras -->
        <div class="card mb-4 usuario-section" data-tipo="compra">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>Administración de Compras ({{ $compras->count() }})
                </h5>
            </div>
            <div class="card-body">
                @if($compras->isEmpty())
                    <div class="text-center py-3 text-muted">
                        <i class="fas fa-users-slash fa-2x mb-3"></i>
                        <p>No hay usuarios de compras registrados</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre Completo</th>
                                    <th>Email</th>
                                    <th>Contraseña Temporal</th>
                                    <th>Tipo</th>
                                    <th>Fecha Registro</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($compras as $compra)
                                <tr class="table-row" data-nombre="{{ strtolower($compra->nombres . ' ' . $compra->apellidos) }}" data-email="{{ strtolower($compra->mail) }}">
                                    <td><strong>{{ $compra->nombres }} {{ $compra->apellidos }}</strong></td>
                                    <td>{{ $compra->mail }}</td>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="form-control pass-temp-field readonly-input" 
                                                value="{{ $compra->passtemp ?? 'Sin contraseña temporal' }}" 
                                                data-invalido="true" readonly>
                                            <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button"
                                                    onclick="GenerarPass(this)"
                                                    data-user-id="{{ $compra->id }}"
                                                    data-user-type="acompras">
                                                <i class="fas fa-sync-alt"></i> 
                                            </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-info">Compra</span></td>
                                    <td>{{ $compra->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-outline-primary" href="{{url('administradores')}}/{{$compra->id}}" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger delete-user-btn" 
                                                title="Eliminar"
                                                data-user-id="{{ $compra->id }}"
                                                data-user-name="{{ $compra->nombres }} {{ $compra->apellidos }}"
                                                data-user-type="Compra">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
                
            </div>
        </main>
    </div>
<!-- Modal de confirmación para eliminar usuario -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="confirmDeleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                    <h5>¿Estás seguro de eliminar este usuario?</h5>
                    <p class="text-muted mb-0" id="deleteUserInfo">Esta acción no se puede deshacer.</p>
                </div>
                <div class="alert alert-warning" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Advertencia:</strong> Todos los datos asociados a este usuario serán eliminados permanentemente.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <form id="deleteUserForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Sí, Eliminar Usuario
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
    @include('footer')
    
    <!-- Script para filtros -->
    <script>

        // Script para eliminar usuarios
document.addEventListener('DOMContentLoaded', function() {
    const deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    const deleteForm = document.getElementById('deleteUserForm');
    const deleteUserInfo = document.getElementById('deleteUserInfo');
    
    // Agregar evento a todos los botones de eliminar
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-user-btn')) {
            const button = e.target.closest('.delete-user-btn');
            const userId = button.getAttribute('data-user-id');
            const userName = button.getAttribute('data-user-name');
            const userType = button.getAttribute('data-user-type');
            
            // Actualizar información en el modal
            deleteUserInfo.textContent = `Vas a eliminar a ${userName} (${userType}) permanentemente.`;
            
            // Configurar la acción del formulario
            deleteForm.action = `{{ url('administradores') }}/${userId}`;
            
            // Mostrar el modal
            deleteModal.show();
        }
    });
    
    // Manejar el envío del formulario de eliminación
    deleteForm.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        
        // Deshabilitar botón y mostrar indicador de carga
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Eliminando...';
        
        // Aquí se enviará el formulario automáticamente
        // Puedes agregar lógica adicional si es necesario
    });
    
    // Resetear botón cuando se cierra el modal
    document.getElementById('confirmDeleteModal').addEventListener('hidden.bs.modal', function() {
        const submitBtn = deleteForm.querySelector('button[type="submit"]');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-trash me-2"></i>Sí, Eliminar Usuario';
    });
});


        document.addEventListener('DOMContentLoaded', function() {
            const tipoFiltro = document.getElementById('tipoFiltro');
            const buscarNombre = document.getElementById('buscarNombre');
            const usuarioSections = document.querySelectorAll('.usuario-section');
            
            // Función para aplicar todos los filtros
            function aplicarFiltros() {
                const tipoSeleccionado = tipoFiltro.value;
                const textoBusqueda = buscarNombre.value.toLowerCase().trim();
                
                usuarioSections.forEach(section => {
                    const tipoSection = section.getAttribute('data-tipo');
                    const filas = section.querySelectorAll('.table-row');
                    let sectionVisible = false;
                    
                    // Primero, filtrar por tipo
                    if (tipoSeleccionado === 'all' || tipoSeleccionado === tipoSection) {
                        section.classList.remove('hidden');
                        
                        // Luego, filtrar filas por texto de búsqueda
                        filas.forEach(fila => {
                            const nombre = fila.getAttribute('data-nombre') || '';
                            const email = fila.getAttribute('data-email') || '';
                            
                            if (textoBusqueda === '' || 
                                nombre.includes(textoBusqueda) || 
                                email.includes(textoBusqueda)) {
                                fila.classList.remove('hidden');
                                sectionVisible = true;
                            } else {
                                fila.classList.add('hidden');
                            }
                        });
                    } else {
                        section.classList.add('hidden');
                        filas.forEach(fila => {
                            fila.classList.add('hidden');
                        });
                    }
                    
                    // Si no hay filas visibles en la sección, ocultar la sección completa
                    if (!sectionVisible && tipoSeleccionado === tipoSection) {
                        section.classList.add('hidden');
                    }
                });
                
                // Mostrar mensaje si no hay resultados
                mostrarMensajeSinResultados();
            }
            
            // Función para mostrar mensaje cuando no hay resultados
            function mostrarMensajeSinResultados() {
                let tieneResultados = false;
                
                usuarioSections.forEach(section => {
                    if (!section.classList.contains('hidden')) {
                        const filasVisibles = section.querySelectorAll('.table-row:not(.hidden)');
                        if (filasVisibles.length > 0) {
                            tieneResultados = true;
                        }
                    }
                });
                
                // Si no hay resultados, mostrar mensaje
                if (!tieneResultados) {
                    // Verificar si ya existe el mensaje
                    let mensajeExistente = document.querySelector('.sin-resultados');
                    if (!mensajeExistente) {
                        const mensaje = document.createElement('div');
                        mensaje.className = 'card sin-resultados';
                        mensaje.innerHTML = `
                            <div class="card-body text-center py-5">
                                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No se encontraron resultados</h5>
                                <p class="text-muted mb-0">Intenta con otros términos de búsqueda</p>
                            </div>
                        `;
                        
                        // Insertar después del filtro
                        const filtroCard = document.querySelector('.card.mb-4');
                        filtroCard.parentNode.insertBefore(mensaje, filtroCard.nextSibling);
                    }
                } else {
                    // Remover mensaje si existe
                    const mensajeExistente = document.querySelector('.sin-resultados');
                    if (mensajeExistente) {
                        mensajeExistente.remove();
                    }
                }
            }
            
            // Función para limpiar filtros
            window.resetFiltros = function() {
                tipoFiltro.value = 'all';
                buscarNombre.value = '';
                buscarNombre.classList.remove('filter-active');
                
                // Mostrar todas las secciones y filas
                usuarioSections.forEach(section => {
                    section.classList.remove('hidden');
                    const filas = section.querySelectorAll('.table-row');
                    filas.forEach(fila => {
                        fila.classList.remove('hidden');
                    });
                });
                
                // Remover mensaje de sin resultados si existe
                const mensajeExistente = document.querySelector('.sin-resultados');
                if (mensajeExistente) {
                    mensajeExistente.remove();
                }
            }
            
            // Event listeners
            tipoFiltro.addEventListener('change', aplicarFiltros);
            
            buscarNombre.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    this.classList.add('filter-active');
                } else {
                    this.classList.remove('filter-active');
                }
                aplicarFiltros();
            });
            
            // Aplicar filtro al presionar Enter en el buscador
            buscarNombre.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    aplicarFiltros();
                }
            });
            
            // Inicializar filtros (por si hay algún valor por defecto)
            aplicarFiltros();
        });
    </script>
</body>
</html>