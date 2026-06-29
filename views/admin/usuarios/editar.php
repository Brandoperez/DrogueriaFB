<main class="registro">
    <section class="registro__imagen">
        <div class="registro__overlay">
            <div class="registro__marca">
                <img src="/build/img/logo.jpg" alt="Logo de Droguería FB">
            </div>

            <div class="registro__contenido">
                <h1>Gestión de Usuarios</h1>
                <p>Administrá accesos, permisos y roles del sistema interno de Droguería FB.</p>
            </div>

        </div>
    </section>

    <section class="registro__formulario">
        <div class="registro__heading">
            <i class="fa-regular fa-user"></i>
            <div>
                <h2>Nuevo Usuario</h2>
                <p>Completá la información para crear la cuenta de tus usuarios en Droguería FB.</p>
            </div>
        </div>

        <?php include_once __DIR__ . '/../../templates/alertas.php'; ?>

        <form action="/admin/usuarios/editar?id=<?php echo $usuario->id; ?>" method="POST" class="formulario">
            <?php include __DIR__ . '/formularioEditar.php'; ?>

            <div class="formulario__acciones--submit">
                <a href="/admin/usuarios" class="formulario__submit">Cancelar</a>
                <input type="submit" class="formulario__submit" value="Editar Usuario">
            </div>
            
        </form>
    </section>
</main>