<section class="pedidos">
    <div class="pedidos__breadcrum">
        <a href="/admin/dashboard">Inicio</a>
        <span>/</span> 
        <a href="/admin/productos/listado">Productos</a>
        <span>/</span> 
        <p>Vista Previa de Productos</p>
    </div>
    <?php if(isset($_SESSION['alerta'])): ?>
        <script>window.ALERTA = <?=  json_encode($_SESSION['alerta']) ?>;</script>
        <?php unset($_SESSION['alerta']); ?>
        <?php endif; ?>

    <div class="excel">
        <div class="excel__steps">
            <div class="excel__step <?= $step >= 1 ? 'excel__step--active' : '' ?>">
                <div class="excel__numero">
                    <p>1</p>
                </div>
                <div class="excel__contenido">
                    <h3>Subir Archivo</h3>
                    <p>Seleccionar Excel</p>
                </div>
            </div>
            <div class="excel__linea"></div>

            <div class="excel__step <?= $step >= 2 ? 'excel__step--active' : '' ?>">
                <div class="excel__numero">
                    <p>2</p>
                </div>
                <div class="excel__contenido">
                    <h3>Validar Datos</h3>
                    <p>Recibir Información</p>
                </div>
            </div>

            <div class="excel__linea"></div>

            <div class="excel__step <?= $step >= 3 ? 'excel__step--active' : '' ?>">
                <div class="excel__numero">
                    <p>3</p>
                </div>
                <div class="excel__contenido">
                    <h3>Confirmar Carga</h3>
                    <p>Ver Resumen Final</p>
                </div>
            </div>
        </div>
            
        <?php if(!empty($resultado['todosLosProductos'])): ?>
        <div class="excel__validacion">
            
            <div class="excel__contenido-carga--productos">
                <div class="excel__tabla formulario__card">
                    <div class="tabla__header tabla__header--productos excel__grid--productos">
                        <span>Código</span>
                        <span>Descripción</span>
                        <span>Laboratorio</span>
                        <span>Precio</span>
                        <span>Stock</span>
                        <span>Estado</span>
                    </div>
                    
                    <?php foreach($resultado['todosLosProductos'] as $producto): ?>
                        <div class="tabla__fila excel__grid--productos">
                            <span><?php echo $producto['code']; ?></span>
                            <span><?php echo $producto['description']; ?></span>
                            <span><?php echo $producto['laboratory']; ?></span>
                            <span>$<?php echo number_format($producto['price'], 2, ',', '.'); ?></span>
                            <span><?php echo $producto['stock']; ?></span>
                            <div class="excel__estado excel__estado--valido">
                                <?php echo $producto['estado']; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>

        <div class="excel__acciones">
             <div></div>
                <div class="excel__acciones-right">
                    <form action="/admin/productos/confirmar" method="POST">
                        <button type="submit" class="excel__accion btn__azul"><i class="fa-solid fa-check"></i>Confirmar<button>
                    </form>
                </div>
            </div>
                
        </div>
        <?php endif; ?>
</section>