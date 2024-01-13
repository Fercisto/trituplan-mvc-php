<header class="header">

    <nav class="header__navegacion">

        <div class="header__navegacion--izquierda">
            
            <div class="header__mobile">
                <img src="../../build/img/barras.svg" alt="menu responsive">
            </div>

            <a class="header__enlace header__enlace--izquierda" href="/">Tritu<span class="header__enlace--azul">plan</span></a>

        </div>


        <div class="header__navegacion--derecha">
            <a class="header__enlace header__enlace--derecha <?php echo pagina_actual('/nosotros') ? 'header__enlace--actual' : ''; ?>" href="/nosotros">Nosotros</a>
            <a class="header__enlace header__enlace--derecha <?php echo pagina_actual('/proyectos') ? 'header__enlace--actual' : ''; ?>" href="/proyectos">Proyectos</a>
            <a class="header__enlace header__enlace--derecha <?php echo pagina_actual('/servicios') ? 'header__enlace--actual' : ''; ?>" href="/servicios">Servicios</a>
            <a class="header__enlace header__enlace--derecha <?php echo pagina_actual('/ayuda') ? 'header__enlace--actual' : ''; ?>" href="/ayuda">Ayuda</a>
            <a class="header__enlace header__enlace--derecha <?php echo pagina_actual('/contacto') ? 'header__enlace--actual' : ''; ?>" href="/contacto">Contacto</a>
            <?php if(is_auth()) { ?>
            
            <a class="header__enlace header__enlace--derecha <?php echo pagina_actual('/admin') ? 'header__enlace--actual' : ''; ?>" href="/admin">Administrar</a>

            <form class="header__form" method="POST" action="/logout">
                <input type="submit" value="Cerrar Sesión" class="header__submit">
            </form>
            
            <?php } ?>
        </div>

    </nav>

</header>