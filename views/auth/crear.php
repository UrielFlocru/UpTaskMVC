<div class="contenedor crear">
    
<?php include __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crea tu cuenta</p>
        
        <?php include __DIR__ . '/../templates/alertas.php'; ?>
        <form action="/crear" class="formulario" method="POST">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" value="<?php echo $usuario->nombre; ?>">
            </div>
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Tu correo electrónico" value="<?php echo $usuario->email; ?>">
            </div>
            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Tu contraseña">
            </div>
            <div class="campo">
                <label for="password2">Repite tu contraseña</label>
                <input type="password" id="password2" name="password2" placeholder="Repite tu contraseña">
            </div>

            <input type="submit" class="boton" value="Crear cuenta">

        </form>

        <div class="acciones">
            <p>¿Ya tienes una cuenta? <a href="/">Iniciar Sesión</a></p>
            <a href="/olvide">Olvidé mi contraseña</a>
        </div>
    </div>

    
</div>