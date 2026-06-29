<main class="login">
    <section class="login__imagen">
        <div class="login__overlay">
            <div class="login__contenido">
                <h1>Recupera el acceso a tu cuenta</h1>
                <p>Te enviaremos las instrucciones necesarias para que recuperes tu cuenta de forma segura.</p>
            </div>

            <div class="login__beneficios">
                <div class="login__beneficio">
                    <i class="fa-solid fa-shield-heart"></i>
                    <div class="login__beneficio--texto">
                        <strong>Proceso Seguro</strong>
                        <p>Protegemos el acceso a tu cuenta.</p>
                    </div>
                </div>

                <div class="login__beneficio">
                    <i class="fa-solid fa-envelope"></i>
                    <div class="login__beneficio--texto">
                        <strong>Correo de Recuperación</strong>
                        <p>Recibirás un correo al email que registraste.</p>
                    </div>
                </div>

                <div class="login__beneficio">
                    <i class="fa-solid fa-clock"></i>
                    <div class="login__beneficio--texto">
                        <strong>Acceso Rápido</strong>
                        <p>Reestablecé tu Contraseña en poco minutos.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="login__formulario">
        <div class="login__card">
            <div class="login__marca">
                <img src="/build/img/logo.jpg" alt="Logo Droguería FB">
            </div>

            <h2>Olvidé mi Contraseña</h2>
            <p>Ingresá tu Correo Electrónico y te enviaremos un enlace para recuperar tu acceso.</p>

            <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

            <form action="/olvide" method="POST" class="formulario">
                <div class="formulario__campo">
                    <label for="email">Correo Electrónico:</label>
                         <div class="formulario__input--icono">
                            <i class="fa-solid fa-envelope"></i>
                            <input type="email" id="email" name="email" placeholder="Ej: contacto@farmacia.com">
                         </div>
                </div>

                <input type="submit" class="formulario__submit" value="Enviar Instrucciones">
            </form>

            <div class="formulario__enlaces formulario__enlaces--crear">
                <span>¿Recordaste tu contraseña?</span>
                <a href="/login">Iniciar sesión</a>
            </div>
        </div>
    </section>
</main>