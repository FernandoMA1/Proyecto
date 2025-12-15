<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_FILES['image']['tmp_name'][0])) {
        $archivos_count = count($_FILES['image']['tmp_name']);
        $max_archivos = 3;

        if ($archivos_count > $max_archivos) {
            header("Location: apps-project-create.php?msg=max_archivos");
            exit;
        }
    }

    $asunto = strip_tags($_POST["asunto"]);
    $cliente = strip_tags($_POST['cliente']);
    $descripcion = $_POST["descripcion"];
    $prioridad = strip_tags($_POST["prioridad"]);
    $soporte = !empty($_POST['asignar']) ? strip_tags($_POST['asignar']) : NULL;

    $sql = "INSERT INTO tickets(asunto, cliente, asignado, descripcion, Prioridad) VALUES(?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('ssiss', $asunto, $cliente, $soporte, $descripcion, $prioridad);

    if (!$stmt) {
        header("Location: apps-project-create.php?msg=error_sql");
        exit;
    }

    if ($stmt->execute()) {
        $ticket_id = $conexion->insert_id;


        if (!empty($_FILES['image']['tmp_name'][0])) {
            $upload_dir = 'uploads/tickets/';

            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            for ($i = 0; $i < $archivos_count; $i++) {
                if (is_uploaded_file($_FILES['image']['tmp_name'][$i])) {
                    $archivo_nombre = $_FILES['image']['name'][$i];
                    $archivo_tipo = $_FILES['image']['type'][$i];

                    $extension = pathinfo($archivo_nombre, PATHINFO_EXTENSION);
                    $nombre_unico = uniqid('ticket_' . $ticket_id . '_') . '.' . $extension;
                    $ruta_archivo = $upload_dir . $nombre_unico;

                    if (move_uploaded_file($_FILES['image']['tmp_name'][$i], $ruta_archivo)) {
                        $sql_archivo = "INSERT INTO archivos (ticket_id, nombre_archivo, tipo_archivo, ruta_archivo, fecha_subida) VALUES (?, ?, ?, ?, NOW())";
                        $stmt_archivo = $conexion->prepare($sql_archivo);
                        $stmt_archivo->bind_param('isss', $ticket_id, $archivo_nombre, $archivo_tipo, $ruta_archivo);
                        $stmt_archivo->execute();
                        $stmt_archivo->close();
                    }
                }
            }
        }

        header("Location: apps-project-create.php?msg=ticket_enviado");
        exit;
    } else {
        header("Location: apps-project-create.php?msg=error_enviar");
        exit;
    }
}
$conexion->close();
?>