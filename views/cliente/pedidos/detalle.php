<div class="pedidos">
    <div class="pedidos__breadcrum">
        <a href="/cliente/clientes">Inicio</a>
        <span>/</span>
        <a href="/cliente/pedidos/listado">Pedidos</a>
        <span>/</span>
        <p>Detalle del Pedido</p>
    </div>

    <div class="formulario__card m__bottom">
        <div class="titulos--h2 m__bottom">
            <h2>Pedido: # <?php echo str_pad($pedido['id'], 6, '0', STR_PAD_LEFT); ?></h2>
            <p><?php echo date('d/m/Y H:i', strtotime($pedido['created_at'])); ?></p>
        </div>

        <div class="detalle__pedido--info">
            <div>
                <span class="detalle__pedido--label">Vendedor</span>
                <p><?php echo s($pedido['seller_name'] ?? 'Sin Vendedor'); ?></p>
            </div>

            <div>
                <span class="detalle__pedido--label">Estado</span>
                <div class="estado <?php echo claseEstado($pedido['status']); ?>">
                    <span><?php echo $pedido['status']; ?></span>
                </div>
            </div>

            <div>
                <span class="detalle__pedido--label">Total</span>
                <p>$<?php echo number_format($pedido['total'], 2, ',', '.'); ?></p>
            </div>

            <?php if(!empty($pedido['observations'])): ?>
                    <div class="detalle__pedido--observaciones">
                        <span class="detalle__pedido--label">Observaciones</span>
                        <p><?php echo s($pedido['observations']); ?></p>
                    </div>
            <?php endif; ?>
        </div>

        <div class="formulario__card m__bottom">
            <div class="titulos--h2 m__bottom">
                <h2>Productos</h2>
                <p>Detalles de los productos incluidos en este pedido</p>
            </div>

            <div class="tabla tabla__grid--detalle">
                <span>Producto</span>
                <span>Cantidad</span>
                <span>Precio</span>
                <span>Subtotal</span>
            </div>

            <?php if(!empty($pedido['items'])): ?>
            <?php foreach($pedido['items'] as $item): ?>
                <div class="tabla tabla__fila--detalle">
                    <span><?php echo s($item['product_name'] ?? 'Producto eliminado'); ?></span>
                    <span><?php echo $item['quantity']; ?></span>
                    <span>$<?php echo number_format($item['price'], 2, ',', '.'); ?></span>
                    <span>$<?php echo number_format($item['subtotal'], 2, ',', '.'); ?></span>
                </div>
            <?php endforeach; ?>
                <?php else: ?>
                    <p class="tabla__vacia">Este pedido no tiene productos cargados</p>
                <?php endif; ?>
            </div>

            <div class="excel__acciones">
                <a href="/cliente/pedidos/listado" class="btn btn__transparente"><i class="fa-solid fa-arrow-left"></i>Volver al listado</a>
            </div>
        </div>
    </div>
</div>