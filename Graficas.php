<?php 
include("conexion.php");


$meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", 
        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

$tickets_por_mes = [];
$resueltos_por_mes = [];
$en_progreso_por_mes = [];
$clientes_por_mes = [];
for ($mes = 1; $mes <= 12; $mes++) {
    $año_actual = date('Y');
    
    $ticket_mes = mysqli_fetch_assoc(mysqli_query($conexion, 
        "SELECT COUNT(*) AS total FROM tickets 
        WHERE MONTH(fecha_alta) = $mes AND YEAR(fecha_alta) = $año_actual"))['total'];
    
    $resuelto_mes = mysqli_fetch_assoc(mysqli_query($conexion, 
        "SELECT COUNT(*) AS total FROM tickets 
        WHERE MONTH(fecha_alta) = $mes AND YEAR(fecha_alta) = $año_actual 
        AND estatus_ticket IN ('RESUELTO', 'CERRADO')"))['total'];
    
    $progreso_mes = mysqli_fetch_assoc(mysqli_query($conexion, 
        "SELECT COUNT(*) AS total FROM tickets 
        WHERE MONTH(fecha_alta) = $mes AND YEAR(fecha_alta) = $año_actual 
        AND estatus_ticket = 'EN PROGRESO'"))['total'];
    $clientes_mes = mysqli_fetch_assoc(mysqli_query($conexion,
        "SELECT COUNT(*) AS total FROM CLIENTES
        WHERE MONTH(fecha_registro) = $mes AND YEAR(fecha_registro) = $año_actual"))['total'];
    $tickets_por_mes[] = $ticket_mes;
    $resueltos_por_mes[] = $resuelto_mes;
    $en_progreso_por_mes[] = $progreso_mes;
    $clientes_por_mes[] = $clientes_mes;
}

$datos_tickets = json_encode($tickets_por_mes);
$datos_resueltos = json_encode($resueltos_por_mes);
$datos_progreso = json_encode($en_progreso_por_mes);
$datos_clientes = json_encode($clientes_por_mes);

if ($_SESSION['rol'] === 'Soporte') {
    $id_soporte = $_SESSION['id_usuario'];

    $tickets_asignados_mes = [];
    $tickets_resueltos_mes = [];
    $tickets_progreso_mes = [];

    for ($mes = 1; $mes <= 12; $mes++) {
        $año_actual = date('Y');

        $asignados = mysqli_fetch_assoc(mysqli_query($conexion,
            "SELECT COUNT(*) AS total FROM tickets 
            WHERE asignado='$id_soporte'
                AND MONTH(fecha_alta) = $mes
                AND YEAR(fecha_alta) = $año_actual"
        ))['total'];

        $resueltos = mysqli_fetch_assoc(mysqli_query($conexion,
            "SELECT COUNT(*) AS total FROM tickets 
            WHERE asignado='$id_soporte'
                AND MONTH(fecha_alta) = $mes
                AND YEAR(fecha_alta) = $año_actual
                AND estatus_ticket IN ('RESUELTO','CERRADO')"
        ))['total'];

        $progreso = mysqli_fetch_assoc(mysqli_query($conexion,
            "SELECT COUNT(*) AS total FROM tickets 
            WHERE asignado='$id_soporte'
                AND MONTH(fecha_alta) = $mes
                AND YEAR(fecha_alta) = $año_actual
                AND estatus_ticket = 'EN PROGRESO'"
        ))['total'];

        $tickets_asignados_mes[] = $asignados;
        $tickets_resueltos_mes[] = $resueltos;
        $tickets_progreso_mes[] = $progreso;
    }

    $datos_soporte_asignados = json_encode($tickets_asignados_mes);
    $datos_soporte_resueltos = json_encode($tickets_resueltos_mes);
    $datos_soporte_progreso = json_encode($tickets_progreso_mes);
}

?>