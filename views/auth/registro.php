<main class="registro">
    <section class="registro__imagen">
        <div class="registro__overlay">
            <div class="registro__marca">
                <img src="/build/img/logo.jpg" alt="Logo de Droguería FB">
            </div>

            <div class="registro__contenido">
                <h1>Creamos alianzas para cuidar la salud</h1>
                <p>Registrate y accedé a nuestro sistema de pedidos mayoristas de medicamentos e insumos.</p>
            </div>

            <div class="registro__beneficios">
                <div class="registro__beneficio">
                    <i class="fa-solid fa-shield-heart"></i>
                    <div class="login__beneficio--texto">
                       <strong>Confianza y Experiencia</strong>
                       <p>Más de 20 años abasteciendo a farmacias, hospitales y centros de salud.</p> 
                    </div>
                </div>

                <div class="registro__beneficio">
                    <i class="fa-solid fa-truck-fast"></i>
                    <div class="login__beneficio--texto">
                       <strong>Envios rápidos y Seguros</strong>
                       <p>LLegamos a todo el país con seguimineto en tiempo real.</p> 
                    </div>
                </div>

                <div class="registro__beneficio">
                    <i class="fa-solid fa-certificate"></i>
                    <div class="login__beneficio--texto">
                       <strong>Productos de Calidad</strong>
                       <p>Trabajamos con los mejores laboratorios y estándares del mercado.</p> 
                    </div>
                </div>
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

        <?php include_once __DIR__ . '/../templates/alertas.php';?>

        <form action="/admin/usuarios/crear" method="POST" class="formulario">
            <fieldset class="formulario__section">
                <legend><i class="fa-solid fa-user"></i> Información Personal</legend>
                    <div class="formulario__grid formulario__grid--2">
                        <div class="formulario__campo">
                            <label for="first_name">Nombre:</label>
                            <div class="formulario__input--icono">
                                <i class="fa-solid fa-user"></i>
                                <input type="text" id="first_name" name="first_name" placeholder="Ej:Andrés" value="<?php echo $usuario->first_name;?>" >
                            </div> 
                        </div>

                        <div class="formulario__campo">
                            <label for="last_name">Apellido:</label>
                            <div class="formulario__input--icono">
                                <i class="fa-solid fa-user"></i>
                                <input type="text" id="last_name" name="last_name" placeholder="Ej:Pérez" value="<?php echo $usuario->last_name;?>">
                            </div> 
                        </div>
                    </div>
            </fieldset>

            <fieldset class="formulario__section">
                <legend>Información de Acceso:</legend>
                    <div class="formulario__grid formulario__grid--2">
                        <div class="formulario__campo">
                            <label for="email">Correo Electrónico:</label>
                            <div class="formulario__input--icono">
                                <i class="fa-solid fa-envelope"></i>
                                <input type="email" id="email" name="email" placeholder="Ej: contacto@farmacia.com" value="<?php echo $usuario->email;?>">
                            </div>
                        </div>

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

                        <div class="formulario__campo">
                            <label for="role">Rol:</label>
                            <div class="formulario__input--icono">
                                 <i class="fa-solid fa-shield-halved"></i>
                                 <select id="role" name="role">
                                    <option value="">Seleccioná un rol</option>
                                    <option value="admin"<?php echo ($usuario->role === 'admin' ? 'selected' : ''); ?>>Administrador</option>
                                    <option value="seller"    <?php echo ($usuario->role === 'seller'    ? 'selected' : ''); ?>>Vendedor</option>
                                    <option value="logistics" <?php echo ($usuario->role === 'logistics' ? 'selected' : ''); ?>>Logística</option>
                                 </select>
                            </div>
                        </div>
                    </div>
            </fieldset>

            <input type="submit" class="formulario__submit" value="Crear Cuenta">
        </form>

        <div class="formulario__enlaces formulario__enlaces--crear">
            <span>¿Ya tenés Cuenta?</span>
            <a href="/login">Iniciar Sesión</a>
        </div>
    </section>
</main>