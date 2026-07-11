<section class="pedidos">
    <div class="pedidos__breadcrum">
        <a href="/cliente/clientes">Inicio</a>
        <span>/</span>
        <a href="/cliente/pedidos/listado">Pedidos</a>
        <span>/</span>
        <p>Carga Masiva de Pedidos</p>
    </div>

    <?php if(isset($_SESSION['alerta'])): ?>
        <script>window.ALERTA = <?= json_encode($_SESSION['alerta']) ?>;</script>
        <?php unset($_SESSION['alerta']); ?>
    <?php endif; ?>

    <div class="excel">
        <div class="excel__steps">
            <div class="excel__step <?= $step >= 1 ? 'excel__step--active' : '' ?>">
                <div class="excel__numero"><p>1</p></div>
                <div class="excel__contenido">
                    <h3>Subir Archivo</h3>
                    <p>Seleccionar Excel</p>
                </div>
            </div>
            <div class="excel__linea"></div>

            <div class="excel__step <?= $step >= 2 ? 'excel__step--active' : '' ?>">
                <div class="excel__numero"><p>2</p></div>
                <div class="excel__contenido">
                    <h3>Validar y Confirmar</h3>
                    <p>Revisar y crear pedido</p>
                </div>
            </div>
        </div>

        <?php if($step == 1): ?>
        <div class="excel__upload">
            <form action="/cliente/pedidos/excel" method="POST" enctype="multipart/form-data">
                <div class="excel__dropzone formulario__card">
                    <div class="excel__header">
                        <h2>Pedido para: <?php echo s($cliente->name); ?></h2>
                    </div>

                    <div class="excel__header">
                        <h2>Subir archivo Excel</h2>
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
                            <span>Columnas: Código de Producto | Cantidad</span>
                        </div>
                    </div>
                </div>

                <div class="excel__acciones">
                    <a href="/cliente/pedidos/listado" class="btn btn__transparente"><i class="fa-solid fa-arrow-left"></i> Cancelar</a>
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
                        <li>El Excel debe tener dos columnas: código de producto y cantidad.</li>
                        <li>Los precios se calculan automáticamente según tu lista de precios asignada.</li>
                    </ul>
                </div>

                <div class="formulario__card">
                    <div class="excel__info--header">
                        <h3>¿No tenés la plantilla?</h3>
                    </div>
                    <p class="excel__texto">Descargá la plantilla para cargar tu pedido correctamente.</p>
                    <a href="/cliente/pedidos/plantilla" class="btn btn__transparente"><i class="fa-solid fa-download"></i>Descargar</a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if($step == 2): ?>
        <div class="excel__validacion">
            <div class="excel__header">
                <h2>Validación del pedido — <?php echo s($cliente->name); ?></h2>
            </div>

            <div class="excel__stats">
                <div class="excel__stat">
                    <span class="excel__stat--numero"><?php echo $resultado['totalRegistros'] ?? 0; ?></span>
                    <p>Total Productos</p>
                </div>

                <div class="excel__stat excel__stat--validos">
                    <span class="excel__stat--numero"><?php echo $resultado['validos'] ?? 0; ?></span>
                    <p>Productos Válidos</p>
                </div>

                <div class="excel__stat excel__stat--error">
                    <span class="excel__stat--numero"><?php echo count($resultado['errores'] ?? []); ?></span>
                    <p>Errores Encontrados</p>
                </div>

                <div class="excel__stat">
                    <span class="excel__stat--numero">$<?php echo number_format($resultado['total'] ?? 0, 2, ',', '.'); ?></span>
                    <p>Total del Pedido</p>
                </div>
            </div>

                <div class="m__bottom formulario__card">
                    <div class="titulos--h2 m__bottom">
                        <h2>Productos del pedido</h2>
                    </div>
                    <div class="tabla tabla__grid--excel">
                        <span>Código</span>
                        <span>Producto</span>
                        <span>Cantidad</span>
                        <span>Precio</span>
                        <span>Subtotal</span>
                        <span>Estado</span>
                    </div>

                    <?php foreach($resultado['itemsProcesados'] ?? [] as $item): ?>
                        <div class="tabla tabla__fila--excel">
                            <span><?php echo s($item['code']); ?></span>
                            <span><?php echo s($item['description']); ?></span>
                            <span><?php echo $item['cantidad']; ?></span>
                            <span>$<?php echo number_format($item['precio'], 2, ',', '.'); ?></span>
                            <span>$<?php echo number_format($item['subtotal'], 2, ',', '.'); ?></span>
                            <div class="excel__estado <?php echo $item['estado'] === 'Válido' ? 'excel__estado--valido' : 'excel__estado--error'; ?>">
                                <?php echo $item['estado']; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <aside class="excel__errores formulario__card">
                    <div class="excel__info--header">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <h3>Errores encontrados</h3>
                    </div>

                    <?php if(!empty($resultado['errores'])): ?>
                        <?php foreach($resultado['errores'] as $error): ?>
                            <div class="excel__error">
                                <span class="excel__error--fila">Fila <?php echo $error['fila']; ?> — <?php echo s($error['codigo']); ?></span>
                                <?php foreach($error['errores'] as $mensaje): ?>
                                    <p><?php echo s($mensaje); ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="excel__error">
                            <p>No se encontraron errores en la importación.</p>
                        </div>
                    <?php endif; ?>
                </aside>

        </div>

        <div class="excel__acciones">
            <a href="/cliente/pedidos/excel" class="btn btn__transparente"><i class="fa-solid fa-arrow-left"></i>Volver</a>
            <div class="excel__acciones-right">
                <?php $hayErrores = !empty($resultado['errores']); ?>
                <form action="/cliente/pedidos/confirmar" method="POST">
                    <button type="submit" class="excel__accion btn__azul <?php echo $hayErrores ? 'boton__desabilitado' : ''; ?>" <?php echo $hayErrores ? 'disabled' : ''; ?>>
                        <i class="fa-solid fa-check"></i>Confirmar
                    </button>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>