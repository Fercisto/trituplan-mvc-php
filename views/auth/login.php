<main class="auth">

    <h2 class="auth__heading"><?php echo $titulo; ?> en trituplan</h2>

    <?php require_once __DIR__ . '/../templates/alertas.php'; ?>

    <form class="formulario" method="POST">
        <div class="formulario__campo">
            <label for="email" class="formulario__label">Email</label>
            <input 
                type="email"
                class="formulario__input"
                placeholder="Tu Email"
                id="email"
                name="email"
            />
        </div>

        <div class="formulario__campo">
            <label for="password" class="formulario__label">Password</label>
            <input 
                type="password"
                class="formulario__input"
                placeholder="Tu Password"
                id="password"
                name="password"
            />
        </div>

        <input type="submit" class="formulario__submit" value="Iniciar Sesión">
    </form>


</main>