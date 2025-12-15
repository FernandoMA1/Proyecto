<?php
include("conexion.php");

if (!isset($_POST['id']) || !isset($_POST['id_ticket'])) {
    die("Datos incompletos");
}

$id = intval($_POST['id']);
$id_ticket = intval($_POST['id_ticket']);

$query = $conexion->prepare("SELECT ruta_archivo FROM archivos WHERE id = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    die("Archivo no encontrado");
}

$archivo = $result->fetch_assoc();
$ruta = $archivo['ruta_archivo'];

if (!empty($ruta) && file_exists($ruta)) {
    unlink($ruta);
}

$delete = $conexion->prepare("DELETE FROM archivos WHERE id = ?");
$delete->bind_param("i", $id);
$delete->execute();

header("Location: ver_ticket.php?id=" . $id_ticket);
exit;
?>
