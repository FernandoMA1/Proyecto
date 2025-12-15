<?php
session_start();
include("conexion.php");

// Verificar sesión
if (!isset($_SESSION['email']) || !isset($_SESSION['rol'])) {
    header("Location: auth_signin.php");
    exit;
}

$rol = strtolower(trim($_SESSION['rol']));
$old_email = $_SESSION['email'];

// Recoger datos del formulario
$contacto = isset($_POST['contacto']) ? trim($_POST['contacto']) : null;
$razon_social = isset($_POST['razon_social']) ? trim($_POST['razon_social']) : null; // clientes
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : null; // usuarios
$apellidos = isset($_POST['apellidos']) ? trim($_POST['apellidos']) : null; // usuarios
$email = isset($_POST['email']) ? trim($_POST['email']) : null;
$telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : null;

// Validaciones mínimas
if (empty($email) || empty($telefono)) {
    header("Location: configuracion_perfil.php?msg=error_campos");
    exit;
}


if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
    $avatar_dir = 'uploads/perfil/';
    if (!file_exists($avatar_dir)) {
        mkdir($avatar_dir, 0777, true);
    }
    
    $file_name = $_FILES['foto_perfil']['name'];
    $file_tmp  = $_FILES['foto_perfil']['tmp_name'];
    $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array($file_ext, $allowed)) {
        header("Location: configuracion_perfil.php?msg=formato_no_permitido");
        exit;
    }
    
    $new_name = 'avatar_' . uniqid() . '.' . $file_ext;
    $final_path = $avatar_dir . $new_name;
    
    if (!move_uploaded_file($file_tmp, $final_path)) {
        header("Location: configuracion_perfil.php?msg=error_subir_archivo");
        exit;
    }

    if ($rol === 'cliente') {
        $stmt = $conexion->prepare("UPDATE clientes SET avatar = ? WHERE email = ?");
        $stmt->bind_param("ss", $final_path, $old_email);
    } else {
        $stmt = $conexion->prepare("UPDATE usuarios SET avatar = ? WHERE email = ?");
        $stmt->bind_param("ss", $final_path, $old_email);
    }
    
    if (!$stmt->execute()) {
        header("Location: configuracion_perfil.php?msg=error_actualizar_avatar");
        exit;
    }
    
    $_SESSION['avatar'] = $final_path;
}


if ($rol === 'cliente') {
    $sql = "UPDATE clientes SET contacto = ?, email = ?, telefono = ?, Razon_social = ? WHERE email = ?";
    $stmt = $conexion->prepare($sql);
    $contacto_safe = $contacto ?: $_SESSION['nombre'];
    $razon_safe = $razon_social ?: $_SESSION['razon'];
    $stmt->bind_param('sssss', $contacto_safe, $email, $telefono, $razon_safe, $old_email);
} else {
    $sql = "UPDATE usuarios SET nombre = ?, apellidos = ?, email = ?, telefono = ? WHERE email = ?";
    $stmt = $conexion->prepare($sql);
    $nombre_safe = $nombre ?: $_SESSION['nombre'];
    $apellidos_safe = $apellidos ?: (isset($_SESSION['apellidos']) ? $_SESSION['apellidos'] : '');
    $stmt->bind_param('sssss', $nombre_safe, $apellidos_safe, $email, $telefono, $old_email);
}

if (!$stmt) {
    header("Location: configuracion_perfil.php?msg=error_sql");
    exit;
}

if ($stmt->execute()) {
    $_SESSION['email'] = $email;
    $_SESSION['telefono'] = $telefono;
    $_SESSION['nombre'] = $nombre ?: $_SESSION['nombre'];
    $_SESSION['nombre_completo'] = ($nombre ?: $_SESSION['nombre']) . ' ' . ($apellidos ?: $_SESSION['apellidos']);
    if ($rol === 'cliente') {
        $_SESSION['nombre'] = $contacto ?: $_SESSION['nombre'];
        $_SESSION['razon'] = $razon_social ?: $_SESSION['razon'];
    } else {
        $stmt2 = $conexion->prepare("SELECT nombre, apellidos FROM usuarios WHERE email = ?");
        $stmt2->bind_param('s', $email);
        if ($stmt2->execute()) {
            $stmt2->bind_result($nombre_db, $apellidos_db);
            if ($stmt2->fetch()) {
                $_SESSION['apellidos'] = $apellidos_db ?: '';
                $_SESSION['nombre'] = $nombre_db ?: '';
            }
        }
        if (isset($stmt2)) { $stmt2->close(); }
    }

    header("Location: configuracion_perfil.php?msg=perfil_actualizado");
    exit;
} else {
    header("Location: configuracion_perfil.php?msg=error_guardar");
    exit;
}
?>
