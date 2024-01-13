<main class="admin-crud">

    <h3 class="admin-crud__heading">Actualizar Proyecto</h3>
    <?php require_once __DIR__ . '/../../templates/alertas.php'; ?>

    <form class="formulario" method="POST" enctype="multipart/form-data">

        <?php include __DIR__ . '/formulario.php' ;?>
        <input type="submit" class="formulario__submit" value="Actualizar Proyecto">

    </form>

</main>