<main class="contacto">

    <h2 class="contacto__heading">¡Ponte en contacto con Trituplan!</h2>
    
    <form action="" method="POST" class="formulario formulario--contacto">

        <h3 class="formulario__heading">Escríbenos un mensaje</h3>

        <?php require_once __DIR__ . '/../templates/alertas.php'; ?>

        <div class="formulario__campo">
            <label class="formulario__label" for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="Tu Email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : (s($mensaje->email) ?? ''); ?>">
        </div>


        <div class="formulario__campo">
            <label for="mensaje">Mensaje</label>
            <textarea name="mensaje" id="mensaje" cols="30" rows="10" placeholder="Escribe tu mensaje aquí..."><?php echo isset($_POST['mensaje']) ? $_POST['mensaje'] : (s($mensaje->mensaje) ?? ''); ?></textarea>
        </div>

        
        <div class="formulario__campo">
            <div class="g-recaptcha" data-sitekey="6LelXT4pAAAAAF4LFx1bP8zGlohVd_g1RC72vDnv"></div>
        </div>

        <input type="submit" value="Enviar Mensaje"  class="formulario__submit formulario__submit--full">

    </form>

</main>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>