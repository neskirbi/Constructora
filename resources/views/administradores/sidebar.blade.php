<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="#" class="logo">
            <i class="fas fa-hard-hat logo-icon"></i>
            <span class="logo-text">{{Empresa()}}</span>
        </a>
    </div>
    
    <div class="sidebar-menu">
        
        <a href="{{url('administradores')}}" class="menu-item">
            <i class="fas fa-users menu-icon"></i>
            <span class="menu-text">Administradores</span>
        </a>
        
        <a href="{{url('acontratos')}}" class="menu-item">
            <i class="fas fa-file-contract menu-icon"></i>
            <span class="menu-text">Contratos</span>
        </a>
        
        <a href="{{url('aingresos')}}" class="menu-item">
            <i class="fas fa-money-bill-wave menu-icon"></i>
            <span class="menu-text">Ingresos</span>
        </a>

        <!-- Grupo expandible de Proveedores -->
        <div class="expandable-menu-container">
            <a href="#" class="menu-item expandable-toggle" onclick="toggleProveedoresMenu(this)">
                <i class="fas fa-truck menu-icon"></i>
                <span class="menu-text">Proveedores</span>
                <i class="fas fa-chevron-down expandable-arrow"></i>
            </a>

            <div class="expandable-submenu">
                <a href="{{url('aproveedoresds')}}" class="submenu-item">
                    <i class="fas fa-users submenu-icon"></i>
                    <span class="submenu-text">Proveedores de Servicios</span>
                </a>
                <!-- Puedes agregar más opciones de proveedores aquí -->
                <!--
                <a href="{{url('aproveedores/materiales')}}" class="submenu-item">
                    <i class="fas fa-boxes submenu-icon"></i>
                    <span class="submenu-text">Proveedores de Materiales</span>
                </a>
                <a href="{{url('aproveedores/equipos')}}" class="submenu-item">
                    <i class="fas fa-tools submenu-icon"></i>
                    <span class="submenu-text">Proveedores de Equipos</span>
                </a>
                -->
            </div>
        </div>

         <a href="{{url('adestajos')}}" class="menu-item">
            <i class="fas fa-hammer menu-icon"></i>
            <span class="menu-text">Destajo</span>
        </a>


        <!-- Grupo expandible de Reportes -->
        <div class="expandable-menu-container">
            <a href="#" class="menu-item expandable-toggle" onclick="toggleReportesMenu(this)">
                <i class="fas fa-chart-line menu-icon"></i>
                <span class="menu-text">Reportes</span>
                <i class="fas fa-chevron-down expandable-arrow"></i>
            </a>

            <div class="expandable-submenu">
                <a href="{{url('reportes/ingresos')}}" class="submenu-item">
                    <i class="fas fa-file-alt submenu-icon"></i>
                    <span class="submenu-text">Reporte de Ingresos</span>
                </a>

                <a href="{{url('reportes/destajo')}}" class="submenu-item">
                    <i class="fas fa-file-alt submenu-icon"></i>
                    <span class="submenu-text">Reporte de Destajo</span>
                </a>
                <!-- Puedes agregar más reportes aquí -->
                <!--
                <a href="{{url('reportes/gastos')}}" class="submenu-item">
                    <i class="fas fa-file-invoice-dollar submenu-icon"></i>
                    <span class="submenu-text">Reporte de Gastos</span>
                </a>
                <a href="{{url('reportes/proyectos')}}" class="submenu-item">
                    <i class="fas fa-project-diagram submenu-icon"></i>
                    <span class="submenu-text">Reporte de Proyectos</span>
                </a>
                <a href="{{url('reportes/proveedores')}}" class="submenu-item">
                    <i class="fas fa-truck submenu-icon"></i>
                    <span class="submenu-text">Reporte de Proveedores</span>
                </a>
                -->
            </div>
        </div>

        <!-- Comentado el resto del menú -->
        <!--
        <a href="#" class="menu-item">
            <i class="fas fa-tasks menu-icon"></i>
            <span class="menu-text">Proyectos</span>
            <span class="menu-badge">5</span>
        </a>
        -->
    </div>
    
    <div style="padding: 20px; border-top: 1px solid var(--color-secundario); margin-top: auto;">
        <div class="menu-item">
            <i class="fas fa-headset menu-icon"></i>
            <span class="menu-text">Soporte</span>
        </div>
    </div>
</aside>