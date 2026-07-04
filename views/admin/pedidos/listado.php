<div class="pedidos">
    <div class="pedidos__breadcrum">
        <a href="/admin/dashboard">Inicio</a>
        <span>/</span>
        <a href="/admin/pedidos/listado">Pedidos</a>
        <span>/</span>
        <p>Listado de Pedidos</p>
    </div>

    <div class="pedidos__header formulario__card">
        <div class="pedidos__header-top">
            <h2>Filtros de Búsqueda</h2>
        </div>

        <div class="pedidos__filtros">
            <div class="formulario__campo">
                <label for="fecha">Fecha:</label>
                <input type="date" id="fecha" name="fecha">
            </div>

            <div class="formulario__campo">
                <label for="vendedor">Vendedor</label>
                <select id="vendedor" name="vendedor">
                    <option value="">Todos los vendedores</option>
                    <?php foreach($vendedores as $vendedor):  ?>
                        <option value="<?php echo $vendedor->id;?>"><?php echo $vendedor->first_name . ' ' . $vendedor->last_name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="formulario__campo">
                <label for="cliente">Cliente</label>
                <select id="cliente" name="cliente">
                    <option value="">Todos los clientes</option>
                    <?php foreach($clientes as $cliente):  ?>
                        <option value="<?php echo $cliente->id;?>"><?php echo $cliente->name;  ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="formulario__campo">
                <label for="estado">Estado</label>
                <select id="estado" name="estado">
                    <option value="">Todos los estados</option>
                    <option value="pending">Pendiente</option>
                    <option value="confirmed">Confirmado</option>
                    <option value="cancelled">Cancelado</option>
                </select>
            </div>

            <div class="formulario__campo">
                <label for="buscar">Buscar</label>
                <input type="text" name="buscar" id="buscar" placeholder="#Pedido o Cliente">
            </div>
    </div>

    <div class="pedidos__acciones-filtros">
            <button type="button" id="btnLimpiarFiltros" class="btn btn__transparente"><i class="fa-solid fa-rotate-left"></i>Limpiar filtros</button>
            <button type="button" id="btnBuscarPedidos" class="btn btn__transparente"><i class="fa-solid fa-magnifying-glass"></i>Buscar Pedidos</button>
    </div>
</div>

<div class="pedidos__tabla formulario__card">
    <div class="pedidos__tabla--header">
        <h3>Pedidos Del Día </h3>
        <p>- Miercoles 24 de abril de 2026</p>
    </div>

    <div class="tabla tabla__grid--listado-pe">
            <span>N° Pedido</span>
            <span>Hora</span>
            <span>Cliente</span>
            <span>Vendedor</span>
            <span>Estado</span>
            <span>Total</span>
            <span>Acciones</span>
    </div>

    <div class="pedidos__tabla-body">
        <?php if(!empty($pedidos)): ?>
            <?php foreach($pedidos as $pedido): ?>
                <div class="tabla tabla__fila--listado-pe pedidos__fila">
                    <span class="pedidos__pedido-id">#<?php echo str_pad($pedido['id'], 6, '0', STR_PAD_LEFT); ?></span>
                    <span><?php echo date('d/m/Y H:i', strtotime($pedido['created_at'])); ?></span>
                    <span><?php echo s($pedido['client_name'] ?? 'Sin cliente'); ?></span>
                    <span><?php echo s($pedido['seller_name'] ?? 'Sin vendedor'); ?></span>
                    <div class="estado estado--proceso">
                        <span><?php echo $pedido['status']; ?></span>
                    </div>
                    <span>$<?php echo number_format($pedido['total'], 2, ',', '.'); ?></span>
                    <div class="tabla__acciones-tabla">
                        <button class="tabla__editar"><i class="fa-solid fa-eye"></i></button>
                        <button class="tabla__editar"><i class="fa-solid fa-ellipsis"></i></button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="tabla__vacia">No hay pedidos registrados</p>
        <?php endif; ?>
    </div>
