<?php
session_start();
include("conexion.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_ticket = isset($_POST['id_ticket']) ? intval($_POST['id_ticket']) : 0;
    $mensaje = isset($_POST['mensaje']) ? trim($_POST['mensaje']) : '';
    $usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : (isset($_SESSION['email']) ? $_SESSION['email'] : 'Invitado');
    $rol_usuario = isset($_SESSION['rol']) ? $_SESSION['rol'] : 'Cliente';
    
    if ($id_ticket <= 0) {
        echo json_encode(['success' => false, 'error' => 'ID de ticket inválido']);
        exit;
    }
    
    if (empty($mensaje)) {
        echo json_encode(['success' => false, 'error' => 'El mensaje no puede estar vacío']);
        exit;
    }
    
    $archivo_adjunto = null;
    $tipo_archivo = null;
    
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
        $archivo = $_FILES['archivo'];
        $nombre_archivo = $archivo['name'];
        $tipo_mime = $archivo['type'];
        $tamaño = $archivo['size'];
        
        $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'video/mp4', 'video/webm', 'video/ogg'];
        
        if (!in_array($tipo_mime, $tipos_permitidos)) {
            echo json_encode(['success' => false, 'error' => 'Tipo de archivo no permitido']);
            exit;
        }
        
        if ($tamaño > 10 * 1024 * 1024) {
            echo json_encode(['success' => false, 'error' => 'El archivo es demasiado grande (máximo 10MB)']);
            exit;
        }
        
        $directorio_uploads = 'uploads/chat/';
        if (!file_exists($directorio_uploads)) {
            mkdir($directorio_uploads, 0777, true);
        }
        
        $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
        $nombre_unico = uniqid() . '_' . time() . '.' . $extension;
        $ruta_archivo = $directorio_uploads . $nombre_unico;
        
        if (move_uploaded_file($archivo['tmp_name'], $ruta_archivo)) {
            $archivo_adjunto = $ruta_archivo;
            $tipo_archivo = strpos($tipo_mime, 'image/') === 0 ? 'imagen' : 'video';
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al subir el archivo']);
            exit;
        }
    }
    
    $stmt = $conexion->prepare("INSERT INTO mensajes_chat (id_ticket, usuario, rol_usuario, mensaje, archivo_adjunto, tipo_archivo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $id_ticket, $usuario, $rol_usuario, $mensaje, $archivo_adjunto, $tipo_archivo);
    
    if ($stmt->execute()) {
        $id_mensaje = $conexion->insert_id;
        
        $stmt_select = $conexion->prepare("SELECT * FROM mensajes_chat WHERE id_mensaje = ?");
        $stmt_select->bind_param("i", $id_mensaje);
        $stmt_select->execute();
        $resultado = $stmt_select->get_result();
        $mensaje_nuevo = $resultado->fetch_assoc();
        
        echo json_encode([
            'success' => true, 
            'mensaje' => $mensaje_nuevo,
            'html' => generar_html_mensaje($mensaje_nuevo)
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al enviar el mensaje: ' . $conexion->error]);
    }
    
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
            $html .= "<img src='{$mensaje['archivo_adjunto']}' class='img-fluid rounded mb-2' style='max-width: 200px;'>";
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