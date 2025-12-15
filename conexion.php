<?php 
$host = 'localhost';
$user = 'root';
$pass = 'Estancias_2';
$db = 'Control_Sistema';

$conexion = new mysqli($host, $user, $pass, $db);
if($conexion ->connect_error){
    die("error de conexion.". $conexion ->connect_error);
}
?>