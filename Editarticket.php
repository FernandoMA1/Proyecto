<?php
include("conexion.php");

if (isset($_GET['id'])) {
    $id_ticket = intval($_GET['id']);

    $query = "SELECT asunto, descripcion, Prioridad FROM TICKETS WHERE id_ticket = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $id_ticket); 
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $ticket = $result->fetch_assoc();
    } else {
        echo "No se encontró el ticket seleccionado";
        exit;
    }
} else {
    echo "ID de ticket no proporcionado";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $asunto = isset($_POST['asunto']) ? trim($_POST['asunto']) : null;
    $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : null;
    $prioridad = isset($_POST['prioridad']) ? trim($_POST['prioridad']) : null;

    $sql = "UPDATE TICKETS SET asunto = ?, descripcion = ?, Prioridad = ? WHERE id_ticket = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('sssi', $asunto, $descripcion, $prioridad, $id_ticket);
    
    if (isset($_POST['archivos_a_eliminar'])) {
        foreach ($_POST['archivos_a_eliminar'] as $archivo_id) {
            $sql_eliminar = "DELETE FROM archivos WHERE id = ?";
            $stmt_eliminar = $conexion->prepare($sql_eliminar);
            $stmt_eliminar->bind_param('i', $archivo_id);
            $stmt_eliminar->execute();
        }
    }

    if (!empty($_FILES['image']['tmp_name'][0])) {
        $archivos_count = count($_FILES['image']['tmp_name']);
        $max_archivos = 3;
        $upload_dir = 'uploads/tickets/';

        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $archivos_count = min($archivos_count, $max_archivos);

        for ($i = 0; $i < $archivos_count; $i++) {
            if (is_uploaded_file($_FILES['image']['tmp_name'][$i])) {
                $archivo_nombre = $_FILES['image']['name'][$i];
                $archivo_tipo = $_FILES['image']['type'][$i];

                $extension = pathinfo($archivo_nombre, PATHINFO_EXTENSION);
                $nombre_unico = uniqid('ticket_' . $id_ticket . '_') . '.' . $extension;
                $ruta_archivo = $upload_dir . $nombre_unico;

                if (move_uploaded_file($_FILES['image']['tmp_name'][$i], $ruta_archivo)) {
                    $sql_archivo = "INSERT INTO archivos (ticket_id, nombre_archivo, tipo_archivo, ruta_archivo, fecha_subida) VALUES (?, ?, ?, ?, NOW())";
                    $stmt_archivo = $conexion->prepare($sql_archivo);
                    $stmt_archivo->bind_param('isss', $id_ticket, $archivo_nombre, $archivo_tipo, $ruta_archivo);
                    $stmt_archivo->execute();
                    $stmt_archivo->close();
                }
            }
        }
    }

    if (!$stmt) {
        header("Location: ver_ticket.php?id=$id_ticket&msg=error_sql");
        exit;
    }

    if ($stmt->execute()) {
        header("Location: ver_ticket.php?id=$id_ticket&msg=ticket_editado");
        exit;
    } else {
        header("Location: ver_ticket.php?id=$id_ticket&msg=error_guardar");
        exit;
    }
}
?>