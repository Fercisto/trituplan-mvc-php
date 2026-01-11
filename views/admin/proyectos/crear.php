<main class="admin">

    <h2 class="admin__heading"><?php echo $titulo; ?></h2>

    <div class="admin__contenedor">

        <?php
            $seccionActiva = 'proyectos';
            include __DIR__ . '/../components/sidebar.php';
        ?>

        <!-- Contenido principal -->
        <div class="admin__contenido">
            <div class="admin-crud">

                <h3 class="admin-crud__heading">Crea un Nuevo Proyecto</h3>

                <?php require_once __DIR__ . '/../../templates/alertas.php'; ?>

                <form class="formulario" method="POST" enctype="multipart/form-data">

                    <?php include __DIR__ . '/formulario.php' ;?>
                    <input type="submit" class="formulario__submit" value="Crear Proyecto">

                </form>

            </div>
        </div><!-- fin admin__contenido -->

    </div><!-- fin admin__contenedor -->

</main>