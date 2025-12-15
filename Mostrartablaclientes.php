<?php 
include("conexion.php");

// Configuración de paginación
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$inicio = ($pagina_actual - 1) * $registros_por_pagina;

// Esta función obtiene todos los clientes de la base de datos con paginación
function obtenerClientes($inicio = 0, $limite = null) {
    global $conexion;
    $clientes = array();
    
    $query = "SELECT * FROM clientes";
    
    // Agregar límite si se especifica
    if ($limite !== null) {
        $query .= " LIMIT $inicio, $limite";
    }
    
    if ($result = $conexion->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $clientes[] = $row;
        }
        $result->free();
    }
    
    return $clientes;
}

// Obtener el total de clientes para la paginación
function obtenerTotalClientes() {
    global $conexion;
    $query = "SELECT COUNT(*) as total FROM clientes";
    $total = 0;
    
    if ($result = $conexion->query($query)) {
        if ($row = $result->fetch_assoc()) {
            $total = $row['total'];
        }
        $result->free();
    }
    
    return $total;
}

$total_registros = obtenerTotalClientes();
$total_paginas = ceil($total_registros / $registros_por_pagina);
?>