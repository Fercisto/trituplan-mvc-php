<div class="wizard">
    <div class="wizard__steps">
        <div class="wizard__step wizard__step--active" data-step="1">
            <span class="wizard__step-number">1</span>
            <span class="wizard__step-title">Información General</span>
        </div>
        <div class="wizard__step" data-step="2">
            <span class="wizard__step-number">2</span>
            <span class="wizard__step-title">Productos/Servicios</span>
        </div>
        <div class="wizard__step" data-step="3">
            <span class="wizard__step-number">3</span>
            <span class="wizard__step-title">Totales y Condiciones</span>
        </div>
    </div>
</div>

<!-- Contenedor de alertas global -->
<div id="formulario__alerta"></div>

<!-- Paso 1: Información General -->
<div class="wizard__content" data-step-content="1">
    <fieldset class="formulario__fieldset">
        <legend class="formulario__legend">Información General</legend>

        <div class="formulario__campo">
            <label for="fecha" class="formulario__label">Fecha de Cotización</label>
            <input
                type="date"
                class="formulario__input"
                id="fecha"
                name="fecha"
                value="<?= $fechaActual ?>"
                required
            >
        </div>

        <div class="formulario__campo">
            <label for="destinatario" class="formulario__label">Destinatario</label>
            <input
                type="text"
                class="formulario__input"
                id="destinatario"
                name="destinatario"
                placeholder="Ej: Lic. Refugio Jiménez"
                value="<?php echo s($cotizacion->destinatario) ?? ''; ?>"
                required
            >
        </div>
    </fieldset>
</div>

<!-- Paso 2: Agregar Items -->
<div class="wizard__content" data-step-content="2" style="display: none;">
    <fieldset class="formulario__fieldset">
        <legend class="formulario__legend">Agregar Producto/Servicio</legend>

        <div class="formulario__campo">
            <label for="descripcion" class="formulario__label">Descripción</label>
            <textarea
                class="formulario__input"
                id="descripcion"
                rows="2"
                placeholder="Ej: Cable uso rudo 4*12"
            ></textarea>
        </div>

        <div class="formulario__campo">
            <label for="cantidad" class="formulario__label">Cantidad</label>
            <input
                type="text"
                class="formulario__input"
                id="cantidad"
                placeholder="Ej: 200 mts"
            >
        </div>

        <div class="formulario__campo">
            <label for="precio_unitario" class="formulario__label">Precio Unitario</label>
            <input
                type="number"
                step="0.01"
                class="formulario__input"
                id="precio_unitario"
                placeholder="0.00"
            >
        </div>

        <div class="formulario__campo">
            <label for="precio_total" class="formulario__label">Precio Total</label>
            <input
                type="text"
                class="formulario__input"
                id="precio_total"
                placeholder="$0.00"
                readonly
            >
        </div>

        <button type="button" class="admin__button" id="btn-agregar-item">
            <i class="fa-solid fa-plus"></i>
            Agregar Producto
        </button>
    </fieldset>

    <!-- Tabla de Items Agregados -->
    <fieldset class="formulario__fieldset">
        <legend class="formulario__legend">Productos Agregados</legend>

        <table class="admin__tabla">
            <thead>
                <th class="admin__tabla--encabezado">Descripción</th>
                <th class="admin__tabla--encabezado">Cantidad</th>
                <th class="admin__tabla--encabezado">P/U</th>
                <th class="admin__tabla--encabezado">Precio</th>
                <th class="admin__tabla--encabezado">Acciones</th>
            </thead>
            <tbody id="items-tabla">
                <!-- Los items se renderizan con JavaScript -->
            </tbody>
        </table>
    </fieldset>
</div>

<!-- Paso 3: Totales y Condiciones -->
<div class="wizard__content" data-step-content="3" style="display: none;">
    <!-- Totales -->
    <fieldset class="formulario__fieldset">
        <legend class="formulario__legend">Totales</legend>

        <div class="formulario__campo">
            <label for="subtotal" class="formulario__label">Subtotal</label>
            <input
                type="text"
                class="formulario__input"
                id="subtotal"
                name="subtotal"
                placeholder="$0.00"
                readonly
            >
        </div>

        <div class="formulario__campo">
            <label for="iva" class="formulario__label">IVA (16%)</label>
            <input
                type="text"
                class="formulario__input"
                id="iva"
                name="iva"
                placeholder="$0.00"
                readonly
            >
        </div>

        <div class="formulario__campo">
            <label for="total" class="formulario__label">Total</label>
            <input
                type="text"
                class="formulario__input"
                id="total"
                name="total"
                placeholder="$0.00"
                readonly
            >
        </div>

        <div class="formulario__campo">
            <label for="total_letra" class="formulario__label">Total en Letra</label>
            <input
                type="text"
                class="formulario__input"
                id="total_letra"
                name="total_letra"
                readonly
            >
        </div>
    </fieldset>

    <!-- Condiciones -->
    <fieldset class="formulario__fieldset">
        <legend class="formulario__legend">Condiciones Generales</legend>

        <div class="formulario__campo">
            <label for="condicion_1" class="formulario__label">Condición 1</label>
            <input
                type="text"
                class="formulario__input"
                id="condicion_1"
                name="condicion_1"
                placeholder="Ej: Se requiere el 100% para formalizar el pedido"
                value="Se requiere el 100% para formalizar el pedido"
            >
        </div>

        <div class="formulario__campo">
            <label for="condicion_2" class="formulario__label">Condición 2</label>
            <input
                type="text"
                class="formulario__input"
                id="condicion_2"
                name="condicion_2"
                placeholder="Ej: Entrega 7 días después de formalizar la compra"
                value="Entrega 7 días después de formalizar la compra"
            >
        </div>

        <div class="formulario__campo">
            <label for="condicion_3" class="formulario__label">Condición 3</label>
            <input
                type="text"
                class="formulario__input"
                id="condicion_3"
                name="condicion_3"
                placeholder="Ej: Transporte y viáticos a cargo del cliente"
                value="Transporte y viáticos a cargo del cliente"
            >
        </div>
    </fieldset>
</div>

<!-- Botones de navegación del wizard -->
<div class="wizard__navigation">
    <button type="button" class="wizard__button wizard__button--secondary" id="btn-anterior">
        <i class="fa-solid fa-arrow-left"></i>
        Anterior
    </button>
    <button type="button" class="wizard__button wizard__button--primary" id="btn-siguiente">
        Siguiente
        <i class="fa-solid fa-arrow-right"></i>
    </button>
</div>

<!-- Campo oculto para enviar los items como JSON -->
<input type="hidden" name="items" id="items-json">

<!-- Campo oculto para enviar las condiciones generales como JSON -->
<input type="hidden" name="condiciones_generales" id="condiciones-json">

<!-- Pasar items y condiciones de PHP a JavaScript (para modo edición) -->
<script>
    window.itemsIniciales = <?= json_encode($items ?? []) ?>;

    // Cargar condiciones en modo edición
    <?php if (isset($cotizacion) && $cotizacion->condiciones_generales): ?>
        const condicionesGuardadas = <?= json_encode(json_decode($cotizacion->condiciones_generales, true) ?? []) ?>;

        document.addEventListener('DOMContentLoaded', () => {
            if (condicionesGuardadas.length > 0) {
                if (condicionesGuardadas[0]) {
                    document.querySelector('#condicion_1').value = condicionesGuardadas[0];
                }
                if (condicionesGuardadas[1]) {
                    document.querySelector('#condicion_2').value = condicionesGuardadas[1];
                }
                if (condicionesGuardadas[2]) {
                    document.querySelector('#condicion_3').value = condicionesGuardadas[2];
                }
            }
        });
    <?php endif; ?>
</script>