<?php 
include("conexion.php");

if (isset($_GET['id']) && !empty($_GET['id'])) {

    $usuario = intval($_GET['id']);

    // Verificar si el usuario tiene tickets asignados
    $check = $conexion->prepare("SELECT COUNT(*) FROM tickets WHERE asignado = ?");
    $check->bind_param("i", $usuario);
    $check->execute();
    $check->bind_result($total_tickets);
    $check->fetch();
    $check->close();

    // Si tiene tickets, no permitir eliminarlo
    if($total_tickets > 0){
        header('Location: listadeusuarios.php?msg=error');
        exit;
    }

    // Eliminar usuario porque no tiene tickets
    $stmt = $conexion->prepare("DELETE FROM USUARIOS WHERE id_usuario = ?");
    $stmt->bind_param("i", $usuario);

    if ($stmt->execute()) {
        header("Location: listadeusuarios.php?msg=user_deleted");
        exit;
    }

    $stmt->close();
}

$conexion->close();
?>
