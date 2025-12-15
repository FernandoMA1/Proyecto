<?php
include("conexion.php");

if (isset($_GET['id'])) {
    $id_ticket = intval($_GET['id']);

    $query = "SELECT estatus_ticket FROM TICKETS WHERE id_ticket = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $id_ticket);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $ticket = $result->fetch_assoc();
    } else {
        echo "No se encontró el ticket seleccionado";
        exit;
    }
} else {
    echo "ID de ticket no proporcionado";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $estatus_ticket = isset($_POST['estatus_ticket']) ? trim($_POST['estatus_ticket']) : null;

    if (empty($estatus_ticket)) {
        header("Location: ver_ticket.php?id=$id_ticket&msg=error");
        exit;
    }

    $estados_con_fecha = ['CERRADO', 'RESUELTO'];

    if (in_array(strtoupper($estatus_ticket), $estados_con_fecha, true)) {
        $sql = "UPDATE TICKETS SET estatus_ticket = ?, fecha_solucion = NOW() WHERE id_ticket = ?";
    } else {
        $sql = "UPDATE TICKETS SET estatus_ticket = ?, fecha_solucion = NULL WHERE id_ticket = ?";
    }

    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        header("Location: ver_ticket.php?id=$id_ticket&msg=error_sql");
        exit;
    }

    $stmt->bind_param('si', $estatus_ticket, $id_ticket);

    if ($stmt->execute()) {
        header("Location: ver_ticket.php?id=$id_ticket&msg=estatus_actualizado");
        exit;
    } else {
        header("Location: ver_ticket.php?id=$id_ticket&msg=error_guardar");
        exit;
    }
}
?>