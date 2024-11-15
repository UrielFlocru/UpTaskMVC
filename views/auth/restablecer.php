<div class="contenedor restablecer">
    
<?php include __DIR__ . '/../templates/nombre-sitio.php'; ?>


    <div class="contenedor-sm">
        <?php include __DIR__ . '/../templates/alertas.php'; ?>
        <?php if ($error) return; ?>
        <p class="descripcion-pagina">Coloca tu nuevo password</p>

        <form  class="formulario" method="POST">
            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Tu nueva contraseña">
            </div>
            <div class="campo">
                <label for="password2">Repite contraseña</label>
                <input type="password" id="password2" name="password2" placeholder="Repite tu contraseña">
            </div>
            
            <input type="submit" class="boton" value="Guardar">

        </form>

        <div class="acciones">
            <p>¿Aún no tienes una cuenta? <a href="/crear">Registrate</a></p>
            <a href="/">Iniciar Sesión</a>
        </div>
    </div>

    
</div>