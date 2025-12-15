<?php 
include("conexion.php");

if(isset($_GET['id'])){
    $id_ticket = intval($_GET['id']);

    $query = "SELECT t.*, u.nombre AS asignado_nombre, u.apellidos AS asignado_apellidos FROM TICKETS t LEFT JOIN Usuarios u ON t.asignado = u.id_usuario WHERE t.id_ticket = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $id_ticket);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result && $result->num_rows > 0){
        $ticket = $result->fetch_assoc();
    } else {
        echo "No se encontró el ticket seleccionado";
        exit;
    }
} else {
    echo "ID de ticket no proporcionado";
    exit;
}

$cliente_nombre = $ticket['cliente']; 
$cliente = null;

$query = "SELECT id_cliente, razon_social, contacto, telefono, email, avatar FROM Clientes WHERE contacto = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param('s', $cliente_nombre);
$stmt->execute();
$result = $stmt->get_result();

if($result && $result->num_rows > 0){
    $cliente = $result->fetch_assoc();
}

$stmt->close();

?>

