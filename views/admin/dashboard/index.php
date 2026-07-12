<?php include_once __DIR__ . '/../../templates/alertas.php'; ?>
<section class="dashboard">
    <div class="dashboard__header">
        <div class="dashboard__bienvenida">
            <h2>Bienvenido,  <?php echo s(sessionUser('first_name')); ?></h2>
            <p><?php echo $descripcion ?? ''; ?></p>
        </div>

        <div class="dashboard__bienvenida--acciones">
            <p><i class="fa-solid fa-calendar-days"></i><?php echo fechaEnEspanol(); ?></p>
            <a href="/admin/dashboard" class="btn btn__transparente">Actualizar</a>
        </div>  
    </div>

    <div class="dashboard__contenedor--cards">
    <div class="dashboard__cards">
        <div class="dashboard__cards--icono cards-icono--azul">
            <i class="fa-solid fa-box"></i>
        </div>
        <div class="dashboard__cards--info">
            <span><?php echo $estadisticas['total'] ?? 0; ?></span>
            <p>Total Pedidos</p>
        </div>
    </div>

    <div class="dashboard__cards">
        <div class="dashboard__cards--icono cards-icono--celeste">
            <i class="fa-solid fa-users"></i>
        </div>
        <div class="dashboard__cards--info">
        <span><?php echo $totalClientes ?? 0; ?></span>
        <p>Total Clientes</p>
        </div>
    </div>

    <div class="dashboard__cards">
        <div class="dashboard__cards--icono cards-icono--azul">
            <i class="fa-solid fa-capsules"></i>
        </div>
        <div class="dashboard__cards--info">
        <span><?php echo $totalProductos ?? 0; ?></span>
        <p>Total Productos</p>
        </div>
    </div>

    <div class="dashboard__cards">
        <div class="dashboard__cards--icono cards-icono--naranja">
            <i class="fa-solid fa-clock"></i>
        </div>
        <div class="dashboard__cards--info">
        <span><?php echo $estadisticas['pendientes'] ?? 0; ?></span>
        <p>Pendientes</p>
        </div>
    </div>

    <div class="dashboard__cards">
        <div class="dashboard__cards--icono cards-icono--verde">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="dashboard__cards--info">
        <span><?php echo $estadisticas['completados'] ?? 0; ?></span>
        <p>Completados</p>
        </div>
    </div>

    <div class="dashboard__cards">
        <div class="dashboard__cards--icono cards-icono--morado">
            <i class="fa-solid fa-ban"></i>
        </div>
        <div class="dashboard__cards--info">
        <span><?php echo $estadisticas['cancelados'] ?? 0; ?></span>
        <p>Cancelados</p>
        </div>
    </div>
    </div>

    <div class="dashboard__grid">
        <div class="dashboard__panel dashboard__panel--pedidos">
            <div class="dashboard__panel--header">
                <h3>Últimos pedidos</h3>
                <a href="/admin/pedidos/listado" class="btn btn__transparente">Ver Todos <i class="fa-solid fa-arrow-right"></i></a>
            </div>

            <div class="dashboard__tabla">
                <div class="tabla tabla__grid--listado-pe">
                    <span>#Pedido</span>
                    <span>Fecha</span>
                    <span>Cliente</span>
                    <span>Vendedor</span>
                    <span>Estado</span>
                    <span>Total</span>
                    <span>Acciones</span>
                </div>
                <?php if(!empty($ultimosPedidos)): ?>
                    <?php foreach($ultimosPedidos as $pedido): ?>
                        <div class="tabla tabla__fila--listado-pe pedidos__fila">
                            <span>#<?php echo str_pad($pedido['id'], 6, '0', STR_PAD_LEFT); ?></span>
                            <span><?php echo date('d/m/Y', strtotime($pedido['created_at'])); ?></span>
                            <span><?php echo s($pedido['client_name'] ?? 'Sin cliente'); ?></span>
                            <span><?php echo s($pedido['seller_name'] ?? 'Sin vendedor'); ?></span>
                            <span><?php echo $pedido['status']; ?></span>
                            <span>$<?php echo number_format($pedido['total'], 2, ',', '.'); ?></span>
                            <div class="tabla__acciones">
                               <span><a href="/admin/pedidos/detalle?id=<?php echo $pedido['id']; ?>"><i class="fa-solid fa-eye"></i></a></span> 
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="tabla__vacia">No hay pedidos registrados</p>
                <?php endif; ?>
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
                <a href="/admin/productos/excel" class="dashboard__accion"> <i class="fa-solid fa-capsules"></i>Nuevo Producto</a>
            </div>
            <div class="dashboard__acciones--rapidas">
                <a href="/admin/clientes/crear" class="dashboard__accion"><i class="fa-solid fa-user-plus"></i>Nuevo Cliente</a>
            </div>
            <div class="dashboard__acciones--rapidas">
                <a href="/admin/usuarios/crear" class="dashboard__accion"> <i class="fa-solid fa-user-gear"></i>Nuevo Usuario</a>
            </div>
        </div>
    </div>
    
</section>

