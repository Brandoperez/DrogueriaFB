<main class="registro">
    <section class="registro__imagen">
        <div class="registro__overlay">
            <div class="registro__marca">
                <img src="/build/img/logo.jpg" alt="Logo de Droguería FB">
            </div>

            <div class="registro__contenido">
                <h1>Gestión de Clientes</h1>
                <p>Administrá el accesos para tus clientes al sistema de Droguería FB.</p>
            </div>

        </div>
    </section>

    <section class="registro__formulario">
        <div class="registro__heading">
            <i class="fa-regular fa-user"></i>
            <div>
                <h2>Editar Cliente</h2>
                <p>Modificá la información de tus clientes en Droguería FB.</p>
            </div>
        </div>

        <?php include_once __DIR__ . '/../../templates/alertas.php'; ?>

        <form action="/admin/clientes/editar?id=<?php echo $cliente->id; ?>" method="POST" class="formulario">
            <?php include __DIR__ . '/formularioEditar.php';?>

            <div class="formulario__acciones--submit">
                <a href="/admin/clientes" class="formulario__submit">Cancelar</a>
                <input type="submit" class="formulario__submit" value="Guardar Cambios">
            </div>
        </form>
    </section>
</main>