<div class="clientes">
    <div class="pedidos__breadcrum">
        <a href="/admin/dashboard">Inicio</a>
        <span>/</span>
        <p>Clientes</p>
    </div>

    <div class="clientes__header formulario__card">
        <div class="clientes__header-top">
            <div class="clientes__header--info">
                <h2>Gestión de Clientes</h2>
                <p>Administrá clientes, saldos y estados.</p>
            </div>

            <div class="clientes__acciones">
                <a href="#" class="btn btn__azul">Modificar Saldos</a>
                <a href="/admin/clientes/crear" class="btn btn__azul"><i class="fa-solid fa-plus"></i>Nuevo Cliente</a>
            </div>
        </div>

        <div class="clientes__filtros">
            <div class="formulario__campo">
                <label for="buscar">Buscar</label>
                <input type="text" name="buscar" id="buscar" placeholder="Busca por Nombre o Cuit">
            </div>
            <div class="formulario__campo">
                <label for="vendedores">Vendedores</label>
                <select id="vendedor" name="vendedor">
                    <option value="">Todos los Vendedores</option>
                    <?php foreach($vendedores as $vendedor): ?>
                    <option value="<?php echo $vendedor->id; ?>"><?php echo $vendedor->first_name . ' ' . $vendedor->last_name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="formulario__campo">
                <label for="estado">Estados</label>
                <select name="estado" id="estado">
                    <option value="">Todos los estados</option>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
            
            <div class="formulario__campo">
                <label for="localidad">Provincias</label>
                <select name="localidad" id="localidad">
                    <option value="">Todas las Provincias</option>
                    <?php foreach($provincias as $provincia): ?>
                        <option value="<?php echo $provincia; ?>">
                            <?php echo $provincia; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="clientes__acciones--filtros">
            <button type="button" id="btnLimpiarFiltros" class="btn btn__transparente"><i class="fa-solid fa-rotate-left"></i>Limpiar Filtros</button>
            <button type="button" id="btnBuscarClientes" class="btn btn__transparente"><i class="fa-solid fa-magnifying-glass"></i>Buscar Clientes</button>
        </div>
    </div>

    <div class="clientes__tabla formulario__card">
        <div class="clientes__tabla-header">
            <h3>Listado de Clientes</h3>
        </div>

        
        <div class="tabla tabla__grid--clientes">
            <span>Cliente</span>
            <span>CUIT</span>
            <span>Provincia</span>
            <span>Vendedor</span>
            <span>Lista</span>
            <span>Estado</span>
            <span>Acciones</span>
        </div>


        <?php if(!empty($clientes)) : ?>
            <?php foreach($clientes as $cliente) : ?>
        <div class="tabla__fila--clientes">
            <span class="clientes__nombre"><?php echo $cliente->name; ?></span>
            <span><?php echo $cliente->cuit; ?></span>
            <span><?php echo $cliente->province; ?></span>
            <span> <?php $vendedor = $cliente->vendedor(); echo $vendedor ? $vendedor->first_name . ' ' . $vendedor->last_name : 'Sin vendedor'; ?></span>
            <span><?php $lista = $cliente->listaPrecios(); echo $lista ? $lista->name : 'Sin lista'; ?></span>
            <a href="#" class="estado js-cambiar-estado <?php echo $cliente->active ? 'estado--completado' : 'estado--cancelado'; ?>"
            data-id="<?php echo $cliente->id; ?>" data-estado="<?php echo $cliente->active; ?>"><?php echo $cliente->active ? 'Activo' : 'Inactivo'; ?></a>

            <div class="acciones--tabla">
                <a href="/admin/clientes/ver?id=<?php echo $cliente->id; ?>" class="acciones__editar"><i class="fa-solid fa-eye"></i></a>
                <a href="/admin/clientes/editar?id=<?php echo $cliente->id; ?>" class="acciones__editar js-editar"><i class="fa-solid fa-pen"></i></a>
                <a href="/admin/clientes/eliminar?id=<?php echo $cliente->id; ?>" class="acciones__eliminar js-eliminar"><i class="fa-solid fa-trash"></i></a>
            </div>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
            <p class="tabla__vacia">No hay clientes registrados</p>

    <?php endif; ?>
    </div>
</div>