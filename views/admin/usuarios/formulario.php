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
                                    <option value="" disabled>Seleccioná un rol</option>
                                    <option value="admin"<?php echo ($usuario->role === 'admin' ? 'selected' : ''); ?>>Administrador</option>
                                    <option value="seller"    <?php echo ($usuario->role === 'seller'    ? 'selected' : ''); ?>>Vendedor</option>
                                    <option value="logistics" <?php echo ($usuario->role === 'logistics' ? 'selected' : ''); ?>>Logística</option>
                                 </select>
                            </div>
                        </div>
                    </div>
            </fieldset>