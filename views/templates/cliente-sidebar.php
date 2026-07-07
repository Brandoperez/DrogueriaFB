<aside class="sidebar">
    <div class="sidebar__logo">
        <img src="/build/img/logo.jpg" alt="Logo de Droguería FB">
        <p>Portal de Clientes</p>
    </div>

    <nav class="sidebar__menu">
        <a href="/cliente/clientes" class="sidebar__enlace <?php echo paginaActual('/admin/dashboard') ? 'sidebar__enlace--actual' : '' ?> ">
            <i class="fa-solid fa-house"></i> 
            <span>Inicio</span>
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
                <i class="fa-solid fa-gear"></i>
                <span>Configuración</span>
                <i class="fa-solid fa-chevron-down sidebar__flecha"></i>
            </a>

            <div class="sidebar__submenu">
                <form action="/logout" method="POST">
                    <button type="submit" class="sidebar__submenu--enlace">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="sidebar__usuario">
        <div class="sidebar__avatar">
            <i class="fa-solid fa-user"></i>
        </div>
        <div class="sidebar__info">
            <p><?php echo s(sessionUser('first_name')); ?></p>
            <span>Cliente</span>
        </div>
    </div>
</aside>