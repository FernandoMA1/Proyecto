<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticket_id = $_POST['ticket_id'];
    $usuario_asignado = $_POST['asignar'];

    $sql = "UPDATE TICKETS SET asignado = ? WHERE id_ticket = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $usuario_asignado, $ticket_id);

    if ($stmt->execute()) {
        header('Location: apps-project-list.php?msg=ticket_asignado');
        exit;
    } else {
        header('Location: apps-project-list.php?msg=error_asignar');
    }

    $stmt->close();
}