<?php
include('conexion.php');

$id_cliente = isset($_GET['id']) ? intval($_GET['id']) : 0;
$cliente = null;

if ($id_cliente > 0) {
    $query = "SELECT contacto, Razon_social, email, telefono, estatus_cliente FROM Clientes WHERE id_cliente = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $id_cliente);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $cliente = $result->fetch_assoc();
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $id_cliente > 0) {
    $contacto = trim($_POST['contacto']);
    $razon_social = trim($_POST['razon']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $estatus_cliente = trim($_POST['estatus']);

    $sql = "UPDATE Clientes SET contacto = ?, Razon_social = ?, email = ?, telefono = ?, estatus_cliente = ? WHERE id_cliente = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('sssssi', $contacto, $razon_social, $email, $telefono, $estatus_cliente, $id_cliente);

    if ($stmt->execute()) {
        header("Location: view-client.php?id=$id_cliente&msg=estatus_editado");
        exit;
    } else {
        header("Location: view-client.php?id=$id_cliente&msg=error_guardar");
        exit;
    }
}
?>
