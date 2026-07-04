<section class="pedidos">
    <div class="pedidos__breadcrum">
        <a href="/admin/dashboard">Inicio</a>
        <span>/</span> 
        <a href="/admin/productos/listado">Productos</a>
        <span>/</span> 
        <p>Importación de Lista de Precios</p>
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

        <?php if($step == 1) : ?>
        <div class="excel__upload">
            <form action="/admin/productos/excel" method="POST" enctype="multipart/form-data">
                <div class="excel__dropzone formulario__card">
                    <div class="excel__header">
                        <h2>1.Subir Archivo Excel</h2>
                    </div>
                    <div class="excel__zona">
                        <div class="excel__icono">
                            <i class="fa-regular fa-file-excel"></i>
                        </div>
                        <h3>Arrastra y soltá tu archivo excel aquí.</h3>
                        <p>o selecciona un archivo desde tu computadora.</p>

                        <input type="file" name="archivo" id="archivo" accept=".xlsx,.xls" hidden>
                        <label for="archivo" class="btn btn__azul"><i class="fa-solid fa-upload"></i>Seleccionar Archivo</label>
                        <p class="excel__archivo--seleccionado"></p>

                        <div class="excel__formatos">
                            <span>Formatos permitidos: .xlsx, .xls</span>
                            <span>Tamaño máximo: 10 MB</span>
                        </div>
                    </div>
                </div>
                <div class="excel__acciones">
                    <a href="/admin/dashboard" class="btn btn__transparente"> <i class="fa-solid fa-arrow-left"></i> Cancelar</a>

                    <div class="excel__acciones-right">
                        <button type="submit" class="excel__accion btn__azul">
                            <i class="fa-solid fa-file-import"></i>Procesar Archivo</button>
                    </div>
                </div>
            </form>
            

            <div class="excel__info">
            <div class="formulario__card">
                <div class="excel__info--header">
                    <i class="fa-solid fa-circle-info"></i>
                    <h3>Importante</h3>
                </div>

                <ul class="excel__lista">
                    <li>Asegurate de usar la plantilla oficial de productos.</li>
                    <li>El Excel debe contener SKU, precios y stock.</li>
                    <li>Los códigos SKU no pueden repetirse.</li>
                    <li>Las columnas obligatorias están marcadas en la plantilla.</li>
                </ul>
            </div>

            <div class="formulario__card">
                <div class="excel__info--header">
                    <h3>¿No tenés la plantilla?</h3>
                </div>

                <p class="excel__texto">Descargá la plantilla para cargar tus pedidos correctamente.</p>
                <button type="button" class="btn btn__transparente">
                    <i class="fa-solid fa-download"></i>
                    Descargar
                </button>
            </div>
        </div>
        </div>
        <?php endif; ?>

        <?php if($step == 2): ?>
        <div class="excel__validacion">
            <div class="excel__header">
                <h2>2.Validación de archivos</h2>
            </div>

            <div class="excel__stats">
                <div class="excel__stat">
                    <span class="excel__stat--numero"><?php echo $resultado['totalRegistros'] ?? 0; ?></span>
                    <p>Total Registros</p>
                </div>

                 <div class="excel__stat excel__stat--validos">
                    <span class="excel__stat--numero"><?php echo $resultado['nuevos'] ?? 0; ?></span>
                    <p>Registros Nuevos</p>
                </div>

                <div class="excel__stat excel__stat--validos">
                    <span class="excel__stat--numero"><?php echo $resultado['actualizados'] ?? 0; ?></span>
                    <p>Registros para Actualizar</p>
                </div>

                <div class="excel__stat excel__stat--error">
                    <span class="excel__stat--numero"><?php echo count($resultado['errores'] ?? []); ?></span>
                    <p>Errores Encontrados</p>
                </div>
            </div>

            <div class="excel__tabs">
                <button class="excel__tab excel__tab--active">Todos los Registros</button>
                <button class="excel__tab">Válidos</button>
                <button class="excel__tab">Errores</button>
            </div>

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
                    
                    <?php foreach($resultado['productosProcesados'] ?? [] as $producto): ?>
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

                    <div class="excel__ver-mas">
                        <a href="/admin/productos/preview" class="excel__accion btn__azul">Ver todos</a>
                    </div>
                </div>

                <aside class="excel__errores formulario__card">
                    <div class="excel__info--header">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <h3>Errores encontrados</h3>
                    </div>
                    <?php if(!empty($resultado['errores'])): ?>

                                <?php foreach($resultado['errores'] as $error): ?>
                                    <div class="excel__error">
                                        <span class="excel__error--fila">
                                            <?php echo $error['fila']; ?>
                                        </span>
                                        <p><?php echo $error['mensaje']; ?></p>
                                    </div>
                                <?php endforeach; ?>

                            <?php else: ?>

                                <div class="excel__error">
                                    <p>No se encontraron errores en la importación.</p>
                                </div>

                            <?php endif; ?>
                </aside>
            </div>
        </div>

        <div class="excel__acciones">
             <div></div>
                <div class="excel__acciones-right">
                    <form action="/admin/productos/confirmar" method="POST">
                        <?php $hayErrores = !empty($resultado['errores']); ?>
                        <a href="/admin/productos/confirmar" class="excel__accion btn__azul <?php echo $hayErrores ? 'boton__desabilitado': ''; ?>" <?php echo $hayErrores ? 'disabled' : '' ?>><i class="fa-solid fa-check"></i>Confirmar</a>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
</section>