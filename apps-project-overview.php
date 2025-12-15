<?php
session_start();
include("conexion.php");
include("Mostrarticket.php");
include("menu.php");
?>

<html lang="en" data-bs-theme="light" data-layout="vertical">

<head>

    <meta charset="utf-8" />
    <title>Index | FabKin Admin & Dashboards Template </title>
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
        <div class="container-fluid">
            <div class="d-flex align-items-center mt-2 mb-2">
                <h6 class="mb-0 flex-grow-1">Vista</h6>
                <div class="flex-shrink-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-end mb-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Ticket</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Vista</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-m-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="card-title">Cliente: <?php echo $ticket['cliente'];?></h6>
                        <a href="apps-project-list.php" class="btn btn-outline-dark"><i class="bi bi-arrow-left me-1"></i>Regresar</a>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-4 mb-8">
                            <div class="flex-grow-1">
                                <h6 class="card-title">Asunto:</h6>
                                <?php echo $ticket['asunto']; ?>
                            </div>
                        <div>
                                <span class="badge <?php echo 
                                ($ticket['estatus_ticket'] == 'CERRADO') ? 'bg-secondary text-white' : 
                                (($ticket['estatus_ticket'] == 'EN PROGRESO') ? 'bg-info text-white' : 
                                (($ticket['estatus_ticket'] == 'PENDIENTE') ? 'bg-warning text-dark' : 
                                (($ticket['estatus_ticket'] == 'RESUELTO') ? 'bg-success text-white' : 
                                'bg-warning'))); ?>">
                                    <?php echo strtoupper($ticket['estatus_ticket']);?>
                                </span>
                            </div>
                            <div>
                                <span class="badge <?php echo 
                                ($ticket['Prioridad'] == 'BAJA') ? 'bg-success text-white' : 
                                (($ticket['Prioridad'] == 'MEDIA') ? 'bg-info text-white' : 
                                (($ticket['Prioridad'] == 'ALTA') ? 'bg-warning text-dark' : 
                                (($ticket['Prioridad'] == 'URGENTE') ? 'bg-danger text-white' : 
                                'bg-warning'))); ?>">
                                    <?php echo strtoupper($ticket['Prioridad']);?>
                                </span>
                            </div>
                        </div>
                        <h6>Descripción del problema :</h6>
                        <p><?php echo nl2br($ticket['descripcion']);?></p>
                        <div class="row mt-8">
                            <div class="col-4 rounded bg-info-subtle text-info-emphasis p-3 mb-3">
                                <h6 class="fs-14">
                                    <i class="bi bi-calendar text-primary fs-12 me-1"></i>
                                    Fecha de alta
                                </h6>
                                <p class="text-muted mb-0 fs-12"><?php echo $ticket['fecha_alta']?>
                            </div>
                            <div class="col-4 rounded bg-dark-subtle text-dark-emphasis p-3 mb-3">
                                <h6 class="fs-14">
                                    <i class="bi bi-calendar text-primary fs-12 me-1"></i>
                                    Fecha de solución
                                </h6>
                                <p class="text-muted mb-0 fs-12"><?php if ($ticket['estatus_ticket'] == 'CERRADO' || $ticket['estatus_ticket'] == 'RESUELTO') {
                                    echo isset($ticket['fecha_solucion']) ? date('d/m/Y', strtotime($ticket['fecha_solucion'])) : date('d/m/Y');
                                    } else {
                                    echo 'Pendiente';
                                    }?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-4">
                <div class="card card-h-100">
                    <div class="card-header">
                        <h6 class="card-title">Archivos adjuntos</h6>
                    </div>
                    <div class="card-body">
                        <table class="table align-middle table-hover">
                            <tbody>
                                <?php 
                                $archivos = $conexion->query("SELECT * FROM ARCHIVOS WHERE ticket_id = $id_ticket");
                                if($archivos && $archivos->num_rows>0){
                                    while($archivo = $archivos->fetch_assoc()){
                                        echo "<tr><td><a href='uploads/{$archivo['nombre_archivo']}' target='_blank'>{$archivo['nombre_archivo']}</a></td></tr>"; }
                                } else { echo "<tr><td>No hay archivos adjuntos</td></tr>"; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Begin scroll top -->
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

    <script src="assets/js/app/project-overview.init.js"></script>

    <!-- App js -->
    <script type="module" src="assets/js/app.js"></script>

</body>

</html>