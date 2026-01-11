<main class="admin">

    <h2 class="admin__heading"><?php echo $titulo; ?> trituplan</h2>

    <div class="admin__contenedor">

        <?php
            $seccionActiva = $seccion;
            include __DIR__ . '/components/sidebar.php';
        ?>

        <!-- Contenido principal -->
        <div class="admin__contenido">

            <?php if($seccion === 'mensajes'): ?>
                <!-- SECCIÓN MENSAJES -->
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

                <?php elseif($seccion === 'proyectos'): ?>
                    <!-- SECCIÓN PROYECTOS -->
                    <a href="admin/proyecto/crear" class="admin__enlace">Agregar Proyecto</a>
                    <table class="admin__tabla">
                        <thead>
                            <th class="admin__tabla--encabezado">ID</th>
                            <th class="admin__tabla--encabezado">Titulo</th>
                            <th class="admin__tabla--encabezado">Descripción</th>
                            <th class="admin__tabla--encabezado">Destino</th>
                            <th class="admin__tabla--encabezado">Imagen</th>
                            <th class="admin__tabla--encabezado">Acciones</th>
                        </thead>
                        <tbody>
                            <?php if(empty($proyectos)) { ?>
                            <tr>
                                <td colspan="6" class="admin__tabla--vacio">No hay proyectos registrados</td>
                            </tr>
                            <?php } else { ?>
                            <?php foreach($proyectos as $proyecto) { ?>
                            <tr>
                                <td data-label="ID:" class="admin__tabla--id"><?php echo $proyecto->id ;?></td>
                                <td data-label="Título:" class="admin__tabla--titulo"><?php echo $proyecto->titulo ;?></td>
                                <td data-label="Descripción:" class="admin__tabla--descripcion"><?php echo $proyecto->descripcion ;?></td>
                                <td data-label="Destino:" class="admin__tabla--destino"><?php echo $proyecto->destino ;?></td>
                                <td data-label="Imagen:" class="admin__tabla--img">

                                    <picture>
                                        <source srcset="<?php echo $_ENV['HOST'] . '/imagenes/' . $proyecto->imagen; ?>.webp">
                                        <source srcset="<?php echo $_ENV['HOST'] . '/imagenes/' . $proyecto->imagen; ?>.jpg">
                                        <img src="<?php echo $_ENV['HOST'] . '/imagenes/' . $proyecto->imagen; ?>.jpg" loading="lazy" alt="Imagen Proyecto" class="admin__tabla--imagen">
                                    </picture>

                                </td>
                                <td class="admin__tabla--acciones">
                                    <a href="admin/proyecto/actualizar?id=<?php echo s($proyecto->id) ; ?>" class="admin__tabla--enlace admin__tabla--enlace-actualizar"><i class="fa-solid fa-pen-to-square"></i>Actualizar</a>

                                    <form method="POST" class="w100" action="/admin/proyecto/eliminar">

                                        <input type="hidden" name="id" value=" <?php echo $proyecto->id ?? '';  ?> ">
                                        <input type="hidden" name="tipo" value="proyecto">
                                        <button type="submit" class="admin__tabla--enlace admin__tabla--enlace-eliminar"><i class="fas fa-trash-alt"></i> Eliminar</button>

                                    </form>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>

                <?php echo $paginacion; ?>

                <?php elseif($seccion === 'cotizaciones'): ?>
                    <!-- SECCIÓN COTIZACIONES -->
                    <a href="admin/cotizacion/crear" class="admin__enlace">Crear Cotización</a>

                    <table class="admin__tabla">
                        <thead>
                            <th class="admin__tabla--encabezado">Folio</th>
                            <th class="admin__tabla--encabezado">Fecha</th>
                            <th class="admin__tabla--encabezado">Destinatario</th>
                            <th class="admin__tabla--encabezado">Total</th>
                            <th class="admin__tabla--encabezado">Acciones</th>
                        </thead>
                        <tbody>
                            <?php if(empty($cotizaciones)) { ?>
                            <tr>
                                <td colspan="5" class="admin__tabla--vacio">No hay cotizaciones registradas</td>
                            </tr>
                            <?php } else { ?>
                                <?php foreach($cotizaciones as $cotizacion) { ?>
                                <tr>
                                    <td data-label="Folio:" class="admin__tabla--folio"><?php echo $cotizacion->folio; ?></td>
                                    <td data-label="Fecha:" class="admin__tabla--fecha"><?php echo date('d/m/Y', strtotime($cotizacion->fecha)); ?></td>
                                    <td data-label="Destinatario:" class="admin__tabla--destinatario"><?php echo $cotizacion->destinatario; ?></td>
                                    <td data-label="Total:" class="admin__tabla--total">$<?php echo number_format($cotizacion->total, 2); ?></td>
                                    <td class="admin__tabla--acciones">
                                        <a href="/admin/cotizaciones/pdf?id=<?php echo $cotizacion->id; ?>" download class="admin__tabla--enlace admin__tabla--enlace-descargar">
                                            <i class="fa-solid fa-file-pdf"></i> Descargar PDF
                                        </a>

                                        <a href="admin/cotizacion/actualizar?id=<?php echo s($cotizacion->id); ?>" class="admin__tabla--enlace admin__tabla--enlace-actualizar">
                                            <i class="fa-solid fa-pen-to-square"></i> Editar
                                        </a>

                                        <form method="POST" class="w100" action="/admin/cotizacion/eliminar">
                                            <input type="hidden" name="id" value="<?php echo $cotizacion->id ?? ''; ?>">
                                            <input type="hidden" name="tipo" value="cotizacion">
                                            <button type="submit" class="admin__tabla--enlace admin__tabla--enlace-eliminar">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>

                <?php echo $paginacion; ?>

            <?php endif; ?>

        </div><!-- fin admin__contenido -->

    </div><!-- fin admin__contenedor -->

</main>