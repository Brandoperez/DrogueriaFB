<aside class="sidebar">
    <div class="sidebar__logo">
        <img src="/build/img/logo.jpg" alt="Logo de Droguería FB">
        <p>Panel Administrativo</p>
    </div>

    <nav class="sidebar__menu">
        <a href="/admin/dashboard" class="sidebar__enlace <?php echo paginaActual('/admin/dashboard') ? 'sidebar__enlace--actual' : '' ?> ">
            <i class="fa-solid fa-house"></i> 
            <span>Dashboard</span>
        </a>

        <div class="sidebar__grupo <?php echo paginaActual('/admin/pedidos') ? 'sidebar__enlace--actual' : ''; ?>">
            <a href="#" class="sidebar__enlace sidebar__toggle">
                <i class="fa-solid fa-box"></i>
                <span>Pedidos</span>
                <i class="fa-solid fa-chevron-down sidebar__flecha"></i>
            </a>

            <div class="sidebar__submenu">
                <a href="/admin/pedidos/crear" class="sidebar__submenu--enlace <?php echo paginaActual('/admin/pedidos/crear') ? 'sidebar__submenu--actual' : ''; ?> ">
                    <i class="fa-solid fa-calendar-plus"></i>
                    <span>Carga Manual</span>
                </a>

                <a href="/admin/pedidos/excel" class="sidebar__submenu--enlace <?php echo paginaActual('/admin/pedidos/excel') ? 'sidebar__submenu--actual' : '';?>">
                    <i class="fa-solid fa-file-excel"></i>
                    <span>Carga Pedido Excel</span>
                </a>

                <a href="/admin/pedidos/listado" class="sidebar__submenu--enlace <?php echo paginaActual('/admin/pedidos/listado') ? 'sidebar_submenu--activo' : ''; ?>">
                    <i class="fa-solid fa-clipboard-list"></i>
                    <span>Listado de Pedidos</span>
                </a>
            </div>
        </div>

        <div class="sidebar__grupo">
            <a href="#" class="sidebar__enlace sidebar__toggle">
                <i class="fa-solid fa-capsules"></i>
                <span>Productos</span>
                <i class="fa-solid fa-chevron-down sidebar__flecha"></i>
            </a>

            <div class="sidebar__submenu">
                <a href="/admin/productos/excel" class="sidebar__submenu--enlace">
                    <i class="fa-solid fa-file-import"></i>
                    <span>Carga Diaria</span>
                </a>

                <a href="/admin/productos/historial" class="sidebar__submenu--enlace">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>Historial de Cargas</span>
                </a>
            </div>
        </div>
        

        <div class="sidebar__grupo <?php echo paginaActual('/admin/clientes') ? 'sidebar__enlace--actual' : ''; ?>">
           <a href="/admin/clientes" class="sidebar__enlace ">
                <i class="fa-solid fa-users"></i>
                <span>Clientes</span>
            </a> 
        </div>
        

        <div class="sidebar__grupo <?php echo paginaActual('/admin/usuarios') ? 'sidebar__enlace--actual' : ''; ?>">
            <a href="/admin/usuarios" class="sidebar__enlace ">
                <i class="fa-solid fa-user-gear"></i>
                <span>Usuarios</span>
            </a>
        </div>
        

        <div class="sidebar__grupo">
            <a href="#" class="sidebar__enlace sidebar__toggle">
                <i class="fa-solid fa-gear"></i>
                <span>Configuración</span>
                <i class="fa-solid fa-chevron-down sidebar__flecha"></i>
            </a>

            <div class="sidebar__submenu">
                <a href="#" class="sidebar__submenu--enlace">
                    <i class="fa-solid fa-file-import"></i>
                    <span>Cerrar Sesión</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="sidebar__usuario">
        <div class="sidebar__avatar">
            <i class="fa-solid fa-user"></i>
        </div>
        <div class="sidebar__info">
            <p>Administrador</p>
            <span>Admin</span>
        </div>
    </div>
</aside>