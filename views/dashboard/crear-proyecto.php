<?php include_once __DIR__ . '/../dashboard/header-dashboard.php' ?>

    <div class="contenedor-sm">
        <?php include_once __DIR__ . '/../templates/alertas.php'?>

        <form class="formulario" method="POST" action="/crear-proyecto">

            <?php include_once __DIR__ . '/formulario-proyecto.php' ?>
            <input type="submit" class="" value="Crear Proyecto">

        </form>

    </div>



<?php include_once __DIR__ . '/../dashboard/footer-dashboard.php' ?>