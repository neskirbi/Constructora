// Variables para controlar el estado de la sidebar
let isSidebarCollapsed = false;

// Función para inicializar dropdowns de Bootstrap
function initializeDropdowns() {
    // Inicializar todos los dropdowns
    const dropdownElements = document.querySelectorAll('.dropdown-toggle');
    dropdownElements.forEach(function(dropdown) {
        new bootstrap.Dropdown(dropdown);
    });
}

// Toggle sidebar
document.getElementById('toggleSidebar').addEventListener('click', function() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const toggleIcon = this.querySelector('i');
    
    if (window.innerWidth <= 992) {
        // En móviles, solo activar/desactivar
        sidebar.classList.toggle('active');
    } else {
        // En pantallas grandes, colapsar/expandir
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('sidebar-collapsed');
        
        // Cambiar icono
        if (sidebar.classList.contains('collapsed')) {
            toggleIcon.className = 'fas fa-chevron-right';
            isSidebarCollapsed = true;
        } else {
            toggleIcon.className = 'fas fa-bars';
            isSidebarCollapsed = false;
        }
        
        // Guardar preferencia en localStorage
        localStorage.setItem('sidebarCollapsed', isSidebarCollapsed);
    }
});

// Cargar preferencia de sidebar al inicio
document.addEventListener('DOMContentLoaded', function() {
    const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const toggleIcon = document.getElementById('toggleSidebar').querySelector('i');
    
    if (sidebarCollapsed && window.innerWidth > 992) {
        sidebar.classList.add('collapsed');
        mainContent.classList.add('sidebar-collapsed');
        toggleIcon.className = 'fas fa-chevron-right';
        isSidebarCollapsed = true;
    }
    
    // Inicializar dropdowns
    initializeDropdowns();
    
    // Configurar logout
    setupLogout();
});

// Cerrar sidebar al hacer clic fuera de él en móviles
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleSidebar');
    
    if (window.innerWidth <= 992 && 
        !sidebar.contains(event.target) && 
        !toggleBtn.contains(event.target) && 
        sidebar.classList.contains('active')) {
        sidebar.classList.remove('active');
    }
});

// Manejar redimensionamiento de ventana
window.addEventListener('resize', function() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const toggleIcon = document.getElementById('toggleSidebar').querySelector('i');
    
    if (window.innerWidth <= 992) {
        // En móviles, asegurar que no esté en modo colapsado
        sidebar.classList.remove('collapsed');
        mainContent.classList.remove('sidebar-collapsed');
        sidebar.classList.remove('active');
        toggleIcon.className = 'fas fa-bars';
    } else {
        // En pantallas grandes, restaurar estado colapsado si estaba
        const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (sidebarCollapsed) {
            sidebar.classList.add('collapsed');
            mainContent.classList.add('sidebar-collapsed');
            toggleIcon.className = 'fas fa-chevron-right';
        } else {
            sidebar.classList.remove('collapsed');
            mainContent.classList.remove('sidebar-collapsed');
            toggleIcon.className = 'fas fa-bars';
        }
    }
});

// Simular notificación (solo si el elemento existe)
const notificationBtn = document.querySelector('.notification-btn');
if (notificationBtn) {
    notificationBtn.addEventListener('click', function() {
        alert('Tienes 3 notificaciones pendientes');
    });
}

// Cambiar elemento activo del menú
document.querySelectorAll('.menu-item').forEach(item => {
    item.addEventListener('click', function(e) {
        document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
        this.classList.add('active');
        
        // En móviles, cerrar sidebar después de seleccionar
        if (window.innerWidth <= 992) {
            document.getElementById('sidebar').classList.remove('active');
        }
    });
});

// Función para configurar logout con SweetAlert
function setupLogout() {
    const logoutBtn = document.querySelector('.logout-btn');
    
    if (!logoutBtn) return;
    
    logoutBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Verificar si SweetAlert2 está disponible
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '¿Cerrar sesión?',
                text: '¿Estás seguro de que deseas salir del sistema?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, cerrar sesión',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                backdrop: true,
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    const logoutForm = document.getElementById('logout-form');
                    if (logoutForm) {
                        logoutForm.submit();
                    }
                }
            });
        } else {
            // Fallback a confirm nativo
            if (confirm('¿Estás seguro de que deseas cerrar sesión?')) {
                const logoutForm = document.getElementById('logout-form');
                if (logoutForm) {
                    logoutForm.submit();
                }
            }
        }
    });
}