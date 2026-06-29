<div class="pedidos">
    <div class="pedidos__breadcrum">
        <a href="/admin/dashboard">Inicio</a>
        <span>/</span>
        <a href="/admin/productos/listado">Productos</a>
        <span>/</span>
        <p>Historial de Cargas</p>
    </div>

        <div class="productos__header formulario__card">
            <div class="pedidos__header-top">
                <h2>Filtros de Búsqueda</h2>
                <a href="#" class="pedidos__exportar"><i class="fa-solid fa-download"></i>Exportar</a>
            </div>

        <div class="pedidos__filtros">

            <div class="formulario__campo">
                <label for="fecha">Fecha</label>
                <input type="date" id="fecha" name="fecha">
            </div>

            <div class="formulario__campo">
                <label for="usuario">Usuario</label>
                <select id="usuario">
                    <option>Todos los usuarios</option>
                </select>
            </div>

            <div class="formulario__campo">
                <label for="estado">Estado</label>
                <select id="estado">
                    <option>Todos los estados</option>
                </select>
            </div>

            <div class="formulario__campo">
                <label for="archivo">Tipo de archivo</label>
                <select id="archivo">
                    <option>Todos los archivos</option>
                </select>
            </div>

            <div class="formulario__campo">
                <label for="buscar">Buscar</label>
                <input type="text"  id="buscar" placeholder="#Carga o nombre de archivo">
            </div>
        </div>

        <div class="pedidos__acciones-filtros">
            <button type="button" class="pedidos__limpiar"><i class="fa-solid fa-rotate-left"></i>Limpiar filtros</button>
            <button type="button" class="pedidos__buscar"><i class="fa-solid fa-magnifying-glass"></i>Buscar cargas</button>
        </div>

    </div>

    <div class="pedidos__tabla formulario__card">
    <div class="pedidos__tabla--header">
        <h3>Cargas Del Día </h3>
        <p>- Miercoles 24 de abril de 2026</p>
    </div>

    <div class="tabla__header pedidos__listado--grid">
            <span>N° Carga De Productos</span>
            <span>Hora</span>
            <span>Usuario</span>
            <span>Rol</span>
            <span>Tipo de archivo</span>
            <span>Estado</span>
            <span>Acciones</span>
    </div>

    <div class="tabla__fila pedidos__listado--grid pedidos__fila">
            <span class="pedidos__pedido-id">#000325</span>
            <span>09:30</span>
            <span>Martín Pérez</span>
            <span>Administrador</span>
            <span>Excel</span>
            <div class="estado estado--proceso">
               <span>En proceso</span> 
            </div>
            <div class="tabla__acciones-tabla">
                    <button class="tabla__editar"><i class="fa-solid fa-eye"></i></button>
                    <button class="tabla__editar"><i class="fa-solid fa-ellipsis"></i></button>
            </div>
        </div>
</div>

</div>