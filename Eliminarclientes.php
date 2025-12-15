<?php 
include("conexion.php");

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $cliente_id = intval($_GET['id']);

    // 1. Sacar el nombre del cliente (contacto) según el id_cliente
    $stmt_cliente = $conexion->prepare("SELECT contacto FROM clientes WHERE id_cliente = ?");
    $stmt_cliente->bind_param("i", $cliente_id);
    $stmt_cliente->execute();
    $stmt_cliente->bind_result($contacto_cliente);
    $stmt_cliente->fetch();
    $stmt_cliente->close();

    if (empty($contacto_cliente)) {
        echo "Cliente no encontrado.";
        exit;
    }

    // 2. Verificar si ese contacto aparece en tickets
    $check = $conexion->prepare("SELECT COUNT(*) FROM tickets WHERE cliente = ?");
    $check->bind_param("s", $contacto_cliente);
    $check->execute();
    $check->bind_result($total_tickets);
    $check->fetch();
    $check->close();

    if ($total_tickets > 0) {
        header('Location: apps-crm-contact.php?msg=error');
        exit;
    }

    // 3. Eliminar si no tiene tickets
    $del = $conexion->prepare("DELETE FROM clientes WHERE id_cliente = ?");
    $del->bind_param("i", $cliente_id);

    if ($del->execute()) {
        header("Location: apps-crm-contact.php?msg=client_deleted");
        exit;
    }
    $del->close();

}

$conexion->close();
?>
