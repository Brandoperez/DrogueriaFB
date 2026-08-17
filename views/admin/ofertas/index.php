<?php include_once __DIR__ . '/../../templates/alertas.php'; ?>
<div class="clientes">
    <div class="pedidos__breadcrum">
        <a href="/admin/dashboard">Inicio</a>
        <span>/</span>
        <p>Ofertas del Mes</p>
    </div>

    <div class="clientes__header formulario__card">
        <div class="clientes__header-top">
            <div class="clientes__header--info">
                <h2>Ofertas del Mes</h2>
                <p>Actualizá las 3 imágenes que se muestran en el slider del portal del cliente.</p>
            </div>
        </div>
    </div>

    <div class="ofertas__admin-grid">
        <?php for($n = 1; $n <= 3; $n++): ?>
        <div class="formulario__card">
            <h3>Oferta <?php echo $n; ?></h3>

            <img src="<?php echo rutaOferta($n); ?>" alt="Oferta <?php echo $n; ?>" class="ofertas__admin-preview">

            <form action="/admin/ofertas" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="slot" value="<?php echo $n; ?>">

                <div class="formulario__campo">
                    <input type="file" name="imagen" id="imagen<?php echo $n; ?>" accept="image/png, image/jpeg, image/webp" hidden required>
                    <label for="imagen<?php echo $n; ?>" class="btn btn__transparente">Seleccionar Imagen</label>
                </div>

                <button type="submit" class="btn btn__azul"><i class="fa-solid fa-check"></i>Actualizar</button>
            </form>
        </div>
        <?php endfor; ?>
    </div>
</div>