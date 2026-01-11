<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>&nbsp;</title>
</head>
<body>

<?php

// Items
$items = json_decode($cotizacion->items, true) ?: [];

// Condiciones generales
$condiciones = json_decode($cotizacion->condiciones_generales, true) ?: [];

// Condiciones predeterminadas si no hay guardadas
if (empty($condiciones)) {
    $condiciones = [
        'Se requiere el 100% para formalizar el pedido.',
        'Entrega estimada 7 días después de formalizar la compra.',
        'Transporte y viáticos a cargo del cliente.'
    ];
}

$fecha = new DateTime($cotizacion->fecha);

$formatter = new IntlDateFormatter(
    'es_MX',
    IntlDateFormatter::LONG,
    IntlDateFormatter::NONE,
    $fecha->getTimezone(),
    IntlDateFormatter::GREGORIAN,
    "d 'de' MMMM 'de' y"
);

$fechaFormateada = $formatter->format($fecha);

// Datos del documento
$folio = $cotizacion->folio ?? '';
$destinatario = $cotizacion->destinatario ?? '';
$totalLetra = $cotizacion->total_letra ?? '';
?>

<div class="container">

  <table class="header-table avoid-break" width="100%" cellspacing="0" cellpadding="0">
    <tr>
      <td width="60%" valign="middle">
        <img
          src="<?php echo __DIR__ . '/../../../public/build/img/logo-cot.png'; ?>"
          alt="Logo"
          class="logo"
        >

      </td>

      <td width="40%" align="right" valign="middle">
        <div class="document-title">COTIZACIÓN</div>
        <div class="document-date"><?php echo htmlspecialchars($fechaFormateada); ?></div>
        <div class="document-number">No. <?php echo htmlspecialchars($folio); ?></div>
      </td>
    </tr>
  </table>

  <div class="client-section avoid-break">
    <div class="client-label">Atención</div>
    <div class="client-name"><?php echo htmlspecialchars($destinatario); ?></div>
  </div>

  <div class="products-section">
    <div class="section-title">DETALLE DE PRODUCTOS</div>

    <table class="products-table">
      <thead>
        <tr>
          <th>DESCRIPCIÓN</th>
          <th class="col-qty">CANT.</th>
          <th class="col-price">PRECIO UNIT.</th>
          <th class="col-total">IMPORTE</th>
        </tr>
      </thead>

      <tbody>
        <?php if (empty($items)): ?>
          <tr>
            <td colspan="4">Sin productos registrados.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($items as $item): ?>
            <?php
              $descripcion = $item['descripcion'] ?? '';
              $cantidad    = isset($item['cantidad']) ? (float)$item['cantidad'] : 0;
              $precioU     = isset($item['precioUnitario']) ? (float)$item['precioUnitario'] : 0;
              $importe     = isset($item['precio']) ? (float)$item['precio'] : ($cantidad * $precioU);
            ?>
            <tr>
              <td><?php echo htmlspecialchars($descripcion); ?></td>
              <td class="col-qty"><?php echo number_format($cantidad, 0); ?></td>
              <td class="col-price">$<?php echo number_format($precioU, 2); ?></td>
              <td class="col-total">$<?php echo number_format($importe, 2); ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="totals-section avoid-break">
    <div class="totals-wrapper">
        <div class="totals-card">
            <table class="totals-table">
                <tr>
                <td class="label">SUBTOTAL:</td>
                <td class="value">$<?php echo number_format((float)$cotizacion->subtotal, 2); ?></td>
                </tr>
                <tr>
                <td class="label">IVA (16%):</td>
                <td class="value">$<?php echo number_format((float)$cotizacion->iva, 2); ?></td>
                </tr>
                <tr class="total-row">
                <td class="label">TOTAL:</td>
                <td class="value">$<?php echo number_format((float)$cotizacion->total, 2); ?></td>
                </tr>
            </table>
        </div>
    </div>
  </div>

  <div class="conditions-section">
    <div class="conditions-title">CONDICIONES GENERALES</div>

    <div class="conditions-amount">
      <div class="conditions-amount-number">
        $<?php echo number_format((float)$cotizacion->total, 2); ?> MXN
      </div>

      <?php if (!empty($totalLetra)): ?>
        <div class="conditions-amount-words">
          (<?php echo htmlspecialchars($totalLetra); ?>)
        </div>
      <?php endif; ?>
    </div>

    <ol class="conditions-list">
      <?php foreach ($condiciones as $condicion): ?>
        <li><?php echo htmlspecialchars($condicion); ?></li>
      <?php endforeach; ?>
    </ol>
  </div>

  <div class="banking-section">
    <div class="banking-title">Datos para depósito</div>

    <div class="bank-info">
      <div class="bank-name"><?php echo htmlspecialchars($_ENV['BANK_NAME']); ?></div>
      <div class="bank-details">
        <p><span class="bank-label">Número de Clave Interbancaria:</span> <span class="bank-value"><?php echo htmlspecialchars($_ENV['BANK_CLABE']); ?></span></p>
        <p><span class="bank-label">No de cuenta:</span> <span class="bank-value"><?php echo htmlspecialchars($_ENV['BANK_ACCOUNT']); ?></span></p>
        <p><span class="bank-label">Sucursal:</span> <span class="bank-value"><?php echo htmlspecialchars($_ENV['BANK_BRANCH']); ?></span></p>
        <p><span class="bank-label"><?php echo htmlspecialchars($_ENV['BANK_CITY']); ?></span></p>
      </div>
    </div>

    <div class="bank-info">
      <div class="bank-name"><?php echo htmlspecialchars($_ENV['OWNER_NAME']); ?></div>
      <div class="bank-details">
        <p><span class="bank-label"><?php echo htmlspecialchars($_ENV['OWNER_ADDRESS']); ?></span></p>
        <p><span class="bank-label"><?php echo htmlspecialchars($_ENV['OWNER_CITY']); ?></span></p>
        <p><span class="bank-label">RFC:</span> <span class="bank-value"><?php echo htmlspecialchars($_ENV['OWNER_RFC']); ?></span></p>
        <p><span class="bank-label">Tel.:</span> <span class="bank-value"><?php echo htmlspecialchars($_ENV['OWNER_PHONE']); ?></span></p>
        <p><span class="bank-label">Email:</span> <span class="bank-value"><?php echo htmlspecialchars($_ENV['OWNER_EMAIL']); ?></span></p>
      </div>
    </div>
  </div>

  <div class="footer-section">
    <div class="signature-box">
      <div class="signature-title">Atentamente</div>
      <div class="signature-name">Ing. Fernando Cisneros Escobedo</div>
    </div>
  </div>

</div>

</body>
</html>