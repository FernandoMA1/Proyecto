<?php 
include("conexion.php");

$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$inicio = ($pagina_actual - 1) * $registros_por_pagina;

$filtro_estatus = isset($_GET['estatus']) ? $_GET['estatus'] : '';
$filtro_registro = isset($_GET['fecha']) ? $_GET['fecha'] : '';
$filtro_rol = isset($_GET['rol']) ? $_GET['rol'] : '';

$sql_count = 'SELECT COUNT(*) FROM USUARIOS';
$sql = 'SELECT * FROM USUARIOS';

$where_conditions = [];
$params = [];
$types = "";

if (!empty($filtro_estatus)) {
    $where_conditions[] = "estatus_Usuario = ?";
    $params[] = $filtro_estatus;
    $types .= "s";
}

if (!empty($filtro_fecha)) {
    $where_conditions[] = "fecha_registro_usuario = ?";
    $params[] = $filtro_fecha;
    $types .= "s";
}

if(!empty($filtro_rol)){
    $where_conditions[] = "rol = ?";
    $params[] = $filtro_rol;
    $types .= "s";
}

if (!empty($where_conditions)) {
    $sql .= " WHERE " . implode(" AND ", $where_conditions);
    $sql_count .= " WHERE " . implode(" AND ", $where_conditions);
}

$sql .= " ORDER BY id_usuario DESC LIMIT ?, ?";
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