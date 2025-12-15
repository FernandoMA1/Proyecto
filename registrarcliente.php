<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contacto =  strip_tags($_POST['contact']);
    $razon_social = strip_tags($_POST['razon_social']);
    $telefono = strip_tags($_POST['telefono']);
    $email = strip_tags($_POST['email']);
    $password = strip_tags($_POST['password']);
    $confirmpassword = strip_tags($_POST['confirmpassword']);

    if ($password !== $confirmpassword) {
        header('Location: auth_signup.php?msg=password_coincidencia');
        exit;
    } else {
        $sql_email = "SELECT email FROM clientes WHERE email = ?";
        $stmt_email = $conexion->prepare($sql_email);
        $stmt_email->bind_param("s", $email);
        $stmt_email->execute();
        $stmt_email->store_result();

        $sql_tel = "SELECT telefono FROM clientes WHERE telefono = ?";
        $stmt_tel = $conexion->prepare($sql_tel);
        $stmt_tel->bind_param("s", $telefono);
        $stmt_tel->execute();
        $stmt_tel->store_result();

        if ($stmt_email->num_rows > 0) {
            header('Location: auth_signup.php?msg=email_en_uso');
            exit;
        } elseif ($stmt_tel->num_rows > 0) {
            header('Location: auth_signup.php?msg=telefono_en_uso');
            exit;
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO clientes (contacto, email, contraseña, telefono, razon_social) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param('sssss', $contacto, $email, $password_hash, $telefono, $razon_social);

            if ($stmt->execute()) {
                header('Location: auth_signup.php?msg=registro_exitoso');
            } else {
                header('Location: auth_signup.php?msg=registro_fallido');
                exit;
            }
            $stmt->close();
        }
        $stmt_email->close();
        $stmt_tel->close();
    }
}

$conexion->close();

?>