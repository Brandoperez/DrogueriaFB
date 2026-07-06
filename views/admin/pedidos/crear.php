<section class="pedidos">
    <div class="pedidos__breadcrum">
        <a href="/admin/dashboard">Inicio</a>
        <span>/</span> 
        <a href="/admin/pedidos/listado">Pedidos</a>
        <span>/</span> 
        <p>Carga Manual</p>
    </div>

    <form action="/admin/pedidos/crear" method="POST" class="formulario">
        <section class="formulario__card">
            <div class="formulario__campos">
                <div class="formulario__campo">
                    <label for="cliente">Cliente:</label>
                    <input type="text" name="cliente" id="cliente" placeholder="Buscar cliente por nombre o CUIT.">
                    <input type="hidden" name="cliente_id" id="cliente_id">
                    <div id="resultado-clientes" class="pedidos__resultados"></div>
                </div>
            </div>
        </section>

        <section>
            <div class="formulario__card m__botom">
                <div class="titulos--h2">
                    <h2>Agregar Productos</h2>
                    <p>Agrega el producto y la cantidad.</p>
                </div>
                <div class="pedidos__productos">
                    <div class="formulario__campo pedidos__producto--busqueda">
                        <label for="producto">Producto:</label>
                        <input type="text" id="producto" name="producto" placeholder="Buscar por nombre, laboratorio o SKU">
                        <input type="hidden" id="producto_id" name="producto_id">
                    </div>

                    <div class="formulario__campo pedidos__cantidad">
                        <label for="cantidad">Cantidad:</label>
                        <input type="number" id="cantidad" name="cantidad" min="1" placeholder="0">
                    </div>

                    <button type="button" class="btn btn__azul pedidos__agregar"><i class="fa-solid fa-plus"></i>Agregar</button>
                </div>

                    <div id="resultado-productos" class="pedidos__resultados"></div>
            </div>
            
            <div class="formulario__card">
                <div class="titulos--h2">
                    <h2>Productos del Pedido</h2>
                </div>
                <div class="tabla__productos">
                    <div class="tabla tabla__grid--pedidos">
                        <span>Producto</span>
                        <span>Cantidad</span>
                        <span>Precio</span>
                        <span>Subtotal</span>
                        <span>Acciones</span>
                    </div>

                    <div class="tabla__vacia">
                        <i class="fa-solid fa-box-open"></i>
                        <p>No hay productos agregados al pedido.</p>
                    </div>
                </div>
            </div>

            <div class="formulario__card">
                <div class="formulario__campo">
                    <label for="observaciones">Observaciones:</label>
                    <textarea name="observaciones" id="observaciones" placeholder="Escribí alguna observación si es necesario..."></textarea>
                </div>
            </div>
            <input type="hidden" name="productos" id="productos_json">
        </section>

        <div class="excel__acciones"> <!--Arreglar CSS-->
            <a href="/admin/dashboard" class="btn btn__transparente"> <i class="fa-solid fa-arrow-left"></i> Cancelar</a>

            <div class="excel__acciones-right">
                <button type="submit" class="excel__accion btn__azul"><i class="fa-solid fa-file-import"></i>Confirmar</button>
            </div>
        </div>
    </form>
</section>

