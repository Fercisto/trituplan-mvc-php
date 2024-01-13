<main class="proyecto-details">

    <div class="proyecto-details__grid">

        <div class="proyecto-details__imagen">
            <picture>
                <source srcset="<?php echo $_ENV['HOST'] . '/imagenes/' . $proyecto->imagen; ?>.webp">
                <source srcset="<?php echo $_ENV['HOST'] . '/imagenes/' . $proyecto->imagen; ?>.jpg">
                <img src="<?php echo $_ENV['HOST'] . '/imagenes/' . $proyecto->imagen; ?>.jpg" loading="lazy" alt="Imagen<?php echo $proyecto->titulo; ?>">
            </picture>
        </div>

        <div class="proyecto-details__descripcion">
            
            <h2 class="proyecto-details__heading"><?php echo $proyecto->titulo; ?></h2>
            <p><?php echo $proyecto->descripcion; ?></p>
            
            <h4 class="proyecto-details__descripcion--destino"><span>Destino: </span><?php echo $proyecto->destino; ?></h4>

            <div class="proyecto-details__descripcion--enlace">
                <a href="mailto:fer_cis@outlook.com" target="_blank" class="proyecto-details__descripcion--enlace-email"><i class="fa-regular fa-envelope"></i> Cotizar</a>
                <a href="https://wa.me/6182201830" target="_blank" class="proyecto-details__descripcion--enlace-wh"><i class="fa-brands fa-whatsapp"></i> Cotizar</a>
            </div>

        </div>

    </div>

</main>