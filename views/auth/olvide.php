<div class="contenedor olvide">
    
<?php include __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <?php include __DIR__ . '/../templates/alertas.php'; ?>
        <?php if ($accion) return;?>
        <p class="descripcion-pagina">Recupera tu contraseña, ingresa tu correo electrónico</p>

        <form action="/olvide" class="formulario" method="POST">
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Tu correo electrónico">
            </div>
            
            <input type="submit" class="boton" value="Recupera tu contraseña">

        </form>

        <div class="acciones">
            <p>¿Aún no tienes una cuenta? <a href="/crear">Registrate</a></p>
            <a href="/">Iniciar Sesión</a>
        </div>
    </div>

    
</div>