<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = strip_tags($_POST["nombre"] ?? '');
    $apellidos = strip_tags($_POST["apellidos"] ?? '');
    $telefono = strip_tags($_POST["telefono"] ?? '');
    $Rol = strip_tags($_POST["rol"] ?? '');
    $email = strip_tags($_POST["email"] ?? '');
    $password = strip_tags($_POST["password"] ?? '');
    $confirmpassword = strip_tags($_POST["confirmpassword"] ?? '');



    if ($password !== $confirmpassword) {
        header('Location: listadeusuarios.php?msg=password_coincidencia');
        exit;
    } else {
        $sql_email = "SELECT email FROM usuarios WHERE email = ?";
        $stmt_email = $conexion->prepare($sql_email);
        $stmt_email->bind_param("s", $email);
        $stmt_email->execute();
        $stmt_email->store_result();

        $sql_email = "SELECT email FROM Clientes WHERE email = ?";
        $stmt_email = $conexion->prepare($sql_email);
        $stmt_email->bind_param("s", $email);
        $stmt_email->execute();
        $stmt_email->store_result();

        $sql_tel = "SELECT telefono FROM usuarios WHERE telefono = ?";
        $stmt_tel = $conexion->prepare($sql_tel);
        $stmt_tel->bind_param("s", $telefono);
        $stmt_tel->execute();
        $stmt_tel->store_result();

        $sql_tel = "SELECT telefono FROM Clientes WHERE telefono = ?";
        $stmt_tel = $conexion->prepare($sql_tel);
        $stmt_tel->bind_param("s", $telefono);
        $stmt_tel->execute();
        $stmt_tel->store_result();

        if ($stmt_email->num_rows > 0) {
            header('Location: listadeusuarios.php?msg=email_en_uso');
            exit;
        } elseif ($stmt_tel->num_rows > 0) {
            header('Location: listadeusuarios.php?msg=telefono_en_uso');
            exit;
        } else {

            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO usuarios (nombre, apellidos, email, contraseña, telefono, rol) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param('ssssss', $nombre, $apellidos, $email, $password_hash, $telefono, $Rol);

            if ($stmt->execute()) {
                header('Location: listadeusuarios.php?msg=registro_exitoso');
            } else {
                header('Location: listadeusuarios.php?msg=registro_fallido');
                exit;
            }
            $stmt->close();
        }
        $stmt_email->close();
        $stmt_tel->close();
    }
}
?>