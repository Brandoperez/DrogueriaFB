<div class="clientes">
        <div class="pedidos__breadcrum">
            <a href="/admin/dashboard">Inicio</a>
            <span>/</span>
            <a href="/admin/clientes">Clientes</a>
            <span>/</span>
            <p><?php echo $cliente->name; ?></p>
        </div>

        <div class="formulario__card clientes__texto">
            <h2 class="clientes__nombre--vista"><?php echo $cliente->name; ?></h2>

            <p><strong>CUIT: </strong><?php echo $cliente->cuit;?></p>
            <p><strong>Correo: </strong><?php echo $cliente->email;?></p>
            <p><strong>Teléfono: </strong><?php echo $cliente->phone;?></p>
            <p><strong>Dirección: </strong><?php echo $cliente->address;?></p>
            <p><strong>Provincia: </strong><?php echo $cliente->province;?></p>
        </div>

        <div class="formulario__acciones--submit">
                <a href="/admin/clientes" class="formulario__submit">Volver</a>
                <a href="/admin/clientes/editar?id=<?php echo $cliente->id; ?>" class="formulario__submit">Editar Cliente</a>
        </div>
</div>

