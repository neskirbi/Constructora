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
    </div>
    
    <div style="padding: 20px; border-top: 1px solid var(--color-secundario); margin-top: auto;">
        <div class="menu-item">
            <i class="fas fa-headset menu-icon"></i>
            <span class="menu-text">Soporte</span>
        </div>
    </div>
</aside>