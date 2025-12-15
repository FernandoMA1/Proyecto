<?php
session_start();
include("conexion.php");
Include("Mostrarticket.php");
include("menu.php");
?>

<html lang="en" data-bs-theme="light" data-layout="vertical">

<head>

    <meta charset="utf-8" />
    <title>Plataforma de tickets </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta content="Admin & Dashboards Template" name="description" />
    <meta content="Pixeleyez" name="author" />

    <!-- layout setup -->
    <script type="module" src="assets/js/layout-setup.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- RemixIcon CDN -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/k_favicon_32x.png"> <!-- Selector Css -->
    <link rel="stylesheet" href="assets/libs/choices.js/public/assets/styles/choices.min.css">
    <!-- Editor  Css-->
    <link rel="stylesheet" href="assets/libs/quill/quill.snow.css">
    <!-- File Upload Css -->
    <link rel="stylesheet" href="assets/libs/dropzone/dropzone.css">
    <!-- Picker CSS -->
    <link rel="stylesheet" href="assets/libs/air-datepicker/air-datepicker.css">
    <!-- Simplebar Css -->
    <link rel="stylesheet" href="assets/libs/simplebar/simplebar.min.css">
    <!-- Swiper Css -->
    <link href="assets/libs/swiper/swiper-bundle.min.css" rel="stylesheet">
    <!-- Nouislider Css -->
    <link href="assets/libs/nouislider/nouislider.min.css" rel="stylesheet">
    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css">
    <!--icons css-->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css">
    <!-- App Css-->
    <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css">

</head>

<body>
    <div class="sidebar-backdrop" id="sidebar-backdrop"></div>
        <main class="app-wrapper">
            <div class="container-fluid py-3">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card mb-3">
                            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center" style="border-radius: 12px 12px 0 0;">
                                    <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador'){ ?>
                                        <a href="apps-project-list.php" class="btn btn-dark btn-sm">
                                            <i class="ri-arrow-left-s-line"></i> Tickets
                                        </a>
                                    <?php } elseif(isset($_SESSION['rol']) && $_SESSION['rol'] === 'Soporte'){ ?>
                                        <a href="tickets_asignados.php" class="btn btn-dark btn-sm">
                                            <i class="ri-arrow-left-s-line"></i> Tickets
                                        </a>
                                    <?php } else { ?>
                                        <a href="mistickets.php" class="btn btn-dark btn-sm">
                                            <i class="ri-arrow-left-s-line"></i> Tickets
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="h4 fs-3 editable" data-field="asunto"><?= $ticket['asunto'] ?></p>
                                <p class="h6 fs-6 text-muted">
                                    <?php
                                    if ($ticket['estatus_ticket'] == 'CERRADO' || $ticket['estatus_ticket'] == 'RESUELTO') {
                                        echo 'Resuelto el: ' . (
                                            isset($ticket['fecha_solucion'])
                                                ? date('d/m/Y', strtotime($ticket['fecha_solucion']))
                                                : date('d/m/Y')
                                        );
                                    } else {
                                        date_default_timezone_set('America/Mexico_City');
                                        $fecha_creacion = new DateTime($ticket['fecha_alta']);
                                        $ahora = new DateTime();
                                        $diff = $fecha_creacion->diff($ahora);
                                        $texto = "Pendiente hace ";
                                        if ($diff->d > 0) {
                                            $texto .= $diff->d . " día(s)";
                                            if ($diff->h > 0) $texto .= " y " . $diff->h . " hora(s)";
                                        } elseif ($diff->h > 0) {
                                            $texto .= $diff->h . " hora(s)";
                                            if ($diff->i > 0) $texto .= " y " . $diff->i . " minuto(s)";
                                        } elseif ($diff->i > 0) {
                                            $texto .= $diff->i . " minuto(s)";
                                        } else {
                                            $texto .= "unos segundos";
                                        }
                                        echo $texto;
                                    }
                                    ?>
                                </p>
                                <p class="h6 fs-6 text-muted">
                                    Estado: 
                                    <span class="badge 
                                        <?php echo 
                                            ($ticket['estatus_ticket'] == 'CERRADO') ? 'bg-secondary text-white' : 
                                            (($ticket['estatus_ticket'] == 'EN PROGRESO') ? 'bg-info text-white' : 
                                            (($ticket['estatus_ticket'] == 'PENDIENTE') ? 'bg-warning text-dark' : 
                                            (($ticket['estatus_ticket'] == 'RESUELTO') ? 'bg-success text-white' : 
                                            'bg-warning'))); ?>
                                    ">
                                        <?= strtoupper($ticket['estatus_ticket']); ?>
                                    </span>
                                </p>
                                <?php if(strtoupper($ticket['estatus_ticket']) === 'PENDIENTE'): ?>
                                    <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#Editar">
                                        <i class="ri-edit-2-line"></i> Editar
                                    </button>
                                <?php endif; ?>
                                <a href="Eliminarticket.php?id=<?= $ticket['id_ticket']; ?>" class="btn btn-sm btn-dark eliminar-ticket">
                                    <i class="ri-delete-bin-2-line"></i> Eliminar
                                </a>
                                <?php if(isset($_SESSION['rol']) && ($_SESSION['rol'] === 'Administrador' || $_SESSION['rol'] === 'Soporte') && strtoupper($ticket['estatus_ticket']) === 'RESUELTO'): ?>
                                    <a href="Cerrar_ticket.php?id=<?= $ticket['id_ticket']; ?>" class="btn btn-sm btn-dark cerrar-ticket">
                                        <i class="ri-lock-2-line"></i> Cerrar ticket
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- ======= DETALLES DEL TICKET ======= -->
                        <div class="card mb-3">
                            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                                <div class="card-header bg-light d-flex align-items-center" style="border-radius: 12px 12px 0 0;">
                                    <h6 class="mb-0">Detalles del ticket</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong class="h6 fs-6">Descripción del ticket <br></strong>
                                        <div style="border: 1px solid #ccc; padding: 10px; border-radius: 6px; background: #f8f9fa; display: inline-block; max-width: 100%; white-space: pre-wrap;"><?= $ticket['descripcion']; ?></div>
                                    </p>
                                    <p><strong class="h6 fs-6">Agente asignado </strong><br><?php if (!empty($ticket['asignado_nombre']) || !empty($ticket['asignado_apellidos'])) {
                                                                echo htmlspecialchars(trim($ticket['asignado_nombre'] . ' ' . $ticket['asignado_apellidos']));
                                                            } else {
                                                                echo 'Sin asignar';
                                                            }
                                                        ?></p>
                                    <p><strong class="h6 fs-6">Fecha de creación</strong><br>
                                        <i class="ri-calendar-2-line"></i>
                                        <?= date('d/m/Y', strtotime($ticket['fecha_alta'])); ?>
                                    </p>
                                    <p><strong class="h6 fs-6">Prioridad</strong><br>
                                        <span class="badge 
                                            <?php echo 
                                                ($ticket['Prioridad'] == 'BAJA') ? 'bg-success text-white' : 
                                                (($ticket['Prioridad'] == 'MEDIA') ? 'bg-info text-white' : 
                                                (($ticket['Prioridad'] == 'ALTA') ? 'bg-warning text-dark' : 
                                                (($ticket['Prioridad'] == 'URGENTE') ? 'bg-danger text-white' : 
                                                'bg-warning'))); ?>
                                        ">
                                            <?= strtoupper($ticket['Prioridad']); ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0" style="border-radius: 12px;">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center" style="border-radius: 12px 12px 0 0;">
                                <h4 class="mb-0">
                                    <i class="ri-message-3-line me-1"></i> CHAT : <?= $ticket['asunto']; ?>
                                </h4>
                                <?php if(isset($_SESSION['rol']) && ($_SESSION['rol'] === 'Administrador' || $_SESSION['rol'] === 'Soporte')): ?>
                                    <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#Marcar">
                                        <i class="ri-file-marked-line"></i> Marcar como
                                    </button>
                                <?php endif; ?>
                            </div>
                            <div class="card-body" style="height:620px; overflow-y:auto;" id="mensajesChat"></div>
                            <div class="card-footer d-flex">
                                <input type="file" id="archivoAdjunto" class="form-control me-2" style="max-width:200px;">
                                <input type="text" id="mensaje" class="form-control me-2" placeholder="Escribe un mensaje...">
                                <button id="enviarMensaje" class="btn btn-light-info">Enviar</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 mb-3" style="border-radius: 12px;">
                            <div class="card-header bg-light d-flex align-items-center" style="border-radius: 12px 12px 0 0;">
                                <h6 class="mb-0">
                                    <i class="ri-user-3-line me-1"></i> Información del Cliente
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <small class="text-muted d-block">Contacto</small>
                                    <p class="h6 mb-0">
                                        <?= !empty($cliente['contacto']) ? $cliente['contacto'] : 'No especificado'; ?>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block">Razón Social</small>
                                    <p class="h6 mb-0">
                                        <?= !empty($cliente['razon_social']) ? $cliente['razon_social'] : 'No especificada'; ?>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block">Teléfono</small>
                                    <p class="h6 mb-0">
                                        <i class="ri-phone-line"></i>
                                        <?= !empty($cliente['telefono']) ? $cliente['telefono'] : 'Sin teléfono'; ?>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block">Correo</small>
                                    <p class="h6 mb-0">
                                        <i class="ri-mail-line"></i>
                                        <?= !empty($cliente['email']) ? $cliente['email'] : 'Sin correo'; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- ARCHIVOS ADJUNTOS (DERECHA) -->
                        <div class="card shadow-sm border-0" style="border-radius: 12px;">
                            <div class="card-header bg-light d-flex align-items-center" style="border-radius: 12px 12px 0 0;">
                                <h6 class="mb-0">
                                    <i class="ri-attachment-line me-1"></i> Archivos adjuntos
                                </h6>
                            </div>
                            <div class="card-body" style="max-height: 260px; overflow-y: auto;">
                                <ul class="list-group list-group-flush">
                                    <?php
                                    $archivosDer = $conexion->query("SELECT * FROM ARCHIVOS WHERE ticket_id = $id_ticket");
                                    if ($archivosDer && $archivosDer->num_rows > 0) {
                                        while ($archivo = $archivosDer->fetch_assoc()) {
                                            ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <a href="<?= $archivo['ruta_archivo'] ?>" target="_blank">
                                                    <i class="bi bi-paperclip"></i> <?= $archivo['nombre_archivo'] ?>
                                                </a>
                                                <form action="delete_archivo.php" method="POST" class="form-eliminar-archivo m-0 p-0">
                                                    <input type="hidden" name="id" value="<?= $archivo['id'] ?>">
                                                    <input type="hidden" name="id_ticket" value="<?= $id_ticket ?>">
                                                    <button type="button" class="btn btn-sm btn-light eliminar-archivo">
                                                        <i class="ri-close-line"></i>
                                                    </button>
                                                </form>
                                            </li>
                                            <?php
                                        }
                                    } else {
                                        echo "<li class='list-group-item text-muted'>No hay archivos adjuntos</li>";
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="Marcar" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Marcar" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Marcar ticket como:</h5>
                            <button type="button" class="btn-close icon-btn-sm" data-bs-dismiss="modal">
                                <i class="ri-close-large-line fw-semibold"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="Actualizarestado.php?id=<?= $ticket['id_ticket']; ?>">
                                <label for="estatus_ticket" class="form-label">Estado</label>
                                <select class="form-select" id="estatus_ticket" name="estatus_ticket">
                                    <option value="RESUELTO" <?= $ticket['estatus_ticket'] === 'RESUELTO' ? 'selected' : '' ?>>RESUELTO</option>
                                    <option value="PENDIENTE" <?= $ticket['estatus_ticket'] === 'PENDIENTE' ? 'selected' : '' ?>>PENDIENTE</option>
                                    <option value="EN PROGRESO" <?= $ticket['estatus_ticket'] === 'EN PROGRESO' ? 'selected' : '' ?>>EN PROGRESO</option>
                                </select>
                                <div class="mt-3 d-flex justify-content-end gap-3">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary" id="btnMarcar">
                                        <span class="spinner-border spinner-border-sm me-2 d-none" id="spinnermarcar"></span>Confirmar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="Editar" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Editar" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar ticket</h5>
                            <button type="button" class="btn-close icon-btn-sm" data-bs-dismiss="modal">
                                <i class="ri-close-large-line fw-semibold"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" enctype="multipart/form-data" action="Editarticket.php?id=<?= $ticket['id_ticket']; ?>">
                                <label class="form-label">Asunto:</label>
                                <input type="text" class="form-control" name="asunto" value="<?= $ticket['asunto']; ?>">
                                <label class="form-label mt-3">Descripción:</label>
                                <textarea id="descripcion" name="descripcion" class="d-none"></textarea>
                                <div id="editorEditar" style="height: 200px;"></div>
                                <label class="form-label mt-3">Prioridad</label>
                                <select class="form-select" name="prioridad" required>
                                    <option value="Baja" <?= $ticket['Prioridad'] === 'BAJA' ? 'selected' : '' ?>>Baja</option>
                                    <option value="Media" <?= $ticket['Prioridad'] === 'MEDIA' ? 'selected' : '' ?>>Media</option>
                                    <option value="Alta" <?= $ticket['Prioridad'] === 'ALTA' ? 'selected' : '' ?>>Alta</option>
                                    <option value="Urgente" <?= $ticket['Prioridad'] === 'URGENTE' ? 'selected' : '' ?>>Urgente</option>
                                </select>
                                <label class="form-label mt-3">Añadir nuevo archivo</label>
                                <input type="file" class="form-control" name="image[]" multiple>
                                <small class="file-error text-danger mt-2 d-block"></small>
                                <div class="mt-3 d-flex justify-content-end gap-3">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" name="actualizar" class="btn btn-primary" id="btnEditarT">
                                        <span class="spinner-border spinner-border-sm me-2 d-none" id="spinnereditar"></span>Actualizar ticket
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>

    <!--         Begin scroll top -->
    <div class="progress-wrap d-flex align-items-center justify-content-center cursor-pointer h-40px w-40px position-fixed" id="progress-scroll">
        <svg class="progress-circle w-100 h-100 position-absolute" viewBox="0 0 100 100">
            <circle cx="50" cy="50" r="45" class="progress" />
        </svg>
        <i class="ri-arrow-up-line fs-16 z-1 position-relative text-primary"></i>
    </div>
    <!-- END scroll top -->

    <!-- Begin Footer -->
    <?php include_once("app/layout/footer.php");?>
    <!-- END Footer -->
    </div>
    <!-- END page -->

    <!-- JAVASCRIPT -->
    <script src="assets/libs/swiper/swiper-bundle.min.js"></script>
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/js/scroll-top.init.js"></script>
    <script src="assets/libs/apexcharts/apexcharts.min.js"></script>
    <script src="assets/libs/quill/quill.js"></script>
    <script src="assets/js/app/project-overview.init.js"></script>
<!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- App js -->
    <script type="module" src="assets/js/app.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector('#Editar form');
        const btnEnviar = document.getElementById('btnEditarT');
        const spinner = document.getElementById('spinnereditar');

        form.addEventListener('submit', function () {
            spinner.classList.remove('d-none');
            btnEnviar.setAttribute('disabled', 'disabled');
            btnEnviar.classList.add('disabled');
            btnEnviar.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2"></span>
                Actualizando ticket...
            `;
        });
    });
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector('#Marcar form');
        const btnEnviar = document.getElementById('btnMarcar');
        const spinner = document.getElementById('spinnermarcar');

        form.addEventListener('submit', function () {
            spinner.classList.remove('d-none');
            btnEnviar.setAttribute('disabled', 'disabled');
            btnEnviar.classList.add('disabled');
            btnEnviar.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2"></span>
                Guardando estado...
            `;
        });
    });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var quillEditar = new Quill('#editorEditar', {
                theme: 'snow'
            });
            quillEditar.root.innerHTML = <?= json_encode($ticket['descripcion']); ?>;
            document.querySelector("#Editar form").addEventListener("submit", function () {
                let contenidoHTML = quillEditar.root.innerHTML;
                contenidoHTML = contenidoHTML.replace(/<script.*?>.*?<\/script>/gi, "");
                document.querySelector("#descripcion").value = contenidoHTML;
            });
            var editarModal = document.getElementById('Editar');
            editarModal.addEventListener('shown.bs.modal', function () {
                quillEditar.focus();
            });
        });
    </script>
    <script>
    document.querySelectorAll('.eliminar-ticket').forEach(function(link){
        link.addEventListener("click", function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            const href = this.getAttribute("href");
            Swal.fire({
                title: "¿Estás seguro?",
                text: "Esta acción no se puede deshacer.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Eliminado",
                        text: "El ticket ha sido eliminado.",
                        icon: "success",
                        confirmButtonText: "Aceptar"
                    }).then(() => {
                        window.location.href = href;
                    });
                }
            });
        });
    });
    
    document.querySelectorAll('.cerrar-ticket').forEach(function(link){
        link.addEventListener("click", function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            const href = this.getAttribute("href");
            Swal.fire({
                title: "¿Estás seguro?",
                text: "Esta acción marcará el ticket como cerrado y no se podrá enviar más mensajes.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Sí, cerrar ticket",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Se ha cerrado el ticket",
                        text: "El ticket se ha marcado como cerrado.",
                        icon: "success",
                        confirmButtonText: "Aceptar"
                    }).then(() => {
                        window.location.href = href;
                    });
                }
            });
        });
    });
    </script>
    <script>
    document.addEventListener('click', function(e) {
        if (e.target.closest('.eliminar-archivo')) {
            let btn = e.target.closest('.eliminar-archivo');
            let form = btn.closest('form');
            Swal.fire({
                title: "¿Eliminar archivo?",
                text: "Esta acción no se puede deshacer",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Se ha eliminado el archivo",
                        text: "El archivo ha sido eliminado correctamente.",
                        icon: "success",
                        confirmButtonText: "Aceptar"
                    }).then(() => {
                        form.submit();
                    });

                }

            });
        }
    });
    </script>


    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const ticketId = <?php echo isset($_GET['id']) ? intval($_GET['id']) : 0; ?>;
        const mensajesChat = document.getElementById('mensajesChat');
        const inputMensaje = document.getElementById('mensaje');
        const btnEnviar = document.getElementById('enviarMensaje');
        const inputArchivo = document.getElementById('archivoAdjunto');
        const params = new URLSearchParams(window.location.search);
        const msg = params.get('msg');
        
        let estadoTicket = '<?php echo strtoupper($ticket['estatus_ticket']); ?>';
        
        // Verificar estado del ticket al cargar
        verificarEstadoTicket();
        
        document.querySelectorAll(".cerrar-ticket").forEach(function(link){
            link.addEventListener("click", function(e){
                e.preventDefault();
                const href = this.getAttribute("href");
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "Esta acción no se puede deshacer.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                });
            });
        });

        if (msg === 'ticket_editado') {
            Swal.fire({
                icon: 'success',
                title: 'Tu ticket se ha actualizado',
                text: 'Tus cambios se han guardado correctamente.',
                timer: 2500,
                showConfirmButton: false
            });
        
        } else if (msg === 'error_sql' || msg === 'error_guardar') {
        Swal.fire({ icon: 'error', title: 'Error al guardar', text: 'Intenta nuevamente o contacta soporte.' });
        }

        let ultimaActualizacion = 0;
        
        cargarMensajes();
        
        setInterval(cargarMensajes, 2000);
        
        btnEnviar.addEventListener('click', enviarMensaje);

        inputMensaje.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                enviarMensaje();
            }
        });
        
        function verificarEstadoTicket() {
            if (estadoTicket === 'CERRADO') {
                btnEnviar.disabled = true;
                inputMensaje.disabled = true;
                inputArchivo.disabled = true;
                
                Swal.fire({
                    icon: 'info',
                    title: 'Ticket Cerrado',
                    text: 'Este ticket está cerrado. Solo puedes ver la conversación.',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                });
            }
        }
        
        function cargarMensajes() {
            if (ticketId <= 0) return;
            
            fetch(`cargar_mensajes.php?id_ticket=${ticketId}&ultima=${ultimaActualizacion}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mensajesChat.innerHTML = data.html;
                        
                        // Mostrar anuncio si existe
                        if (data.anuncio) {
                            mensajesChat.insertAdjacentHTML('afterbegin', data.anuncio);
                        }
                        
                        mensajesChat.scrollTop = mensajesChat.scrollHeight;
                        ultimaActualizacion = Date.now();
                        
                        // Actualizar estado si cambió
                        if (data.estado) {
                            estadoTicket = data.estado;
                            verificarEstadoTicket();
                        }
                    } else {
                        console.error('Error al cargar mensajes:', data.error);
                    }
                })
                .catch(error => {
                    console.error('Error en la petición:', error);
                });
        }
        
                function enviarMensaje() {
            const mensaje = inputMensaje.value.trim();
            const archivo = inputArchivo.files[0];
            
            if (!mensaje && !archivo) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: 'Debes escribir un mensaje o seleccionar un archivo'
                });
                return;
            }
            
            if (ticketId <= 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'ID de ticket inválido'
                });
                return;
            }
            
            const formData = new FormData();
            formData.append('id_ticket', ticketId);
            formData.append('mensaje', mensaje);
            
            if (archivo) {
                const tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'video/mp4', 'video/webm', 'video/ogg'];
                if (!tiposPermitidos.includes(archivo.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Archivo no válido',
                        text: 'Solo se permiten imágenes (JPEG, PNG, GIF, WebP) y videos (MP4, WebM, OGG)'
                    });
                    return;
                }
                
                if (archivo.size > 10 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Archivo muy grande',
                        text: 'El archivo no puede ser mayor a 10MB'
                    });
                    return;
                }
                
                formData.append('archivo', archivo);
            }
            
            btnEnviar.disabled = true;
            btnEnviar.textContent = 'Enviando...';
            
            fetch('enviar_mensaje.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.bloqueado === true) {
                    estadoTicket = 'CERRADO';
                    verificarEstadoTicket();
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Ticket Cerrado',
                        text: data.error || 'No se pueden enviar mensajes en un ticket cerrado'
                    });
                    return;
                }
                
                if (data.success) {
                    inputMensaje.value = '';
                    inputArchivo.value = '';
                    
                    if (data.estado) {
                        estadoTicket = data.estado;
                    }
                    
                    // Recargar mensajes (ya incluye el anuncio del sistema)
                    cargarMensajes();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Mensaje enviado',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error || 'Error al enviar el mensaje'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo enviar el mensaje. Verifica tu conexión.'
                });
            })
            .finally(() => {
                btnEnviar.disabled = false;
                btnEnviar.textContent = 'Enviar';
            });
        }
        
        inputArchivo.addEventListener('change', function() {
            const archivo = this.files[0];
            if (archivo) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    console.log('Archivo seleccionado:', archivo.name);
                };
                reader.readAsDataURL(archivo);
            }
        });
    });
    </script>

</body>

</html>