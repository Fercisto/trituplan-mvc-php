<div class="formulario__campo">
    <label for="titulo" class="formulario__label">Titulo</label>
    <input 
        type="text"
        class="formulario__input"
        placeholder="Escribe un titulo"
        id="titulo"
        name="proyecto[titulo]"
        value="<?php echo s($proyecto->titulo) ?? ''; ?>"
    />
</div>

<div class="formulario__campo">

    <label for="descripcion" class="formulario__label">Descripción</label>
    <textarea 
        name="proyecto[descripcion]" 
        id="descripcion" 
        cols="30" 
        rows="10"
        placeholder="Escribe una descripción..."
        class="formulario__input"
    /><?php echo s($proyecto->descripcion) ?? ''; ?></textarea>
    
</div>

<div class="formulario__campo">
    <label for="destino" class="formulario__label">Destino</label>
    <input 
        type="text"
        class="formulario__input"
        placeholder="Escribe un destino. Ej: Durango"
        id="destino"
        name="proyecto[destino]"
        value="<?php echo s($proyecto->destino) ?? ''; ?>"
    />
</div>

<div class="formulario__campo">

    <label for="imagen" class="formulario__label">Selecciona una imagen</label>
    <input type="file" id="imagen" accept="image/jpeg, image/png" name="proyecto[imagen]">
    <?php if($proyecto->imagen) { ?>

        <picture>
            <source srcset="<?php echo $_ENV['HOST'] . '/imagenes/' . $proyecto->imagen; ?>.webp">
            <source srcset="<?php echo $_ENV['HOST'] . '/imagenes/' . $proyecto->imagen; ?>.jpg">
            <img src="<?php echo $_ENV['HOST'] . '/imagenes/' . $proyecto->imagen; ?>.jpg" loading="lazy" alt="Imagen Proyecto" class="admin__tabla--imagen">
        </picture>

    <?php } ?>

</div>