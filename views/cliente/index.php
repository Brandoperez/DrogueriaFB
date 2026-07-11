<div class="clientes">
    <div class="cliente__header">
        <div class="cliente__nombre">
            <h2>¡Bienvenido, <span><?php echo s(sessionUser('first_name')); ?>!</span> </h2>
        </div>
        <div class="cliente__usuario">
                <div>
                    <p><?php echo s(sessionUser('first_name')); ?></p>
                    <span>Cliente Droguería FB</span>
                </div>
                <div class="cliente__avatar">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
        </div>
    </div>
    
    <div class="pedidos__breadcrum">
        <a href="/admin/clientes">Clientes</a>
        <span>/</span>
        <p>Detalles del cliente</p>
    </div>

    <div class="cliente__card">
        <div class="cliente__perfil">
            <div class="cliente__iniciales">
                <p><p><?php echo strtoupper(mb_substr($cliente->name, 0, 2)); ?></p>
            </div>

            <div class="cliente__info">
                <h3><?php echo s(sessionUser('first_name')); ?></h3>
                <span class="estado estado--completado">Activo</span>

                <div class="cliente__datos">
                    <p><i class="fa-solid fa-id-card"></i><strong>CUIT:</strong><span><?php echo $cliente->cuit; ?></span></p>
                    <p><i class="fa-solid fa-envelope"></i><strong>Email:</strong><span><?php echo $cliente->email; ?></span></p>
                    <p><i class="fa-solid fa-phone"></i><strong>Teléfono:</strong><span><?php echo $cliente->phone; ?></span></p>
                    <p><i class="fa-solid fa-location-dot"></i><strong>Dirección:</strong><span><?php echo $cliente->address; ?></span></p>
                    <p><i class="fa-solid fa-list"></i><strong>Lista de precios:</strong><span><?php echo $listaPrecios->name; ?></span></p>
                    <p><i class="fa-solid fa-user"></i><strong>Vendedor asignado:</strong><span><?php echo $cliente->vendedor()->first_name . ' ' . $cliente->vendedor()->last_name; ?></span></p>
                </div>
            </div>
        </div>

        <div class="cliente__sidebar">
            <div class="cliente__acciones">
                <h3>Acciones rápidas</h3>

                <div class="accion">
                    <a href="/cliente/pedidos/crear" class="cliente__boton ">
                        <i class="fa-solid fa-cart-shopping"></i>
                        Crear Pedido Manual
                    </a>

                    <a href="/cliente/pedidos/excel" class="cliente__boton cliente__boton--secundario">
                        <i class="fa-solid fa-upload"></i>
                        Carga de Lista Diaria (Excel)
                    </a>

                    <a href="/cliente/pedidos/listado" class="cliente__boton cliente__boton--secundario">
                        <i class="fa-solid fa-list"></i>
                        Ver Listado de Pedidos
                    </a>
                </div>
                    
            </div>

             <div class="cliente__resumen">
                <div class="cliente__btn">
                    <a href="/cliente/lista-precios" class="btn btn__azul">Lista de Precios</a>
                </div>
            </div>
        </div>
    </div>

    <div class="cliente__pedidos">
        <div class="cliente__tabs">
            <button class="cliente__tab cliente__tab--active"><i class="fa-solid fa-list"></i>Pedidos Realizados</button>
        </div>
        <div class="formulario__card">
            <div class="tabla tabla__grid--listado-pe">
                <span>N° Pedido</span>
                <span>Fecha</span>
                <span>Hora</span>
                <span>Vendedor</span>
                <span>Estado</span>
                <span>Total</span>
                <span>Acciones</span>
            </div>

            <?php if(empty($pedidos)): ?>
                <div class="tabla__vacia">
                    <p>Todavía no realizaste ningún pedido.</p>
                </div>
            <?php else:  ?>
            <?php foreach($pedidos as $pedido): ?>
            <div class="tabla tabla__fila--listado-pe pedidos__fila">
                <span>#<?php echo str_pad($pedido['id'], 6, '0', STR_PAD_LEFT); ?></span>
                <span><?php echo date('d/m/Y', strtotime($pedido['created_at'])); ?></span>
                <span><?php echo date('H:i', strtotime($pedido['created_at'])); ?></span>
                <span><?php echo $pedido['seller_name'] ?? 'Sin asignar'; ?></span>
                <span class="estado <?php echo claseEstado($pedido['status']); ?>"><?php echo ucfirst($pedido['status']); ?></span>
                <span>$<?php echo number_format($pedido['total'], 2, ',', '.'); ?></span>
                <div class="tabla__acciones">
                    <a href="/cliente/pedidos/detalle?id=<?php echo $pedido['id']; ?>" class="tabla__accion"><i class="fa-solid fa-eye"></i></a>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>