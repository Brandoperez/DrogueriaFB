<main class="login">
    <section class="login__imagen">
        <div class="login__overlay">
            <div class="login__contenido">
                <h1>Creá una nueva Contraseña</h1>
                <p>Elige una Contraseña segura para proteger el acceso a tu cuenta.</p>
            </div>
        </div>
    </section>
    <section class="login__formulario">
        <div class="login__card">
            <div class="login__marca">
                <img src="/build/img/logo.jpg" alt="Logo Droguería FB">
            </div>

            <h2>Reestablecer Contraseña</h2>
            <p>Ingresa tu nueva Contraseña para recuperar tu cuenta.</p>

            <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

            <?php if($token_valido) {  ?>

                <form action="/restablecer?token=<?php echo s($token); ?>" method="POST" class="formulario">
                    <div class="formulario__campo">
                            <label for="password">Contraseña:</label>
                            <div class="formulario__input--icono">
                                <i class="fa-solid fa-lock"></i>
                                <input type="password" id="password" name="password" placeholder="Minimo 8 caracteres">
                            </div>
                        </div>

                        <div class="formulario__campo">
                            <label for="password2">Repetir Contraseña:</label>
                            <div class="formulario__input--icono">
                                <i class="fa-solid fa-lock"></i>
                                <input type="password" id="password2" name="password2" placeholder="Repite tu Contraseña">
                            </div>
                        </div>

                        <input type="submit" class="formulario__submit" value="Guardar nueva contraseña">
                </form>
            <?php } ?>
            <div class="formulario__enlaces formulario__enlaces--crear">
            <span>¿Ya tenés acceso?</span>
            <a href="/login">Iniciar Sesión</a>
        </div>
        </div>
    </section>
</main>