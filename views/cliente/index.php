<div class="clientes">
    <div class="cliente__header">
        <div class="cliente__nombre">
            <h2>¡Bienvenido, <span><?php echo s(sessionUser('first_name')); ?>!</span> </h2>
        </div>
        <div class="cliente__usuario">
                <div>
                    <p><?php echo s(sessionUser('first_name')); ?></p>
                    <span>Cliente Droguería FB</span>
                </div>
                <div class="cliente__avatar">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
        </div>
    </div>
    
    <div class="pedidos__breadcrum">
        <a href="/admin/clientes">Clientes</a>
        <span>/</span>
        <p>Detalles del cliente</p>
    </div>

    <div class="cliente__card">
        <div class="cliente__perfil">
            <div class="cliente__iniciales">
                <p>FS</p>
            </div>

            <div class="cliente__info">
                <h3>Farmacia del Sol</h3>
                <span class="estado estado--completado">Activo</span>

                <div class="cliente__datos">
                    <p><i class="fa-solid fa-id-card"></i><strong>CUIT:</strong><span>20-12345678-9</span></p>
                    <p><i class="fa-solid fa-envelope"></i><strong>Email:</strong><span>contacto@farmaciadelsol.com</span></p>
                    <p><i class="fa-solid fa-phone"></i><strong>Teléfono:</strong><span>341 555-1234</span></p>
                    <p><i class="fa-solid fa-location-dot"></i><strong>Dirección:</strong><span>Av. San Martín 1234, Rosario, Santa Fe</span></p>
                    <p><i class="fa-solid fa-list"></i><strong>Lista de precios:</strong><span>Lista 2 - Preferencial</span></p>
                    <p><i class="fa-solid fa-user"></i><strong>Vendedor asignado:</strong><span>Martín Pérez</span></p>
                </div>
            </div>
        </div>

        <div class="cliente__sidebar">
            <div class="cliente__acciones">
                <h3>Acciones rápidas</h3>

                <div class="accion">
                    <a href="/cliente/pedidos/crear" class="cliente__boton ">
                        <i class="fa-solid fa-cart-shopping"></i>
                        Crear Pedido Manual
                    </a>

                    <a href="/cliente/pedidos/excel" class="cliente__boton cliente__boton--secundario">
                        <i class="fa-solid fa-upload"></i>
                        Carga de Lista Diaria (Excel)
                    </a>

                    <a href="/cliente/pedidos/listado" class="cliente__boton cliente__boton--secundario">
                        <i class="fa-solid fa-list"></i>
                        Ver Listado de Pedidos
                    </a>
                </div>
                    
            </div>

             <div class="cliente__resumen">
                <div class="cliente__btn">
                    <a href="#" class="btn btn__azul">Lista de Precios</a>
                </div>
            </div>
        </div>
    </div>

    <div class="cliente__pedidos">
        <div class="cliente__tabs">
            <button class="cliente__tab cliente__tab--active"><i class="fa-solid fa-list"></i>Pedidos Realizados</button>
            <button class="cliente__tab"><i class="fa-solid fa-dollar-sign"></i>Historial de Saldos</button>
        </div>
        <div class="formulario__card">
            <div class="tabla tabla__grid--listado-pe">
                <span>N° Pedido</span>
                <span>Fecha</span>
                <span>Hora</span>
                <span>Vendedor</span>
                <span>Estado</span>
                <span>Total</span>
                <span>Acciones</span>
            </div>

            <div class="tabla tabla__fila--listado-pe pedidos__fila">
                <span>000325</span>
                <span>24/04/2024</span>
                <span>09:30</span>
                <span>Martín Pérez</span>
                <span class="estado estado--proceso">En Proceso</span>
                <span>$345.200,00</span>
                <div class="tabla__acciones">
                    <a href="#" class="tabla__accion"><i class="fa-solid fa-eye"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>