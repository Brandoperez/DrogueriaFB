<div class="pedidos">
    <div class="pedidos__breadcrum">
        <a href="/admin/dashboard">Inicio</a>
        <span>/</span>
        <a href="/admin/pedidos/listado">Pedidos</a><span>/</span>
        <p>Confirmar carga</p>
    </div>

    <div class="confirmar">
        <div class="excel__steps">
            <div class="excel__step excel__step--active">
                <div class="excel__numero">1</div>
                <div class="excel__contenido">
                    <h3>Subir archivo</h3>
                    <p>Seleccionar Excel</p>
                </div>
            </div>

            <div class="excel__linea"></div>

            <div class="excel__step excel__step--active">
                <div class="excel__numero">2</div>
                <div class="excel__contenido">
                    <h3>Validar datos</h3>
                    <p>Revisar información</p>
                </div>
            </div>

            <div class="excel__linea"></div>

            <div class="excel__step excel__step--active">
                <div class="excel__numero">3</div>

                <div class="excel__contenido">
                    <h3>Confirmar carga</h3>
                    <p>Ver resumen final</p>
                </div>
            </div>

            <div class="excel__linea"></div>

            <div class="excel__step">
                <div class="excel__numero">4</div>

                <div class="excel__contenido">
                    <h3>Resultado</h3>
                    <p>Carga completada</p>
                </div>
            </div>
        </div>

        <div class="confirmar__tabla formulario__card">
            <div class="confirmar__header">
                <h2>Productos cargados</h2>
                <p> Revisá la información antes de confirmar.</p>
            </div>

            <div class="tabla__header confirmar__grid">
                <span>Farmacia</span>
                <span>Producto</span>
                <span>Cantidad</span>
                <span>Precio</span>
                <span>Subtotal</span>
                <span>Acciones</span>
            </div>

            <div class="tabla__fila confirmar__grid">
                <span>Farmacia Central</span>
                <span>Ibuprofeno 600mg</span>
                <span>24</span>
                <span>$5.200</span>
                <span>$124.800</span>
                <div class="tabla__acciones-tabla">
                    <button class="tabla__editar"><i class="fa-solid fa-pen"></i></button>
                    <button class="tabla__eliminar"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
        </div>

        <div class="excel__acciones">

        <button type="button" class="excel__accion excel__accion--secundaria"><i class="fa-solid fa-arrow-left"></i>Cancelar</button>
        <div class="excel__acciones-right">
            <a href="/admin/pedidos/resultado" class="excel__accion excel__accion--primary">Confirmar</a>
        </div>
    </div>
</div>
</div>
