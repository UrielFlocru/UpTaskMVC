<?php include_once __DIR__ . '/../dashboard/header-dashboard.php' ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

    <a href="/perfil" class="enlace" >Volver</a>

    <form class="formulario" method="POST" action="/cambiar-password">
        <div class="campo">
            <label for="actual">Contraseña actual</label>
            <input type="password" id="actual" name="actual"  placeholder="Tu contraseña actual">
        </div>
        <div class="campo">
            <label for="nueva">Contraseña nueva</label>
            <input type="password" id="nueva" name="nueva" placeholder="Tu nueva contraseña">
        </div>

        <input type="submit" value="Guardar cambios">

    </form>
</div>



<?php include_once __DIR__ . '/../dashboard/footer-dashboard.php' ?>