<main class="admin">
    
    <h2 class="admin__heading"><?php echo $titulo; ?> trituplan</h2>

    <div class="admin__mensajes">

        <h3 class="admin__mensajes--heading">Mensajes:</h3>

        <?php if(empty($mensajes)) { ?>
        <div class="admin__mensajes--empty">
            <span class="admin__mensajes--empty">-- Aún no hay mensajes --</span>
        </div>
        <?php } else { ?>
            <?php foreach($mensajes as $mensaje) { ?>
            <div class="admin__mensajes--contenido">

                <div class="admin__mensajes--contenido-flex">
                    <span class="admin__mensajes--azul"><i class="fa-solid fa-user"></i><?php echo $mensaje->email; ?></span>
                    <span class="admin__mensajes--fecha"><?php echo restearFecha($mensaje->fecha); ?></span>
                </div>
                
                <div class="admin__mensajes--linea"></div>
                
                <div class="admin__mensajes--contenido-flex">
                    <p><span class="admin__mensajes--bold">Mensaje: </span><?php echo $mensaje->mensaje; ?></p>

                    <form method="POST" class="w100" action="/admin/mensaje/eliminar">

                        <input type="hidden" name="id" value=" <?php echo $mensaje->id ?? '';  ?> ">
                        <input type="hidden" name="tipo" value="mensaje">
                        <button type="submit" class="admin__mensajes--boton"><i class="fas fa-trash-alt trash"></i></button>

                    </form>

                </div>
            </div>
            <?php } ?>
        <?php } ?>
    </div>

    <a href="admin/proyecto/crear" class="admin__enlace">Agregar Proyecto</a>
    <table class="admin__tabla">
        <thead>
            <th class="admin__tabla--encabezado">ID</th>
            <th class="admin__tabla--encabezado">Titulo</th>
            <th class="admin__tabla--encabezado admin__tabla--encabezado-mobile">Descripción</th>
            <th class="admin__tabla--encabezado admin__tabla--encabezado-mobile">Destino</th>
            <th class="admin__tabla--encabezado admin__tabla--encabezado-mobile">Imagen</th>
            <th class="admin__tabla--encabezado">Acciones</th>
        </thead>
        <tbody>
            <?php foreach($proyectos as $proyecto) { ?>
            <tr>
                <td class="admin__tabla--id"><?php echo $proyecto->id ;?></td>
                <td class="admin__tabla--titulo"><?php echo $proyecto->titulo ;?></td>
                <td class="admin__tabla--descripcion"><?php echo $proyecto->descripcion ;?></td>
                <td class="admin__tabla--destino"><?php echo $proyecto->destino ;?></td>
                <td class="admin__tabla--img">
                    
                    <picture>
                        <source srcset="<?php echo $_ENV['HOST'] . '/imagenes/' . $proyecto->imagen; ?>.webp">
                        <source srcset="<?php echo $_ENV['HOST'] . '/imagenes/' . $proyecto->imagen; ?>.jpg">
                        <img src="<?php echo $_ENV['HOST'] . '/imagenes/' . $proyecto->imagen; ?>.jpg" loading="lazy" alt="Imagen Proyecto" class="admin__tabla--imagen">
                    </picture>
            
                </td>
                <td class="admin__tabla--acciones">
                    <a href="admin/proyecto/actualizar?id=<?php echo s($proyecto->id) ; ?>" class="admin__tabla--enlace admin__tabla--enlace-actualizar"><i class="fa-solid fa-pen-to-square"></i></li>Actualizar</a>

                    <form method="POST" class="w100" action="/admin/proyecto/eliminar">

                        <input type="hidden" name="id" value=" <?php echo $proyecto->id ?? '';  ?> ">
                        <input type="hidden" name="tipo" value="proyecto">
                        <button type="submit" class="admin__tabla--enlace admin__tabla--enlace-eliminar"><i class="fas fa-trash-alt"></i> Eliminar</button>

                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <?php echo $paginacion; ?>

</main>
