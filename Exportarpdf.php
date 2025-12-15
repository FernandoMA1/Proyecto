<?php
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 0);

require 'fpdf/fpdf.php';
include("conexion.php");

$tabla = isset($_GET['tabla']) ? $_GET['tabla'] : '';
$allowed = ['usuarios','clientes'];
if (!in_array($tabla, $allowed, true)) {
    http_response_code(400);
    echo 'Tabla inválida.';
    exit;
}

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

$select = implode(', ', $cols);
$stmt = $conexion->prepare("SELECT $select FROM $tabla");
$stmt->execute();
$result = $stmt->get_result();

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial','B',14);

        $this->Cell(0,10, iconv('UTF-8', 'ISO-8859-1', ucfirst($GLOBALS['tabla'])), 0, 1, 'C');
        $this->Ln(2);
    }
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','',8);
        $this->Cell(0,10, 'Pagina '.$this->PageNo().'/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF('L','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);

$colWidthsMap = [
    'usuarios' => [20,35,35,60,30,30,30,35],
    'clientes' => [20,45,60,30,60,30,35],
];
$widths = $colWidthsMap[$tabla];

$pdf->SetFillColor(79, 147, 242);
$pdf->SetTextColor(255);
$pdf->SetFont('Arial','B',10);

foreach ($headers as $i => $h) {
    $pdf->Cell($widths[$i], 8, iconv('UTF-8', 'ISO-8859-1', $h), 1, 0, 'C', true);
}
$pdf->Ln();


$pdf->SetFont('Arial','',9);
$pdf->SetTextColor(0);
$pdf->SetFillColor(245,245,245);
$fill = false;

while ($row = $result->fetch_assoc()) {
    foreach ($cols as $i => $colName) {
        $val = $row[$colName];
        if (in_array($colName, ['fecha_registro_usuario','fecha_registro'], true) && !empty($val)) {
            $val = date('d/m/Y', strtotime($val));
        }
        $pdf->Cell($widths[$i], 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', (string)$val), 1, 0, 'L', $fill);
    }
    $pdf->Ln();
    $fill = !$fill;
}

$pdf->Output('D', $tabla . '.pdf');
exit;
?>
