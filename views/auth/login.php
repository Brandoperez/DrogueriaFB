<main class="login">
    <section class="login__imagen">
        <div class="login__overlay">

            <div class="login__contenido">
                <h1>Suministrando salud a tu negocio.</h1>
                <p>Distribución de medicamentos e insumos para farmacias, clínicas, hospitales, droguerías y municipalidades.</p>
            </div>

            <div class="login__beneficios">
                <div class="login__beneficio">
                    <i class="fa-solid fa-truck-fast"></i>
                    <div class="login__beneficio--texto">
                       <strong>Entrega rápida</strong>
                       <p>Envios seguros a todo el país.</p> 
                    </div>
                </div>

                <div class="login__beneficio">
                    <i class="fa-solid fa-shield-heart"></i>
                    <div class="login__beneficio--texto">
                       <strong>Confianza</strong>
                       <p>Más de 20 años de experiencia.</p> 
                    </div>
                </div>

                <div class="login__beneficio">
                    <i class="fa-solid fa-certificate"></i>
                     <div class="login__beneficio--texto">
                       <strong>Calidad</strong>
                       <p>Productos originales y certificados.</p> 
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

            <h2>Acceso al sistema</h2>
            <p>Ingresa sus credenciales para continuar.</p>

            <?php include_once  __DIR__ . '/../templates/alertas.php'; ?>

            <form action="/login" method="POST" class="formulario">

                <div class="formulario__campo">
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" placeholder="ejemplo@gmail.com" value="<?php echo s($_POST['email'] ?? $_COOKIE['recordarme_email'] ?? ''); ?>">
                </div>

                <div class="formulario__campo">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" placeholder="Ingresa tu Contraseña">
                </div>

                <div class="formulario__enlaces formulario__enlaces--login">
                    <label><input type="checkbox" name="recordarme">Recordarme</label>
                    <a href="/olvide">¿Olvidaste tu Contraseña?</a>
                </div>

                <input type="submit" class="formulario__submit" value="Ingresar al sistema">
            </form>

            <div class="formulario__enlaces formulario__enlaces--login">
                <span>¿No tenés cuenta?</span>
                <a href="/registro">Crear Una</a>
            </div>
        </div>
    </section>
</main>