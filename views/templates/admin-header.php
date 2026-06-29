<header class="admin-header">
    <div class="admin-header__contenido">
        <div class="admin-header__texto">
            <h1 class="admin-header__heading"><?php echo $titulo ?? 'Panel Principal';?></h1>
        </div>

        <div class="admin-header__acciones">
            <form class="admin-header__busqueda">
                 <i class="fa-solid fa-magnifying-glass admin-header__busqueda-icono"></i>
                <input type="text" placeholder="Buscar productos, usuarios, clientes...">
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
            <button class="admin-header__icono"> <i class="fa-solid fa-bell"></i></button>
            <button class="admin-header__icono"><i class="fa-solid fa-gear"></i></button>
        </div>
        
    </div>
</header>