<?php foreach($alertas as $key => $mensajes) { ?>
    <?php foreach($mensajes as $mensaje) { ?>
        <div class="alerta alerta--<?php echo $key; ?>">
            <?php echo $mensaje;?>
        </div>
    <?php } ?>
<?php } ?>

<?php if(isset($_SESSION['alerta'])): ?>
    <script>
        window.ALERTA = <?= json_encode($_SESSION['alerta']); ?>;
    </script>
    <?php unset($_SESSION['alerta']); ?>
<?php endif; ?>