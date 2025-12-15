<?php
session_start();
include("conexion.php");

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $ticket = $_GET['id'];

    $stmt = $conexion->prepare("DELETE FROM tickets WHERE id_ticket = ?");
    $stmt->bind_param("i", $ticket);

    if ($stmt->execute()) {
        if ($_SESSION['rol'] == 'Cliente') {
            header("Location: mistickets.php");
        } elseif ($_SESSION['rol'] == 'Soporte') {
            header("Location: tickets_asignados.php");
        } elseif ($_SESSION['rol'] == 'Administrador') {
            header("Location: apps-project-list.php");
        }
        exit;
    } else {
        echo "Error al eliminar el ticket: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "ID de ticket no válido.";
}

$conexion->close();
?>