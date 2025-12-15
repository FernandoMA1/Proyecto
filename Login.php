<?php
include("conexion.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id_usuario, nombre, contraseña, rol, telefono, apellidos, fecha_registro_usuario, estatus_Usuario, avatar FROM usuarios WHERE email = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows == 1){
        $stmt->bind_result($id_usuario, $nombre, $password_hash, $rol, $telefono, $apellidos, $fecha_registro_usuario, $estatus_user, $avatar);
        $stmt->fetch();

        if($password_hash !== null && password_verify($password, $password_hash)){
            if($estatus_user === 'No Disponible'){
                header('Location: auth_signin.php?msg=estatus_error');
                exit;
            }
            $_SESSION['id_usuario'] = $id_usuario;
            $_SESSION['avatar'] = $avatar;
            $_SESSION['email'] = $email;
            $_SESSION['rol'] = $rol;
            $_SESSION['apellidos'] = $apellidos;
            $_SESSION['nombre'] = $nombre;
            $_SESSION['nombre_completo'] = trim($nombre . ' ' . $apellidos);
            $_SESSION['telefono'] = $telefono;
            $_SESSION['fecha_alta'] = $fecha_registro_usuario;
            $_SESSION['estatus_Usuario'] = $estatus_user;
            $_SESSION['login_ok'] = 1;

            if($rol == "Administrador"){
                header('Location: dashboard_prueba.php?msg=login');
            } elseif ($rol == "Soporte"){
                header('Location: dashboard_prueba.php?msg=login');
            }
            exit;
        }else{
            header('Location: auth_signin.php?msg=contraseña_error');
            exit;
        }
    }else{
        $sql = "SELECT id_cliente, contacto, contraseña, telefono, fecha_registro, Razon_social, estatus_cliente, avatar FROM clientes WHERE email = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows == 1){
        $stmt->bind_result($id_cliente, $contacto, $password_hash, $telefono, $fecha_registro, $Razon_social, $estatus, $avatar);
        $stmt->fetch();
        
        if($password_hash !== null && password_verify($password, $password_hash)){
            if($estatus === 'No Disponible'){
                header('Location: auth_signin.php?msg=estatus_error');
                exit;
            }

            $_SESSION['id_usuario'] = $id_cliente;
            $_SESSION['avatar'] = $avatar;
            $_SESSION['email'] = $email;
            $_SESSION['rol'] = 'Cliente';
            $_SESSION['nombre'] = $contacto;
            $_SESSION['telefono'] = $telefono;
            $_SESSION['fecha_alta'] = $fecha_registro;
            $_SESSION['razon'] = $Razon_social;
            $_SESSION['estatus_cliente'] = $estatus;
            $_SESSION['login_ok'] = 1;
            header('Location: dashboard_prueba.php?msg=login');
            exit;
        }else{
            header('Location: auth_signin.php?msg=contraseña_error');
            exit;
        }
    }else{
        header('Location: auth_signin.php?msg=email_error');
    }
}
$stmt->close();
}
$conexion->close();
?>