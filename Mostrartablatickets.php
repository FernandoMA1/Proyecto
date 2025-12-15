<?php
include("conexion.php");

$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$inicio = ($pagina_actual - 1) * $registros_por_pagina;

$filtro_mes = isset($_GET['mes']) ? $_GET['mes'] : '';
$filtro_anio = isset($_GET['anio']) ? $_GET['anio'] : '';

// Usar el id del soporte desde la sesión si el rol es "Soporte"
$id_soporte = (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Soporte' && isset($_SESSION['id_usuario'])) 
    ? intval($_SESSION['id_usuario']) 
    : null;

$sql_count = 'SELECT COUNT(t.id_ticket) FROM TICKETS t LEFT JOIN Usuarios u ON t.asignado = u.id_usuario';
$sql = 'SELECT t.*, u.nombre AS asignado_nombre, u.apellidos AS asignado_apellidos FROM TICKETS t LEFT JOIN Usuarios u ON t.asignado = u.id_usuario';

$where_conditions = [];
$params = [];
$types = "";

if (!empty($filtro_mes)) {
    $where_conditions[] = "MONTH(t.fecha_alta) = ?";
    $params[] = intval($filtro_mes);
    $types .= "i";
}

if (!empty($filtro_anio)) {
    $where_conditions[] = "YEAR(t.fecha_alta) = ?";
    $params[] = intval($filtro_anio);
    $types .= "i";
}

if (isset($tickets_cliente) && $tickets_cliente === true && isset($_SESSION['nombre'])) {
    $where_conditions[] = "t.cliente = ?";
    $params[] = $_SESSION['nombre'];
    $types .= "s";
}

// Agregar condición por soporte si existe
if ($id_soporte !== null) {
    $where_conditions[] = "t.asignado = ?";
    $params[] = $id_soporte;
    $types .= "i";
}

if (!empty($where_conditions)) {
    $sql .= " WHERE " . implode(" AND ", $where_conditions);
    $sql_count .= " WHERE " . implode(" AND ", $where_conditions);
}

$sql .= " ORDER BY t.id_ticket DESC LIMIT ?, ?";
$types .= "ii";
$params[] = $inicio;
$params[] = $registros_por_pagina;

$stmt_count = $conexion->prepare($sql_count);
if (!empty($params) && count($params) > 0 && !empty($types)) {
    $params_count = array_slice($params, 0, -2);
    $types_count = substr($types, 0, -2);
    if (!empty($params_count)) {
        $stmt_count->bind_param($types_count, ...$params_count);
    }
}
$stmt_count->execute();
$stmt_count->bind_result($total_registros);
$stmt_count->fetch();
$stmt_count->close();

$total_paginas = ceil($total_registros / $registros_por_pagina);

$stmt = $conexion->prepare($sql);
if (!empty($params) && !empty($types)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$resultado = $stmt->get_result(); 
?>