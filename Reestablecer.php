<?php
include("conexion.php");

function buscarUsuarioPorEmail($conexion, $email) {
    // Intenta en usuarios
    $stmt = $conexion->prepare("SELECT id_usuario AS id, codigo_recuperacion, expiracion_codigo, 'usuarios' AS tipo FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
        return $res->fetch_assoc();
    }
    // Intenta en clientes
    $stmt = $conexion->prepare("SELECT id_cliente AS id, codigo_recuperacion, expiracion_codigo, 'clientes' AS tipo FROM clientes WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
        return $res->fetch_assoc();
    }
    return null;
}

// Modo 2: Cambiar contraseña (requiere email, codigo y password)
if (isset($_POST['email'], $_POST['codigo'], $_POST['password'])) {
    $email = trim($_POST['email']);
    $codigo = trim($_POST['codigo']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $user = buscarUsuarioPorEmail($conexion, $email);
    if (!$user) {
        header("Location: auth_create_password.php?email=" . urlencode($email) . "&msg=usuario_no_encontrado");
        exit;
    }

    $codigo_db = $user['codigo_recuperacion'];
    $expiracion = $user['expiracion_codigo'];
    $no_expirado = $expiracion && (strtotime($expiracion) > time());

    if (!$codigo_db || !hash_equals($codigo_db, $codigo) || !$no_expirado) {
        header("Location: codigo_de_recuperacion.php?email=" . urlencode($email) . "&msg=error_codigo");
        exit;
    }

    if ($user['tipo'] === 'usuarios') {
        $stmt = $conexion->prepare("UPDATE usuarios SET contraseña=?, codigo_recuperacion=NULL, expiracion_codigo=NULL WHERE id_usuario=?");
        $stmt->bind_param("si", $password, $user['id']);
    } else {
        $stmt = $conexion->prepare("UPDATE clientes SET contraseña=?, codigo_recuperacion=NULL, expiracion_codigo=NULL WHERE id_cliente=?");
        $stmt->bind_param("si", $password, $user['id']);
    }

    if ($stmt && $stmt->execute()) {
        header("Location: auth_create_password.php?msg=cambiada");
        exit;
    } else {
        header("Location: auth_create_password.php?email=" . urlencode($email) . "&msg=error_guardar");
        exit;
    }
}
?>