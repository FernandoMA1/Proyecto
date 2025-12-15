<?php
session_start();
include("conexion.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id_ticket = isset($_GET['id_ticket']) ? intval($_GET['id_ticket']) : 0;
    
    if ($id_ticket <= 0) {
        echo json_encode(['success' => false, 'error' => 'ID de ticket inválido']);
        exit;
    }
    
    $stmt = $conexion->prepare("SELECT * FROM mensajes_chat WHERE id_ticket = ? ORDER BY fecha_envio ASC");
    $stmt->bind_param("i", $id_ticket);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    $mensajes = [];
    $html_mensajes = '';
    
    while ($mensaje = $resultado->fetch_assoc()) {
        $mensajes[] = $mensaje;
        $html_mensajes .= generar_html_mensaje($mensaje);
    }
    
    echo json_encode([
        'success' => true,
        'mensajes' => $mensajes,
        'html' => $html_mensajes,
        'total' => count($mensajes)
    ]);
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}

function generar_html_mensaje($mensaje) {
    $fecha = date('d/m/Y H:i', strtotime($mensaje['fecha_envio']));
    $es_propio = isset($_SESSION['nombre']) && $_SESSION['nombre'] === $mensaje['usuario'];
    $clase_mensaje = $es_propio ? 'text-end' : 'text-start';
    $clase_burbuja = $es_propio ? 'bg-light text-dark' : 'bg-light';
    
    $html = "<div class='mb-3 {$clase_mensaje}'>";
    $html .= "<div class='d-inline-block p-2 rounded {$clase_burbuja}' style='max-width: 70%;'>";
    $html .= "<div class='fw-bold'>{$mensaje['usuario']} ({$mensaje['rol_usuario']})</div>";
    
    if (!empty($mensaje['archivo_adjunto'])) {
        if ($mensaje['tipo_archivo'] === 'imagen') {
            $html .= "<img src='{$mensaje['archivo_adjunto']}' class='img-fluid rounded mb-2' style='max-width: 200px;' onclick='window.open(this.src, \"_blank\")'>";
        } else if ($mensaje['tipo_archivo'] === 'video') {
            $html .= "<video controls class='rounded mb-2' style='max-width: 200px;'>";
            $html .= "<source src='{$mensaje['archivo_adjunto']}' type='video/mp4'>";
            $html .= "Tu navegador no soporta el elemento video.";
            $html .= "</video>";
        }
    }
    
    $html .= "<div>{$mensaje['mensaje']}</div>";
    $html .= "<small class='text-muted'>{$fecha}</small>";
    $html .= "</div></div>";
    
    return $html;
}

$conexion->close();
?>