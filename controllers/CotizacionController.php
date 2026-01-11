<?php

namespace Controllers;
use MVC\Router;
use Model\Cotizacion;

class CotizacionController {

    public static function crear(Router $router) {

        $alertas = [];
        $cotizacion = new Cotizacion;
        $fechaActual = date('Y-m-d');

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Generar folio automático
            $folio = Cotizacion::generarFolio();

            // Preparar datos para la cotización
            $datos = [
                'folio' => $folio,
                'fecha' => $_POST['fecha'] ?? '',
                'destinatario' => $_POST['destinatario'] ?? '',
                'items' => $_POST['items'] ?? '',
                'subtotal' => $_POST['subtotal'] ?? 0,
                'iva' => $_POST['iva'] ?? 0,
                'total' => $_POST['total'] ?? 0,
                'total_letra' => $_POST['total_letra'] ?? '',
                'condiciones_generales' => $_POST['condiciones_generales'] ?? '',
            ];

            $cotizacion = new Cotizacion($datos);

            // Validar
            $alertas = $cotizacion->validar();

            if(empty($alertas)) {
                // Guardar en la base de datos
                $resultado = $cotizacion->guardar();

                if($resultado['resultado']) {

                    header('Location: /admin?seccion=cotizaciones&page=1');
                    exit();
                }
            }
        }

        $alertas = Cotizacion::getAlertas();

        $router->render('admin/cotizaciones/crear', [
            'titulo' => 'Crear Cotización',
            'alertas' => $alertas,
            'fechaActual' => $fechaActual,
            'cotizacion' => $cotizacion,
            'items' => [] // Array vacío para modo crear
        ]);
    }

    public static function actualizar(Router $router) {

        $id = $_GET['id'] ?? null;
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if(!$id) {
            header('Location: /admin?seccion=cotizaciones&page=1');
            exit();
        }

        $fechaActual = date('Y-m-d');
        $cotizacion = Cotizacion::find($id);

        if(!$cotizacion) {
            header('Location: /admin?seccion=cotizaciones&page=1');
            exit();
        }

        // Decodificar items desde JSON
        $items = json_decode($cotizacion->items, true) ?? [];

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Sincronizar datos del formulario
            $cotizacion->sincronizar([
                'fecha' => $_POST['fecha'] ?? '',
                'destinatario' => $_POST['destinatario'] ?? '',
                'items' => $_POST['items'] ?? '',
                'subtotal' => $_POST['subtotal'] ?? 0,
                'iva' => $_POST['iva'] ?? 0,
                'total' => $_POST['total'] ?? 0,
                'total_letra' => $_POST['total_letra'] ?? '',
                'condiciones_generales' => $_POST['condiciones_generales'] ?? '',
            ]);

            // Validar
            $alertas = $cotizacion->validar();

            if(empty($alertas)) {
                // Actualizar en la base de datos
                $resultado = $cotizacion->guardar();

                if($resultado) {
                    header('Location: /admin?seccion=cotizaciones&page=1');
                    exit();
                }
            }
        }

        $router->render('admin/cotizaciones/actualizar', [
            'titulo' => 'Actualizar Cotización',
            'alertas' => $alertas,
            'cotizacion' => $cotizacion,
            'fechaActual' => $fechaActual,
            'items' => $items
        ]);
    }

    public static function eliminar() {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if($id) {

                $cotizacion = Cotizacion::find($id);
                $resultado = $cotizacion->eliminar();

                if($resultado) {
                    header('Location: /admin?seccion=cotizaciones&page=1');
                    exit();
                }

            }
        }

    }

    public static function pdf() {
        $id = $_GET['id'] ?? null;

        $cotizacion = Cotizacion::find($id);
        if (!$cotizacion) {
            header('Location: /admin/cotizaciones');
            exit;
        }

        ini_set('display_errors', '0');

        if (ob_get_length()) {
            ob_end_clean();
        }
        ob_start();

        // HTML
        include __DIR__ . '/../views/admin/cotizaciones/pdf.php';
        $html = ob_get_clean();

        // CSS
        $cssPath = __DIR__ . '/../public/css/pdf/cotizaciones.css';
        $css = file_exists($cssPath) ? file_get_contents($cssPath) : '';

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'Letter',
            'margin_top' => 18,
            'margin_bottom' => 18,
            'margin_left' => 12,
            'margin_right' => 12,
        ]);

        $mpdf->SetHTMLHeader('');
        $mpdf->SetHTMLFooter('');

        $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

        $filename = preg_replace('/[^A-Za-z0-9\-_\.]/', '_', $cotizacion->folio . '-' . $cotizacion->destinatario) . '.pdf';

        // Descarga PDF
        $mpdf->Output($filename, \Mpdf\Output\Destination::DOWNLOAD);

        exit;
    }

}