<?php include_once __DIR__ . '/../dashboard/header-dashboard.php' ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

    <a href="/cambiar-password" class="enlace" >Cambiar contraseña</a>
    
    <form class="formulario" method="POST" action="/perfil">
        <div class="campo">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $usuario->nombre; ?>" placeholder="Tu nombre">
        </div>
        <div class="campo">
            <label for="email">Correo Electrónico</label>
            <input type="email" id="email" name="email" value="<?php echo $usuario->email; ?>" placeholder="Tu correo electrónico">
        </div>

        <input type="submit" value="Guardar cambios">

    </form>
</div>



<?php include_once __DIR__ . '/../dashboard/footer-dashboard.php' ?>