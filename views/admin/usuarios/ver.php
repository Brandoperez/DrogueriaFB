<div class="clientes">
        <div class="pedidos__breadcrum">
            <a href="/admin/dashboard">Inicio</a>
            <span>/</span>
            <a href="/admin/clientes">Usuarios</a>
            <span>/</span>
            <p><?php echo $usuario->first_name . ' ' . $usuario->last_name; ?></p>
        </div>

        <div class="formulario__card">
            <h2><?php echo $usuario->first_name . ' ' . $usuario->last_name; ?></h2>

            <p><strong>Nombre: </strong><?php echo $usuario->first_name;?></p>
            <p><strong>Apellido: </strong><?php echo $usuario->last_name;?></p>
            <p><strong>Correo: </strong><?php echo $usuario->email;?></p>
            <p><strong>Rol: </strong><?php echo $usuario->role;?></p>
        </div>

        <div class="formulario__acciones--submit">
                <a href="/admin/usuarios" class="formulario__submit">Volver</a>
                <a href="/admin/usuarios/editar?id=<?php echo $usuario->id; ?>" class="formulario__submit">Editar Usuario</a>
        </div>
</div>

