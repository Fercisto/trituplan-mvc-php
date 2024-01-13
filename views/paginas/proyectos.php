<main class="proyectos">

    <h2 class="proyectos__heading">Nuestros Proyectos</h2>

    <div class="proyectos__listado slider swiper">

            <div class="swiper-wrapper">

                <?php foreach($proyectos as $proyecto) {?>
                <div class="proyecto swiper-slide">
                    
                    <div class="proyecto__card">

                        <picture>
                            <source srcset="<?php echo $_ENV['HOST'] . '/imagenes/' . $proyecto->imagen; ?>.webp">
                            <source srcset="<?php echo $_ENV['HOST'] . '/imagenes/' . $proyecto->imagen; ?>.jpg">
                            <img src="<?php echo $_ENV['HOST'] . '/imagenes/' . $proyecto->imagen; ?>.jpg" loading="lazy" alt="Imagen<?php echo $proyecto->titulo; ?>" class="proyecto__imagen">
                        </picture>
                    
                        <div class="proyecto__contenido">
                            <h3 class="proyecto__heading"><?php echo $proyecto->titulo; ?></h3>
                            <a href="/proyecto?id=<?php echo s($proyecto->id); ?>" class="proyecto__enlace">Ver más</a>
                        </div>

                    </div>
                 
                </div>
                <?php } ?>

            </div>
            
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            
    </div>
    
</main>
