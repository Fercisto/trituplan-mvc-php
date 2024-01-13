<?php require_once __DIR__ . '/../templates/banner.php'; ?>

<main class="home">

    <h2 class="home__heading">Conoce nuestros proyectos</h2>

    <div class="home__proyectos--block">

        <div class="home__proyectos proyectos__listado">

            <?php foreach($proyectos as $proyecto) {?>
            <div class="proyecto">
                
                <div class="proyecto__card">

                    <picture>
                        <source srcset="<?php echo $_ENV['HOST'] . '/imagenes/' . $proyecto->imagen; ?>.webp">
                        <source srcset="<?php echo $_ENV['HOST'] . '/imagenes/' . $proyecto->imagen; ?>.jpg">
                        <img src="<?php echo $_ENV['HOST'] . '/imagenes/' . $proyecto->imagen; ?>.jpg" loading="lazy" alt="Imagen Proyecto" class="proyecto__imagen proyecto__imagen--index">
                    </picture>
                
                    <div class="proyecto__contenido">
                        <h3 class="proyecto__heading"><?php echo $proyecto->titulo; ?></h3>
                        <a href="/proyecto?id=<?php echo s($proyecto->id); ?>" class="proyecto__enlace">Ver más</a>
                    </div>

                </div>
                
            </div>
            <?php } ?>     

        </div>

        <div class="home__enlaces">
            <a href="/proyectos" class="home__enlace">Ver Todos</a>
        </div>

    </div>
  
    <h2 class="home__heading">Más sobre nosotros</h2>

    <div class="home__nosotros">

        <div class="home__nosotros--contenido">
            <i class="fa-solid fa-truck-fast"></i>
            <h3>Envíos</h3>
            <p>Ofrecemos envíos a toda la República Mexicana. Compra con confianza y  disfruta de una experiencia de compra única. ¡Tu satisfacción es nuestra prioridad, descubre la simplicidad de comprar con nosotros hoy!</p>
        </div>

        <div class="home__nosotros--contenido">
            <i class="fa-solid fa-hand-holding-dollar"></i>
            <h3>Precio</h3>
            <p>Nos destacamos por ofrecer productos/servicios de alta calidad a precios bajos. Nos comprometemos a proporcionar soluciones accesibles sin comprometer la excelencia. Disfruta de la mejor relación calidad-precio en Trituplan.</p>
        </div>

        <div class="home__nosotros--contenido">
            <i class="fa-solid fa-gear"></i>
            <h3>Personal</h3>
            <p>Nuestro equipo altamente capacitado está aquí para ofrecerte atención personalizada y asesoramiento experto. Conoce la excelencia con un servicio que entiende y se adapta a tus necesidades.</p>
        </div>

    </div>

    <div class="home__servicio">

        <div class="home__servicio--imagen">
            <picture>
                <source srcset="<?php echo $_ENV['HOST'] . '/';?>build/img/caseta.webp">
                <source srcset="<?php echo $_ENV['HOST'] . '/';?>build/img/caseta.avif">
                <source srcset="<?php echo $_ENV['HOST'] . '/';?>build/img/caseta.jpg">
                <img class="home__servicio--imagen-size" loading="lazy" width="200" height="300" src="<?php echo $_ENV['HOST'] . '/';?>build/img/caseta.jpg" alt="imagen nosotros">
            </picture>
        </div>


        <div class="home__servicio--descripcion">
            <h3>Servicios</h3>
            <p>Con Trituplan, tienes la garantía de contar con un equipo altamente capacitado y una amplia gama de servicios. Desde asesoramiento experto hasta soluciones personalizadas.</p>
            <p>Si deseas obtener más información, no dudes en <a href="/contacto">contactarnos</a>.</p>
            <a class="home__servicio--enlace" href="/servicios">Ver Servicios</a>
        </div>

    </div>

</main>
