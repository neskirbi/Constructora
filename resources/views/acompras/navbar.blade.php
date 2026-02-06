<!-- Navbar -->
<nav class="navbar-custom">
    <div class="navbar-content">
        <button class="toggle-sidebar" id="toggleSidebar">
            <i class="fas fa-bars"></i>
        </button>
        
        <div class="navbar-actions">
            <!-- Perfil de usuario con dropdown -->
            <div class="dropdown user-profile-dropdown">
                <button class="btn p-0 border-0 bg-transparent d-flex align-items-center" 
                        type="button" 
                        id="userProfileDropdown" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false">
                    <div class="user-profile">
                        <div class="user-avatar">
                            @php
                                $iniciales = '';
                                $nombre = GetNombre();
                                if ($nombre !== 'Invitado') {
                                    $palabras = explode(' ', $nombre);
                                    if (count($palabras) >= 2) {
                                        $iniciales = strtoupper(substr($palabras[0], 0, 1) . substr($palabras[1], 0, 1));
                                    } else {
                                        $iniciales = strtoupper(substr($nombre, 0, 2));
                                    }
                                } else {
                                    $iniciales = 'IN';
                                }
                            @endphp
                            {{ $iniciales }}
                        </div>
                        <div class="user-info">
                            <span class="user-name">{{ GetNombre() }}</span>
                            <span class="user-role">{{ GetRol() }}</span>
                        </div>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </button>
                
                <!-- Menú dropdown -->
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userProfileDropdown">
                    <li>
                        <div class="dropdown-header">
                            <div class="d-flex align-items-center">
                                <div class="user-avatar-sm me-3">
                                    {{ $iniciales }}
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ GetNombre() }}</h6>
                                    <small class="text-muted">{{ GetRol() }}</small>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <!--<li>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-user-circle me-2"></i> Mi Perfil
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-cog me-2"></i> Configuración
                        </a>
                    </li>-->
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <!-- Formulario para cerrar sesión -->
                        <form id="logout-form" action="{{ url('logout') }}" method="GET" style="display: none;">
                            @csrf
                        </form>
                        <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>