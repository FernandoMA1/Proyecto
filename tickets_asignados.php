<?php
require_once "auth_check.php";
protegerRuta(['Soporte']);
include("Mostrartablatickets.php");
include("conexion.php");
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
                <h6 class="mb-0 flex-grow-1">Tickets asignados</h6>
                <div class="flex-shrink-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-end mb-0">
                            <li class="breadcrumb-item">Panel de control</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tickets asignados</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex">
                            <form method="GET" action="" class="d-flex w-100">
                                <div class="col-md-4 col-xl-3 col-xxl-2 me-2">
                                    <select id="month-choice" name="mes" class="form-select" onchange="this.form.submit()">
                                        <option value="">Todos los meses</option>
                                        <option value="1" <?php echo (isset($_GET['mes']) && $_GET['mes']=='1') ? 'selected' : ''; ?>>Enero</option>
                                        <option value="2" <?php echo (isset($_GET['mes']) && $_GET['mes']=='2') ? 'selected' : ''; ?>>Febrero</option>
                                        <option value="3" <?php echo (isset($_GET['mes']) && $_GET['mes']=='3') ? 'selected' : ''; ?>>Marzo</option>
                                        <option value="4" <?php echo (isset($_GET['mes']) && $_GET['mes']=='4') ? 'selected' : ''; ?>>Abril</option>
                                        <option value="5" <?php echo (isset($_GET['mes']) && $_GET['mes']=='5') ? 'selected' : ''; ?>>Mayo</option>
                                        <option value="6" <?php echo (isset($_GET['mes']) && $_GET['mes']=='6') ? 'selected' : ''; ?>>Junio</option>
                                        <option value="7" <?php echo (isset($_GET['mes']) && $_GET['mes']=='7') ? 'selected' : ''; ?>>Julio</option>
                                        <option value="8" <?php echo (isset($_GET['mes']) && $_GET['mes']=='8') ? 'selected' : ''; ?>>Agosto</option>
                                        <option value="9" <?php echo (isset($_GET['mes']) && $_GET['mes']=='9') ? 'selected' : ''; ?>>Septiembre</option>
                                        <option value="10" <?php echo (isset($_GET['mes']) && $_GET['mes']=='10') ? 'selected' : ''; ?>>Octubre</option>
                                        <option value="11" <?php echo (isset($_GET['mes']) && $_GET['mes']=='11') ? 'selected' : ''; ?>>Noviembre</option>
                                        <option value="12" <?php echo (isset($_GET['mes']) && $_GET['mes']=='12') ? 'selected' : ''; ?>>Diciembre</option>
                                    </select>
                                </div>
                                <div class="col-md-4 col-xl-3 col-xxl-2">
                                    <select id="year-choice" name="anio" class="form-select" onchange="this.form.submit()">
                                        <option value="">Todos los años</option>
                                        <?php $resAnios = $conexion->query("SELECT DISTINCT YEAR(fecha_alta) AS anio FROM TICKETS ORDER BY anio DESC");
                                        if ($resAnios) { while ($row = $resAnios->fetch_assoc()) { $anio = (int)$row['anio']; ?>
                                            <option value="<?php echo $anio; ?>" <?php echo (isset($_GET['anio']) && $_GET['anio']==$anio) ? 'selected' : ''; ?>><?php echo $anio; ?></option>
                                        <?php } } ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-box table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Asunto</th>
                                            <th scope="col">Cliente</th>
                                            <th scope="col">Agente asignado</th>
                                            <th scope="col">Descripción</th>
                                            <th scope="col">Estatus</th>
                                            <th scope="col">Prioridad</th>
                                            <th scope="col">Fecha de alta</th>
                                            <th scope="col">Fecha de solución</th>
                                            <th scope="col">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($resultado->num_rows > 0) {
                                            while ($ticket = $resultado->fetch_assoc()) {
                                                $estatus_class = '';
                                                switch ($ticket['estatus_ticket']) {
                                                    case 'PENDIENTE':
                                                        $estatus_class = 'bg-warning text-dark';
                                                        break;
                                                    case 'EN PROGRESO':
                                                        $estatus_class = 'bg-info text-white';
                                                        break;
                                                    case 'RESUELTO':
                                                        $estatus_class = 'bg-success text-white';
                                                        break;
                                                    case 'CERRADO':
                                                        $estatus_class = 'bg-secondary text-white';
                                                        break;
                                                }
                                                $prioridad_class = '';
                                                switch ($ticket['Prioridad']) {
                                                    case 'BAJA':
                                                        $prioridad_class = 'bg-success text-white';
                                                        break;
                                                    case 'MEDIA':
                                                        $prioridad_class = 'bg-info text-white';
                                                        break;
                                                    case 'ALTA':
                                                        $prioridad_class = 'bg-warning text-dark';
                                                        break;
                                                    case 'URGENTE':
                                                        $prioridad_class = 'bg-danger text-white';
                                                        break;
                                                }
                                        ?>
                                                <tr>
                                                    <td><?php echo $ticket['id_ticket']; ?></td>
                                                    <td><?php echo htmlspecialchars($ticket['asunto']); ?></td>
                                                    <td><?php echo htmlspecialchars($ticket['cliente']); ?></td>
                                                    <td><?php if (!empty($ticket['asignado_nombre']) || !empty($ticket['asignado_apellidos'])) {
                                                                echo htmlspecialchars(trim($ticket['asignado_nombre'] . ' ' . $ticket['asignado_apellidos']));
                                                            } else {
                                                                echo 'Sin asignar';
                                                            }
                                                        ?></td>
                                                    <td><?php echo (substr($ticket['descripcion'], 0, 50)) . (strlen($ticket['descripcion']) > 50 ? '...' : ''); ?></td>
                                                    <td><span class="badge <?php echo $estatus_class; ?>"><?php echo $ticket['estatus_ticket']; ?></span></td>
                                                    <td><span class="badge <?php echo $prioridad_class; ?>"><?php echo $ticket['Prioridad']; ?></span></td>
                                                    <td><?php echo isset($ticket['fecha_alta']) ? date('d/m/Y', strtotime($ticket['fecha_alta'])) : 'N/A'; ?></td>
                                                    <td><?php echo isset($ticket['fecha_solucion']) ? date('d/m/Y', strtotime($ticket['fecha_solucion'])) : 'Pendiente'; ?></td>
                                                    <td>
                                                        <a href="ver_ticket.php?id=<?php echo $ticket['id_ticket']; ?>" class="btn btn-light-secondary">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="Eliminarticket.php?id=<?php echo $ticket['id_ticket'];?>" class="btn btn-light-danger eliminar-ticket">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="10" class="text-center">No se encontraron tickets</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-between align-items-center p-3">
                                    Mostrando <?php echo min($total_registros, $registros_por_pagina); ?> de <?php echo $total_registros; ?> resultados
                                </div>
                                <?php
                                $min_registros_paginacion = 10;
                                if ($total_registros > $min_registros_paginacion):
                                ?>
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination mb-0">
                                            <?php if ($pagina_actual > 1): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?pagina=<?php echo ($pagina_actual - 1); ?>" aria-label="Previous">
                                                        <span aria-hidden="true">&laquo;</span>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                            <?php
                                            $rango = 2;
                                            $inicio_rango = max(1, $pagina_actual - $rango);
                                            $fin_rango = min($total_paginas, $pagina_actual + $rango);
                                            if ($inicio_rango > 1) {
                                                echo '<li class="page-item"><a class="page-link" href="?pagina=1">1</a></li>';
                                                if ($inicio_rango > 2) {
                                                    echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                                                }
                                            }
                                            for ($i = $inicio_rango; $i <= $fin_rango; $i++) {
                                                echo '<li class="page-item ' . ($i == $pagina_actual ? 'active' : '') . '">
                                                <a class="page-link" href="?pagina=' . $i . '">' . $i . '</a>
                                            </li>';
                                            }
                                            if ($fin_rango < $total_paginas) {
                                                if ($fin_rango < $total_paginas - 1) {
                                                    echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                                                }
                                                echo '<li class="page-item"><a class="page-link" href="?pagina=' . $total_paginas . '">' . $total_paginas . '</a></li>';
                                            }
                                            ?>
                                            <?php if ($pagina_actual < $total_paginas): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?pagina=<?php echo ($pagina_actual + 1); ?>" aria-label="Next">
                                                        <span aria-hidden="true">&raquo;</span>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main> <!-- Begin scroll top -->
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
    <!-- App js -->
    <script type="module" src="assets/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.querySelectorAll(".eliminar-ticket").forEach(function(link){
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
    </script>
    <script src="assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>

    <script src="assets/js/app/project-list.init.js"></script>

</body>

</html>