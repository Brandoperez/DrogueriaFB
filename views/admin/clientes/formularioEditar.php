<fieldset class="formulario__section">
                <legend><i class="fa-solid fa-user"></i> Información de la Empresa</legend>
                    <div class="formulario__grid formulario__grid--2">
                        <div class="formulario__campo">
                            <label for="name">Empresa:</label>
                            <div class="formulario__input--icono">
                                <i class="fa-solid fa-building"></i>
                                <input type="text" id="name" name="name" placeholder="Ej:Farmacia Central" value="<?php echo $cliente->name;?>" >
                            </div> 
                        </div>

                        <div class="formulario__campo">
                            <label for="cuit">Cuit:</label>
                            <div class="formulario__input--icono">
                                <i class="fa-solid fa-id-card"></i>
                                <input type="text" id="cuit" name="cuit" placeholder="Ej:20-12345678-9" value="<?php echo $cliente->cuit;?>">
                            </div> 
                        </div>
                    </div>
            </fieldset>

            <fieldset class="formulario__section">
                <legend>Información de Contacto:</legend>
                    <div class="formulario__grid formulario__grid--2">
                        <div class="formulario__campo">
                            <label for="email">Correo Electrónico:</label>
                            <div class="formulario__input--icono">
                                <i class="fa-solid fa-envelope"></i>
                                <input type="email" id="email" name="email" placeholder="Ej: contacto@farmacia.com" value="<?php echo $cliente->email;?>">
                            </div>
                        </div>

                        <div class="formulario__campo">
                            <label for="phone">Teléfono:</label>

                            <div class="formulario__input--icono">
                                <i class="fa-solid fa-phone"></i>
                                <input type="tel" id="phone" name="phone" placeholder="Ej: 1122334455" value="<?php echo $cliente->phone; ?>">
                            </div>
                        </div>

                        <div class="formulario__campo">
                            <label for="type">Tipo de Cliente:</label>
                            <div class="formulario__input--icono">
                                <i class="fa-solid fa-location-dot"></i>
                                    <select id="type" name="type">
                                        <option value="">Seleccionar</option>
                                            <option value="farmacia" <?php echo $cliente->type === 'farmacia' ? 'selected' : ''; ?>>Farmacia</option>
                                            <option value="hospital" <?php echo $cliente->type === 'hospital' ? 'selected' : ''; ?>>Hospital</option>
                                            <option value="clinica" <?php echo $cliente->type === 'clinica' ? 'selected' : ''; ?>>Clínica</option>
                                            <option value="municipalidad" <?php echo $cliente->type === 'municipalidad' ? 'selected' : ''; ?>>Municipalidad</option>
                                            <option value="drogueria" <?php echo $cliente->type === 'drogueria' ? 'selected' : ''; ?>>Droguería</option>
                                            <option value="otro" <?php echo $cliente->type === 'otro' ? 'selected' : ''; ?>>Otro</option>
                                    </select>
                            </div>
                        </div>

                        <div class="formulario__campo">
                            <label for="address">Direccion:</label>
                            <div class="formulario__input--icono">
                                <i class="fa-solid fa-location-dot"></i>
                                <input type="text" id="address" name="address" placeholder="Ej: Viamonte 1727" value="<?php echo $cliente->address; ?>">
                            </div>
                        </div>

                        <div class="formulario__campo">
                            <label for="province">Provincia:</label>
                            <div class="formulario__input--icono">
                                 <i class="fa-solid fa-map-location-dot"></i>
                                 <select id="province" name="province">
                                    <option value="">Seleccioná una Provincia</option>
                                    <?php foreach($provincias as $provincia): ?>
                                        <option value="<?php echo $provincia; ?>" <?php echo $cliente->province === $provincia ? 'selected' : ''; ?>>
                                            <?php echo $provincia; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="formulario__campo">
                            <label for="locality">Localidad:</label>
                            <div class="formulario__input--icono">
                                <i class="fa-solid fa-map-pin"></i>
                                <select id="locality" name="locality" data-actual="<?php echo $cliente->locality; ?>">
                                    <option value="">Seleccioná primero una Provincia</option>
                                </select>
                            </div>
                        </div>
                    </div>
            </fieldset>

            <fieldset class="formulario__section">
                <legend> <i class="fa-solid fa-tags"></i> Configuración Comercial</legend>

                <div class="formulario__campo">
                    <label for="price_list_id">Lista de Precios:</label>
                    <div class="formulario__input--icono">
                        <i class="fa-solid fa-tags"></i>
                        <select id="price_list_id" name="price_list_id">
                            <option value="">Seleccione una lista</option>

                            <?php foreach($listaPrecios as $lista) : ?>
                                <option
                                    value="<?php echo $lista->id; ?>"
                                    <?php echo $cliente->price_list_id == $lista->id ? 'selected' : ''; ?>
                                >
                                    <?php echo $lista->name; ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                    </div>
                </div>

                <div class="formulario__campo">
                    <label for="seller_id">Vendedor:</label>
                        <div class="formulario__input--icono">
                            <i class="fa-solid fa-user-tie"></i>
                            <select name="seller_id" id="seller_id">
                                <option value="" disabled>Seleccionar Vendedor</option>
                                    <?php foreach($vendedores as $vendedor): ?>
                                        <option value="<?php echo $vendedor->id;?>" <?php echo $cliente->seller_id == $vendedor->id ? 'selected' : ''; ?>><?php echo $vendedor->first_name . ' ' . $vendedor->last_name; ?></option>
                                    <?php endforeach; ?>
                            </select>
                        </div>
                </div>
            </fieldset>