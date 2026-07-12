<?php include_once __DIR__ . '/../../templates/alertas.php'; ?>
<div class="clientes">
    <div class="pedidos__breadcrum">
        <a href="/admin/dashboard">Inicio</a>
        <span>/</span>
        <p>Usuarios</p>
        <span>/</span>
        <p>Gestión de Usuarios</p>
    </div>

    <div class="clientes__header formulario__card">
        <div class="clientes__header-top">
            <div class="clientes__header--info">
                <h2>Usuarios del Sistema</h2>
                <p>Administración general de usuarios y permisos.</p>
            </div>

            <div class="clientes__acciones">
                <a href="/admin/usuarios/crear" class="btn btn__azul"><i class="fa-solid fa-plus"></i>
                Nuevo Usuario</a>
            </div>
        </div>

    <div class="formulario__card">
        <div class="formulario__campo formulario__buscador">
            <h3>Listado de usuarios</h3>
            <div class="formulario__input--icono">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="buscarUsuarios" placeholder="Buscar usuario...">
            </div>
        </div>

        <div class="tabla tabla__grid--usuarios">
            <span>Nombre</span>
            <span>Apellido</span>
            <span>Email</span>
            <span>Rol</span>
            <span>Estado</span>
            <span>Acciones</span>
        </div>

        <?php if(!empty($usuarios)) : ?>
            <?php foreach($usuarios as $usuario) : ?>
        <div class="tabla tabla__fila--usuarios">
            <span class="clientes__nombre"><?php echo $usuario->first_name ?></span>
            <span class="clientes__nombre"><?php echo $usuario->last_name ?></span>
            <span><?php echo $usuario->email ?></span>
            <span><?php echo $usuario->role ?></span>
            <span><?php echo $usuario->active ? 'Active' : 'Inactive'; ?></span>
            <div class="acciones--tabla">
                <a href="/admin/usuarios/ver?id=<?php echo $usuario->id; ?>" class="acciones__editar"><i class="fa-solid fa-eye"></i></a>
                <a href="/admin/usuarios/editar?id=<?php echo $usuario->id; ?>" class="acciones__editar"><i class="fa-solid fa-pen"></i></a>
                <a href="/admin/usuarios/eliminar?id=<?php echo $usuario->id; ?>" class="acciones__eliminar js-eliminar"><i class="fa-solid fa-trash"></i></a>
            </div>
        </div>
            <?php endforeach; ?>
                <?php else : ?>
                    <p class="tabla__vacia">No hay usuarios registrados</p>
                <?php endif; ?>
    </div>
</div>