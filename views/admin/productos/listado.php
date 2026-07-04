<section class="pedidos">
    <div class="pedidos__breadcrum">
        <a href="/admin/dashboard">Inicio</a>
        <span>/</span>
        <p>Historial de Cargas</p>
    </div>

    <div class="btn btn__acciones">
        <a href="/admin/productos/excel" class="excel__accion btn__azul">
            <i class="fa-solid fa-file-import"></i>
            Nueva Importación
        </a>
    </div>

    <div class="formulario__card">
        <div class="tabla tabla__grid--listado">
            <span>Archivo</span>
            <span>Fecha</span>
            <span>Usuario</span>
            <span>Estado</span>
            <span>Acciones</span>
        </div>

        <?php foreach($importaciones as $importacion): ?>

            <div class="tabla__fila tabla__fila--listado">
                <span><?php echo $importacion->file_name; ?></span>
                <span><?php echo date('d/m/Y H:i', strtotime($importacion->created_at)); ?></span>
                <span><?php echo $importacion->usuario ?? 'Sin usuario'; ?></span>
                <span class="estado estado--completado">
                    <?php echo $importacion->status; ?>
                </span>
                <span class="tabla__acciones">
                    <a href="#" class="tabla__accion"><i class="fa-solid fa-eye"></i></a>
                </span>
            </div>

        <?php endforeach; ?>
    </div>
</section>