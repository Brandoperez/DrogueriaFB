<section class="pedidos">
    <div class="pedidos__breadcrum">
        <a href="/admin/dashboard">Inicio</a>
        <span>/</span>
        <p>Historial de Cargas</p>
    </div>

    <div class="pedidos__acciones">
        <a href="/admin/productos/excel" class="excel__accion excel__accion--primary">
            <i class="fa-solid fa-file-import"></i>
            Nueva Importación
        </a>
    </div>

    <div class="excel__tabla formulario__card">
        <div class="tabla__header tabla__header--historial">
            <span>Archivo</span>
            <span>Fecha</span>
            <span>Usuario</span>
            <span>Total</span>
            <span>Nuevos</span>
            <span>Actualizados</span>
            <span>Estado</span>
        </div>

        <?php foreach($importaciones as $importacion): ?>
            <div class="tabla__fila tabla__fila--historial">
                <span><?php echo $importacion->file_name; ?></span>
                <span><?php echo date('d/m/Y H:i', strtotime($importacion->created_at)); ?></span>
                <span><?php echo $importacion->usuario ?? 'Sin usuario'; ?></span>
                <span><?php echo $importacion->total_records; ?></span>
                <span><?php echo $importacion->new_products; ?></span>
                <span><?php echo $importacion->updated_products; ?></span>
                <span class="estado estado--completado">
                    <?php echo $importacion->status; ?>
                </span>
            </div>
        <?php endforeach; ?>
    </div>
</section>