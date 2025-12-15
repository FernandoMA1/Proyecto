<?php
session_start();
include("conexion.php");
include("enviarticket.php");
include("menu.php");
?>

<html lang="en" data-bs-theme="light" data-layout="vertical">

<head>

    <meta charset="utf-8" />
    <title>Index | FabKin Admin & Dashboards Template </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta content="Admin & Dashboards Template" name="description" />
    <meta content="Pixeleyez" name="author" />


    <script type="module" src="assets/js/layout-setup.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

    <link rel="shortcut icon" href="assets/images/k_favicon_32x.png">
    <link rel="stylesheet" href="assets/libs/choices.js/public/assets/styles/choices.min.css">

    <link rel="stylesheet" href="assets/libs/quill/quill.snow.css">

    <link rel="stylesheet" href="assets/libs/dropzone/dropzone.css">

    <link rel="stylesheet" href="assets/libs/air-datepicker/air-datepicker.css">

    <link rel="stylesheet" href="assets/libs/simplebar/simplebar.min.css">

    <link href="assets/libs/swiper/swiper-bundle.min.css" rel="stylesheet">

    <link href="assets/libs/nouislider/nouislider.min.css" rel="stylesheet">

    <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css">

    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css">

    <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css">

</head>

<body>
    <div class="sidebar-backdrop" id="sidebar-backdrop"></div>
    <main class="app-wrapper d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="container" style="max-width: 1100px;">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Crear ticket</h5>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="row g-5">
                                        <div class="col-12">
                                            <label for="asunto" class="form-label">Asunto</label>
                                            <input type="text" class="form-control" id="asunto" name="asunto" placeholder="Ingresa tu asunto" required>
                                        </div>
                                        <div class="col-xl-12">
                                            <label for="cliente" class="form-label">Cliente</label>
                                            <?php if($_SESSION['rol'] === 'Administrador'): ?>
                                                <select class="form-select" id="cliente" name="cliente" required>
                                                    <option value="">Seleccionar cliente</option>
                                                    <?php 
                                                    include("conexion.php");
                                                    $sql = "SELECT * FROM CLIENTES WHERE estatus_cliente = 'Disponible'";
                                                    $resultado = mysqli_query($conexion, $sql);
                                                    while($consulta = mysqli_fetch_array($resultado)){
                                                        echo '<option value="'. $consulta['contacto'] . '">'. $consulta['contacto'] . ' ('.$consulta['Razon_social'] . ') '.'</option>';
                                                    }
                                                    ?>
                                                </select>
                                            <?php endif; ?>
                                            <?php if(isset($_SESSION['rol']) && ($_SESSION['rol'] === 'Soporte' || $_SESSION['rol'] === 'Cliente')):?>
                                            <input type="text" class="form-control" id="cliente" name="cliente" value="<?php echo htmlspecialchars(isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Invitado'); ?>" readonly>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-12">
                                            <label for="descripcion" class="form-label">Descripción</label>
                                            <textarea id="descripcion" name="descripcion" class="form-control d-none"></textarea>
                                            <div id="snowEditor"></div>
                                        </div>
                                        <div class="col-xl-6">
                                            <label for="prioridad" class="form-label">Prioridad</label>
                                            <select class="form-select" id="prioridad" name="prioridad" required>
                                                <option value="">Seleccionar prioridad</option>
                                                <option value="Baja">Baja</option>
                                                <option value="Media">Media</option>
                                                <option value="Alta">Alta</option>
                                                <option value="Urgente">Urgente</option>
                                            </select>
                                        </div>
                                        <?php
                                        if($_SESSION['rol'] === 'Administrador'): ?>
                                        <div class="col-xl-6">
                                            <label for="asignar" class="form-label">Asignar a:</label>
                                            <select class="form-select" id="asignar" name="asignar" required>
                                                <option value="">Seleccionar agente</option>
                                                <?php 
                                                include("conexion.php");
                                        
                                                $sql = "SELECT * FROM USUARIOS WHERE estatus_Usuario = 'Disponible' AND rol = 'Soporte'";
                                                $resultado = mysqli_query($conexion, $sql);
                                                while($consulta = mysqli_fetch_array($resultado)){
                                                    echo '<option value="'. $consulta['id_usuario'] . '">'. $consulta['nombre'] . ' '.$consulta['apellidos'] .'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <?php else: ?>
                                        <input type="hidden" name="asignar" value="">
                                        <?php endif;?>
                                        <div class="col-12 col-xl-6">
                                            <div class="card card-h-100">
                                                <h4> Adjuntar archivos</h4>
                                                <div class="form-group">
                                                    <label class="col-sm-10 control-label">Solo imagenes (1 mb) y videos (10 mb) como máximo. Limite de 3 archivos.</label>
                                                    <div>
                                                        <input type="file" id="image" class="form-control me-2" style="max-width:400px;" name="image[]" multiple>
                                                        <small class="file-error text-danger mt-2 d-block">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-9 d-flex justify-content-end gap-5">
                                            <button type="reset" class="btn btn-light-primary">Cancelar</button>
                                            <button type="submit" class="btn btn-primary" id="btnEnviarTicket">
                                                <span class="spinner-border spinner-border-sm me-2 d-none" id="spinnerTicket"></span><i class="bi bi-plus-lg me-1"></i>Crear Ticket
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
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
    <!-- Selector Js -->
    <script src="assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>
    <!-- Editor Js -->
    <script src="assets/libs/quill/quill.js"></script>
    <!-- Datepicker Js -->
    <script src="assets/libs/air-datepicker/air-datepicker.js"></script>
    <!-- File Upload Js -->
    <script src="assets/libs/dropzone/dropzone-min.js"></script>
    <!-- File Js -->
    <script src="assets/js/app/project-create.init.js"></script>
    <!-- App js -->
    <script type="module" src="assets/js/app.js"></script>
    <script src="assets/js/form/file-upload.init.js"></script>e
    <script>
    var quill = new Quill('#snowEditor', {
        theme: 'snow'
    });

    document.querySelector("form").addEventListener("submit", function() {

        // OBTENER SIEMPRE HTML COMPLETO
        let contenido = quill.root.innerHTML;

        // Opcional: sanitizar script tags
        contenido = contenido.replace(/<script.*?>.*?<\/script>/gi, "");

        document.querySelector("#descripcion").value = contenido;
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const params = new URLSearchParams(window.location.search);
            const msg = params.get('msg');
            if (msg === 'ticket_enviado') {
                Swal.fire({
                    icon: 'success',
                    title: 'Ticket enviado',
                    text: 'Tu ticket se ha enviado correctamente.',
                    timer: 2500,
                    showConfirmButton: false
                });
            } else if (msg === 'error_sql' || msg === 'error_enviar') {
                Swal.fire({ icon: 'error', title: 'Error al enviar', text: 'Intenta nuevamente, verifica los campos obligatorios.' });
            } else if (msg === 'max_archivos') {
                Swal.fire({ icon: 'error', title: 'Error al enviar', text: 'Maximo 3 archivos' });
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function (){
            const form = document.querySelector('form');
            const btnEnviar = document.getElementById('btnEnviarTicket');
            const spinner = document.getElementById('spinnerTicket');

            form.addEventListener('submit', function (){
                spinner.classList.remove('d-none');
                btnEnviar.setAttribute('disabled', 'disabled');
                btnEnviar.classList.add('disabled');
                btnEnviar.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2"></span>
                Enviando ticket....`;
            });
        });
    </script>

</body>

</html>