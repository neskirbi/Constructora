<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="#" class="logo">
            <i class="fas fa-hard-hat logo-icon"></i>
            <span class="logo-text">{{Empresa()}}</span>
        </a>
    </div>
    
    <div class="sidebar-menu">
        <a href="{{url('productosyservicios')}}" class="menu-item">
            <i class="fas fa-box menu-icon"></i>
            <span class="menu-text">Productos y Servicios</span>
        </a>
        
        <a href="{{url('proveedoresds')}}" class="menu-item">
            <i class="fas fa-users menu-icon"></i>
            <span class="menu-text">Proveedores</span>
        </a>

        <a href="{{ route('compras.index') }}" class="menu-item">
            <i class="fas fa-list menu-icon"></i>
            <span class="menu-text">Compras</span>
        </a>

       <a href="{{ url('requisiciones') }}" class="menu-item">
            <i class="fas fa-clipboard-list menu-icon"></i>
            <span class="menu-text">Requisiciones</span>
           
        </a>

        <!-- Grupo expandible de Reportes -->
        <div class="expandable-menu-container">
            <a href="#" class="menu-item expandable-toggle" onclick="toggleReportesMenu(this)">
                <i class="fas fa-chart-line menu-icon"></i>
                <span class="menu-text">Reportes</span>
                <i class="fas fa-chevron-down expandable-arrow"></i>
            </a>

            <div class="expandable-submenu">
                 

                <a href="{{url('reportes/compra')}}" class="submenu-item">
                    <i class="fas fa-file-alt submenu-icon"></i>
                    <span class="submenu-text">Reporte Compras</span>
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

        
    </div>
    
    <div style="padding: 20px; border-top: 1px solid var(--color-secundario); margin-top: auto;">
        <div class="menu-item">
            <i class="fas fa-headset menu-icon"></i>
            <span class="menu-text">Soporte</span>
        </div>
    </div>
</aside>