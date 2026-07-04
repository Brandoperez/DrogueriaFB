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
            <a href="#" class="btn btn__transparente"><i class="fa-solid fa-download"></i>Exportar</a>
        </div>

        <div class="pedidos__filtros">
        <div class="formulario__campo">
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha">
        </div>

        <div class="formulario__campo">
            <label for="vendedor">Vendedor</label>
            <select id="vendedor">
                <option>Todos los vendedores</option>
            </select>
        </div>

        <div class="formulario__campo">
            <label for="cliente">Cliente</label>
            <select id="cliente">
                <option>Todos los clientes</option>
            </select>
        </div>

        <div class="formulario__campo">
            <label for="estado">Estado</label>
            <select id="estado">
                <option>Todos los estados</option>
            </select>
        </div>

        <div class="formulario__campo">
            <label for="buscar">Buscar</label>
            <input type="text" name="buscar" id="buscar" placeholder="#Pedido o Cliente">
        </div>
    </div>

    <div class="pedidos__acciones-filtros">
            <button type="button" class="btn btn__transparente"><i class="fa-solid fa-rotate-left"></i>Limpiar filtros</button>
            <button type="button" class="btn btn__transparente"><i class="fa-solid fa-magnifying-glass"></i>Buscar Pedidos</button>
    </div>
</div>

<div class="pedidos__tabla formulario__card">
    <div class="pedidos__tabla--header">
        <h3>Pedidos Del Día </h3>
        <p>- Miercoles 24 de abril de 2026</p>
    </div>

    <div class="tabla__header pedidos__listado--grid">
            <span>N° Pedido</span>
            <span>Hora</span>
            <span>Cliente</span>
            <span>Vendedor</span>
            <span>Estado</span>
            <span>Total</span>
            <span>Acciones</span>
    </div>

    <div class="tabla__fila pedidos__listado--grid pedidos__fila">
            <span class="pedidos__pedido-id">#000325</span>
            <span>09:30</span>
            <span>Farmacia Central</span>
            <span>Martín Pérez</span>
            <div class="estado estado--proceso">
               <span>En proceso</span> 
            </div>
            <span>$345.200</span>
            <div class="tabla__acciones-tabla">
                    <button class="tabla__editar"><i class="fa-solid fa-eye"></i></button>
                    <button class="tabla__editar"><i class="fa-solid fa-ellipsis"></i></button>
            </div>
        </div>
</div>
