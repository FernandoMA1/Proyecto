<?php
require 'vendor/autoload.php';
include("conexion.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$tabla = isset($_GET['tabla']) ? $_GET['tabla'] : '';

$allowed = ['usuarios','clientes'];
if (!in_array($tabla, $allowed, true)) {
    http_response_code(400);
    echo 'Tabla inválida.';
    exit;
}

// Definir columnas seguras por tabla
$safeColumnsMap = [
    'usuarios' => [
        'id_usuario', 'nombre', 'apellidos', 'email', 'telefono', 'rol', 'estatus_Usuario', 'fecha_registro_usuario'
    ],
    'clientes' => [
        'id_cliente', 'contacto', 'email', 'telefono', 'Razon_social', 'estatus_cliente', 'fecha_registro'
    ],
];

$headersMap = [
    'usuarios' => ['ID','Nombre(s)','Apellidos','Correo','Telefono','Rol','Estatus','Fecha de Registro'],
    'clientes' => ['ID','Contacto','Correo','Telefono','Razon Social','Estatus','Fecha de Registro'],
];

$cols = $safeColumnsMap[$tabla];
$headers = $headersMap[$tabla];

// Construir consulta segura
$select = implode(', ', $cols);
$stmt = $conexion->prepare("SELECT $select FROM $tabla");
$stmt->execute();
$result = $stmt->get_result();

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle(ucfirst($tabla));

// Encabezados estilizados
foreach ($headers as $i => $header) {
    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
    $cell = $colLetter . '1';
    $sheet->setCellValue($cell, $header);
    $sheet->getStyle($cell)->getFont()->setBold(true)->setColor(new Color(Color::COLOR_WHITE));
    $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF4CAF50');
    $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
}

// Datos
$rowNum = 2;
while ($row = $result->fetch_assoc()) {
    foreach ($cols as $i => $colName) {
        $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
        $value = $row[$colName];
        // Formatear fecha si aplica
        if (in_array($colName, ['fecha_registro_usuario', 'fecha_registro'], true) && !empty($value)) {
            $value = date('d/m/Y', strtotime($value));
        }
        $sheet->setCellValue($colLetter . $rowNum, $value);
    }
    $rowNum++;
}

// Bordes y autosize
$lastColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
$sheet->getStyle('A1:' . $lastColLetter . ($rowNum - 1))
    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

for ($i = 1; $i <= count($headers); $i++) {
    $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
}

$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $tabla . '.xlsx"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
exit;
?>