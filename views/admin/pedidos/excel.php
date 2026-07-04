<section class="pedidos">
    <div class="pedidos__breadcrum">
        <a href="/admin/dashboard">Inicio</a>
        <span>/</span> 
        <a href="/admin/pedidos/listado">Pedidos</a>
        <span>/</span> 
        <p>Carga Masiva Mendiante Excel</p>
    </div>

    <div class="excel">
        <div class="excel__steps">
            <div class="excel__step excel__step--active">
                <div class="excel__numero">
                    <p>1</p>
                </div>
                <div class="excel__contenido">
                    <h3>Subir Archivo</h3>
                    <p>Seleccionar Excel</p>
                </div>
            </div>
            <div class="excel__linea"></div>

            <div class="excel__step excel__step--active">
                <div class="excel__numero">
                    <p>2</p>
                </div>
                <div class="excel__contenido">
                    <h3>Validar Datos</h3>
                    <p>Recibir Información</p>
                </div>
            </div>

            <div class="excel__linea"></div>

            <div class="excel__step">
                <div class="excel__numero">
                    <p>3</p>
                </div>
                <div class="excel__contenido">
                    <h3>Confirmar Carga</h3>
                    <p>Ver Resumen Final</p>
                </div>
            </div>

            <div class="excel__linea"></div>

            <div class="excel__step">
                <div class="excel__numero">
                    <p>4</p>
                </div>
                <div class="excel__contenido">
                    <h3>Resultado</h3>
                    <p>Carga Completa</p>
                </div>
            </div>
        </div>

        <div class="excel__upload">
            <div class="excel__dropzone formulario__card">
                <div class="excel__header">
                    <h2>1.Subir Archivo Excel</h2>
                </div>
                <div class="excel__zona">
                    <div class="excel__icono">
                        <i class="fa-regular fa-file-excel"></i>
                    </div>
                    <h3>Arrastra y soltá tu archivo excel aquí.</h3>
                    <p>o selecciona un achivo desde tu computadora.</p>

                    <button type="button" class="btn btn__azul">
                        <i class="fa-solid fa-upload"></i>Seleccionar Archivo
                    </button>

                    <div class="excel__formatos">
                        <span>Formatos permitidos: .xlsx, .xls</span>
                        <span>Tamaño máximo: 10 MB</span>
                    </div>
                </div>
            </div>

            <div class="excel__info">
            <div class="formulario__card">
                <div class="excel__info--header">
                    <i class="fa-solid fa-circle-info"></i>
                    <h3>Importante</h3>
                </div>

                <ul class="excel__lista">
                    <li>Asegurate de usar la plantilla oficial de pedidos.</li>
                    <li>El Excel debe contener los productos con sus cantidades.</li>
                    <li>Los códigos de productos deben existir en el sistema.</li>
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

        <div class="excel__validacion">
            <div class="excel__header">
                <h2>2.Validación de archivos</h2>
            </div>

            <div class="excel__stats">
                <div class="excel__stat">
                    <span class="excel__stat--numero">10</span>
                    <p>Total Registros</p>
                </div>

                <div class="excel__stat excel__stat--validos">
                    <span class="excel__stat--numero">20</span>
                    <p>Registros Cargados</p>
                </div>

                <div class="excel__stat excel__stat--error">
                    <span class="excel__stat--numero">4</span>
                    <p>Errores Encontrados</p>
                </div>
            </div>

            <div class="excel__tabs">
                <button class="excel__tab excel__tab--active">Todos los Registros</button>
                <button class="excel__tab">Válidos</button>
                <button class="excel__tab">Errores</button>
            </div>

            <div class="excel__contenido-carga">
                <div class="excel__tabla formulario__card">
                    <div class="tabla__header tabla__header--excel">
                        <span>Cliente</span>
                        <span>Producto</span>
                        <span>Cantidad</span>
                        <span>Estado</span>
                    </div>
                    <div class="tabla__fila excel__grid">
                        <span>Farmacia Central</span>
                        <span>Ibuprofeno 600mg</span>
                        <span>24</span>
                        <div class="excel__estado excel__estado--valido">Válido</div>
                    </div>
                    <div class="tabla__fila excel__grid">
                        <span>Farmacia Central</span>
                        <span>Ibuprofeno 600mg</span>
                        <span>24</span>
                        <div class="excel__estado excel__estado--error">Error</div>
                    </div>
                </div>
                <aside class="excel__errores formulario__card">
                    <div class="excel__info--header">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <h3>Errores encontrados</h3>
                    </div>
                    <div class="excel__error">
                        <span class="excel__error--fila">Fila 2</span>
                        <p>El producto no existe en el sistema</p>
                    </div>
                </aside>
            </div>
        </div>

        <div class="excel__acciones">

        <button type="button" class="btn btn__transparente"><i class="fa-solid fa-arrow-left"></i>Cancelar</button>
        <div class="excel__acciones-right">
            <button type="button" class="btn btn__transparente">Guardar borrador</button>
            <a href="/admin/pedidos/confirmar" class="btn btn__azul">
            <i class="fa-solid fa-file-import"></i>Procesar Archivo</a>
        </div>
</div>
    </div>
</section>