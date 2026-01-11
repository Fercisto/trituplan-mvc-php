<main class="admin">

    <h2 class="admin__heading"><?php echo $titulo; ?></h2>

    <div class="admin__contenedor">

        <?php
            $seccionActiva = 'cotizaciones';
            include __DIR__ . '/../components/sidebar.php';
        ?>

        <!-- Contenido principal -->
        <div class="admin__contenido">
            <div class="admin-crud">

                <h3 class="admin-crud__heading">Actualizar Cotización</h3>

                <?php require_once __DIR__ . '/../../templates/alertas.php'; ?>

                <form id="formulario-cotizacion" class="formulario" method="POST">

                    <?php include __DIR__ . '/formulario.php' ;?>
                    <input type="submit" class="formulario__submit" value="Actualizar Cotización">

                </form>

            </div>
        </div><!-- fin admin__contenido -->

    </div><!-- fin admin__contenedor -->

</main>
