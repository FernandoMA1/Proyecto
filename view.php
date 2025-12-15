<?php 
include('conexion.php');

if(isset($_GET['id'])){
    $id_usuario = intval($_GET['id']);

    $query = "SELECT * FROM USUARIOS WHERE id_usuario = $id_usuario";
    $result = $conexion->query($query);

    if($result && $result ->num_rows>0){
        $usuario = $result->fetch_assoc();
    }
}else{
    if(isset($_GET['id'])){
        $id_cliente = intval($_GET['id']);
        $query ="SELECT * FROM CLIENTES WHERE id_cliente = $id_cliente";
        $result = $conexion->query($query);
        if($result && $result -> num_rows>0){
            $cliente = $result->fetch_assoc();
        }
    }
}
?>