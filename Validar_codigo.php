<?php
$email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';

// Procesar validación del código
if (isset($_POST['email'], $_POST['codigo'])) {
    $email_post = trim($_POST['email']);
    $codigo_post = trim($_POST['codigo']);
    
    // Buscar usuario en ambas tablas
    $user_found = false;
    $codigo_valido = false;
    
    // Buscar en usuarios
    $stmt = $conexion->prepare("SELECT codigo_recuperacion, expiracion_codigo FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email_post);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user_found = true;
        $row = $result->fetch_assoc();
        $codigo_db = $row['codigo_recuperacion'];
        $expiracion = $row['expiracion_codigo'];
        
        if ($codigo_db && $codigo_db === $codigo_post && $expiracion && strtotime($expiracion) > time()) {
            $codigo_valido = true;
        }
    } else {
        // Buscar en clientes
        $stmt = $conexion->prepare("SELECT codigo_recuperacion, expiracion_codigo FROM clientes WHERE email = ?");
        $stmt->bind_param("s", $email_post);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user_found = true;
            $row = $result->fetch_assoc();
            $codigo_db = $row['codigo_recuperacion'];
            $expiracion = $row['expiracion_codigo'];
            
            if ($codigo_db && $codigo_db === $codigo_post && $expiracion && strtotime($expiracion) > time()) {
                $codigo_valido = true;
            }
        }
    }
    
    if (!$user_found) {
        header("Location: codigo_de_recuperacion.php?email=" . urlencode($email_post) . "&msg=usuario_no_encontrado");
        exit;
    }
    
    if ($codigo_valido) {
        header("Location: auth_create_password.php?email=" . urlencode($email_post) . "&codigo=" . urlencode($codigo_post) . "&msg=codigo_ok");
        exit;
    } else {
        header("Location: codigo_de_recuperacion.php?email=" . urlencode($email_post) . "&msg=error_codigo");
        exit;
    }
}
?>