<?php include_once __DIR__ . '/../../templates/alertas.php'; ?>
<section class="dashboard">
    <div class="dashboard__header">
        <div class="dashboard__bienvenida">
            <h2>Bienvenido, Administrador</h2>
            <p><?php echo $descripcion ?? ''; ?></p>
        </div>

        <div class="dashboard__bienvenida--acciones">
            <p><i class="fa-solid fa-calendar-days"></i>Domingo 31 de mayo de 2026</p>
            <a href="/admin/dashboard" class="btn btn__transparente">Actualizar</a>
        </div>  
    </div>

    <div class="dashboard__contenedor--cards">
    <div class="dashboard__cards">
        <div class="dashboard__cards--icono cards-icono--azul">
            <i class="fa-solid fa-box"></i>
        </div>
        <div class="dashboard__cards--info">
            <span>18</span>
            <p>Pedidos</p>
        </div>
    </div>

    <div class="dashboard__cards">
        <div class="dashboard__cards--icono cards-icono--naranja">
            <i class="fa-solid fa-clock"></i>
        </div>
        <div class="dashboard__cards--info">
        <span>18</span>
        <p>Proceso</p>
        </div>
    </div>

    <div class="dashboard__cards">
        <div class="dashboard__cards--icono cards-icono--verde">
            <i class="fa-solid fa-truck"></i>
        </div>
        <div class="dashboard__cards--info">
        <span>18</span> 
        <p>Despacho</p>
        </div>
    </div>

    <div class="dashboard__cards">
        <div class="dashboard__cards--icono cards-icono--morado">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="dashboard__cards--info">
        <span>18</span>    
        <p>Despachados</p>
        </div>
    </div>

    <div class="dashboard__cards">
        <div class="dashboard__cards--icono cards-icono--celeste">
            <i class="fa-solid fa-users"></i>
        </div>
        <div class="dashboard__cards--info">
        <span>18</span> 
        <p>Clientes</p>
        </div>
    </div>

    <div class="dashboard__cards">
        <div class="dashboard__cards--icono cards-icono--azul">
            <i class="fa-solid fa-capsules"></i>
        </div>
        <div class="dashboard__cards--info">
        <span>18</span>  
        <p>Productos</p>
        </div>
    </div>
    </div>

    <div class="dashboard__grid">
        <div class="dashboard__panel dashboard__panel--pedidos">
            <div class="dashboard__panel--header">
                <h3>Últimos pedidos</h3>
                <a href="/admin/pedidos" class="btn btn__transparente">Ver Todos <i class="fa-solid fa-arrow-right"></i></a>
            </div>

            <div class="dashboard__tabla">
                <div class="dashboard__tabla--header">
                    <span>#Pedido</span>
                    <span>Fecha</span>
                    <span>Cliente</span>
                    <span>Vendedor</span>
                    <span>Estado</span>
                    <span>Total</span>
                    <span>Acciones</span>
                </div>
                <div class="dashboard__tabla--fila">
                    <span>#7654</span>
                    <span>14/10/2026</span>
                    <span>Farmacia</span>
                    <span>Jeromino P</span>
                    <span>Proceso</span>
                    <span>$345.000</span>
                    <span><i class="fa-solid fa-users"></i></span>
                </div>
            </div>
        </div>

        <div class="dashboard__panel dashboard__panel--acciones">
            <div class="dashboard__panel--header">
                <h3>Acciones Rápidas</h3>
            </div>
            <div class="dashboard__acciones--rapidas">
                <a href="/admin/pedidos/crear" class="dashboard__accion"><i class="fa-solid fa-plus"></i>Nuevo Pedido</a>
            </div>
            <div class="dashboard__acciones--rapidas">
                <a href="/admin/productos/crear" class="dashboard__accion"> <i class="fa-solid fa-capsules"></i>Nuevo Producto</a>
            </div>
            <div class="dashboard__acciones--rapidas">
                <a href="/admin/productos/crear" class="dashboard__accion"><i class="fa-solid fa-user-plus"></i>Nuevo Cliente</a>
            </div>
            <div class="dashboard__acciones--rapidas">
                <a href="/admin/usuarios/crear" class="dashboard__accion"> <i class="fa-solid fa-user-gear"></i>Nuevo Usuario</a>
            </div>
        </div>
    </div>
    
</section>

