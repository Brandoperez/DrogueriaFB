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
                    <select name="cliente_id" id="cliente">
                        <option value="">Seleccionar Cliente</option>
                    </select>
                </div>

                <div class="formulario__campo">
                    <label for="vendedor">Vendedor:</label>
                    <select name="vendedor_id" id="vendedor">
                        <option value="">Seleccionar Vendedor</option>
                    </select>
                </div>

                <div class="formulario__campo">
                    <label for="fecha">Fecha del pedido:</label>
                    <input type="date" name="fecha" id="fecha">
                </div>

                <div class="formulario__campo formulario__campo--full">
                    <label for="observaciones">Observaciones:</label>
                    <textarea name="observaciones" id="observaciones" placeholder="Agregar observaciones del pedido"></textarea>
                </div>
            </div>
        </section>

        <section class="pedidos__grid--productos">
            <div class="formulario__card">
                <div class="pedidos__card--titulo">
                    <h3>Agregar Productos</h3>
                    <p>Agrega el producto y la cantidad.</p>
                </div>
                <div class="pedidos__productos">
                    <div class="formulario__campo">
                        <label for="producto">Producto:</label>
                        <input type="text" id="producto" name="producto" placeholder="Buscar por nombre o SKU">
                    </div>

                    <div class="formulario__campo">
                        <label for="cantidad">Cantidad:</label>
                        <input type="number" id="cantidad" name="cantidad" min="1" placeholder="0">
                    </div>

                    <button type="button" class="pedidos__agregar"><i class="fa-solid fa-plus"></i>Agregar</button>
                </div>
            </div>

            <div class="formulario__card">
                <div class="pedidos__card--titulo">
                    <h3>Productos en el Pedido</h3>
                    <p>Listado de productos agregados al pedido</p>
                </div>
                <div class="tabla__productos">
                    <div class="tabla__header">
                        <span>Producto</span>
                        <span>Cantidad</span>
                        <span>Precio</span>
                        <span>Subtotal</span>
                        <span></span>
                    </div>

                    <div class="tabla__vacia">
                        <i class="fa-solid fa-box-open"></i>
                        <p>No hay productos agregados al pedido.</p>
                    </div>
                </div>
            </div>
        </section>
    </form>
</section>

