<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['email']) || !isset($_SESSION['rol'])) {
    echo json_encode(["ok" => false, "msg" => "no_auth"]);
    exit;
}

$rol = strtolower($_SESSION['rol']);
$email = $_SESSION['email'];

$default = "assets/images/avatar/avatar-default.png";

if (isset($_SESSION['avatar']) && 
    $_SESSION['avatar'] !== $default && 
    file_exists($_SESSION['avatar'])) 
{
    unlink($_SESSION['avatar']);
}

if ($rol === "cliente") {
    $stmt = $conexion->prepare("UPDATE clientes SET avatar = ? WHERE email = ?");
} else {
    $stmt = $conexion->prepare("UPDATE usuarios SET avatar = ? WHERE email = ?");
}

$stmt->bind_param("ss", $default, $email);
$stmt->execute();

$_SESSION['avatar'] = $default;

echo json_encode(["ok" => true]);
exit;
?>
