<main class="login-simple">
    <section class="login__formulario">
        <div class="login__card"> 
            <div class="login__marca">
                <img src="/build/img/logo.jpg" alt="Logo Droguería FB">
            </div>

            <?php foreach($alertas as $tipo => $mensajes) { ?>
                <div class="login__icono login__icono--<?php echo $tipo; ?>">
                    <i class="fa-solid <?php echo $tipo === 'exito' ? 'fa-circle-check' : 'fa-circle-xmark'; ?>"></i>
                </div>

                <?php foreach($mensajes as $mensaje) { ?>
                    <h2><?php echo $titulo; ?></h2>
                    <p><?php echo $mensaje; ?></p>
                <?php } ?>
            <?php } ?>

            <div class="formulario__enlaces formulario__enlaces--crear">
                <a href="/login">Iniciar Sesión</a>
            </div>
        </div>
    </section>
</main>