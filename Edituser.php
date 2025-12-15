<?php
include("conexion.php");

if (isset($_GET['id'])) {
    $id_usuario = intval($_GET['id']);

    $query = "SELECT nombre, apellidos, email, telefono, rol, estatus_usuario FROM Usuarios WHERE id_usuario = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
    } else {
        $usuario = null;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $rol = trim($_POST['rol']);
    $estatus_usuario = trim($_POST['estatus']);

    $sql = "UPDATE Usuarios SET nombre = ?, apellidos = ?, email = ?, telefono = ?, rol = ?, estatus_Usuario = ? WHERE id_usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('ssssssi', $nombre, $apellidos, $email, $telefono, $rol, $estatus_usuario, $id_usuario);

    if ($stmt->execute()) {
        header("Location: view-user.php?id=$id_usuario&msg=usuario_editado");
        exit;
    } else {
        header("Location: view-user.php?id=$id_usuario&msg=error_guardar");
        exit;
    }
}

?>
