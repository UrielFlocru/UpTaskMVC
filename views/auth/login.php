<div class="contenedor login">
    
<?php include __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <?php include __DIR__ . '/../templates/alertas.php'; ?>

        <p class="descripcion-pagina">Iniciar Sesión</p>

        <form action="/" class="formulario" method="POST">
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Tu correo electrónico">
            </div>
            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Tu contraseña">
            </div>

            <input type="submit" class="boton" value="Iniciar Sesión">

        </form>

        <div class="acciones">
            <p>¿Aún no tienes una cuenta? <a href="/crear">Registrate</a></p>
            <a href="/olvide">Olvidé mi contraseña</a>
        </div>
    </div>

    
</div>