<?php
require_once APPPATH . 'third_party/vendor/autoload.php';
use Dompdf\Dompdf;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\DummyPrintConnector;
use Mike42\Escpos\CapabilityProfile;

function generateBarcodePDF($barcodeValue, $barcodeType = 'C128') {
    // Create a new Dompdf instance
    $dompdf = new Dompdf();

    // Generate barcode image using Escpos library
    $connector = new DummyPrintConnector();
    $profile = CapabilityProfile::load("simple");
    $printer = new Printer($connector, $profile);
    $printer->setBarcodeHeight(80);
    $printer->setBarcodeWidth(2);
    $printer->barcode($barcodeValue, $barcodeType);
    $printer->feed();

    // Get the printer output as HTML
    $barcodeHtml = $connector->getData();

    // Load HTML content with barcode image
    $html = '
    <html>
    <head>
    <style>
    .barcode {
        text-align: center;
    }
    </style>
    </head>
    <body>
    <div class="barcode">
        ' . $barcodeHtml . '
    </div>
    </body>
    </html>
    ';

    // Load HTML into Dompdf
    $dompdf->loadHtml($html);

    // (Optional) Set PDF rendering options
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();

    // Output the generated PDF
    $dompdf->stream('barcode.pdf', array('Attachment' => false));
}
