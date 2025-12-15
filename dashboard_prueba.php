<?php
session_start();
include("conexion.php");
include("menu.php");
include("Graficas.php");
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-layout="vertical">

<head>
    <script type="module" src="assets/js/layout-setup.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="shortcut icon" href="assets/images/k_favicon_32x.png">
    <link rel="stylesheet" href="assets/libs/choices.js/public/assets/styles/choices.min.css">
    <link rel="stylesheet" href="assets/libs/simplebar/simplebar.min.css">
    <link href="assets/libs/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="assets/libs/nouislider/nouislider.min.css" rel="stylesheet">
    <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css">
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="sidebar-backdrop" id="sidebar-backdrop"></div>
    <main class="app-wrapper">
        <div class="container-fluid">
            <div class="d-flex align-items-center mt-2 mb-4">
                <h6 class="mb-0 flex-grow-1">
                    <?php 
                        $dias = ["Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado"];
                        $meses_nombres = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
                        echo $dias[date('w')]." ".date('d')." de ".$meses_nombres[date('n')-1]. " del ".date('Y');
                    ?>
                </h6>
            </div>
            
            <!-- Bienvenida -->
            <div class="row">
                <div class="col-12">
                    <div class="card card-h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-start">
                                <h4 class="fw-semibold mb-2">Bienvenido, <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Cliente') { echo htmlspecialchars($_SESSION['nombre']); }else{echo htmlspecialchars($_SESSION['nombre_completo']);} ?></h4>
                                <p class="text-muted mb-0">
                                    <?php 
                                        if ($_SESSION['rol'] === 'Administrador') {
                                            echo "Control general del sistema";
                                        } elseif ($_SESSION['rol'] === 'Soporte') {
                                            echo "Gestionar los tickets asignados y pendientes.";
                                        } else {
                                            echo "Consulta el estado de tus tickets.
                                                ¿Tienes problemas con alguno de nuestros sistemas?, crea un nuevo ticket y te solucionaremos cuanto antes.";
                                        }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php 
            if ($_SESSION['rol'] === 'Administrador') {
                $total_clientes = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS total FROM clientes"))['total'];
                $total_usuarios = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS total FROM usuarios WHERE estatus_Usuario = 'Disponible'"))['total'];
                $tickets_abiertos = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS total FROM tickets WHERE estatus_ticket IN ('PENDIENTE')"))['total'];
                $tickets_resueltos = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS total FROM tickets WHERE estatus_ticket IN ('RESUELTO', 'CERRADO')"))['total'];
                $tickets_proceso = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS total FROM tickets WHERE estatus_ticket IN ('EN PROGRESO')"))['total'];
                $tickets_noasignados = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS total FROM tickets WHERE asignado IS NULL"))['total'];
            } elseif ($_SESSION['rol'] === 'Soporte') {
                $correo_soporte = $_SESSION['email'];
                $id_soporte = $_SESSION['id_usuario'];
                $tickets_asignados = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS total FROM tickets WHERE asignado='$id_soporte'"))['total'];
                $tickets_resueltos = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS total FROM tickets WHERE asignado='$id_soporte' AND estatus_ticket='RESUELTO' OR estatus_ticket='CERRADO'"))['total'];
                $tickets_pendientes = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS total FROM tickets WHERE asignado='$id_soporte' AND estatus_ticket='PENDIENTE'"))['total'];
                $tickets_iniciados = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS total FROM tickets WHERE asignado='$id_soporte' AND estatus_ticket='EN PROGRESO'"))['total'];
            } else {
                $correo_cliente = $_SESSION['email'];
                $contacto_cliente = $_SESSION['nombre'];
                $tickets_activos = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS total FROM tickets WHERE cliente='$contacto_cliente' AND estatus_ticket NOT IN ('RESUELTO','CERRADO')"))['total'];
                $tickets_cerrados = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) AS total FROM tickets WHERE cliente='$contacto_cliente' AND estatus_ticket IN ('RESUELTO','CERRADO')"))['total'];
            }
            ?>

            <!-- Dashboard ADMINISTRADOR -->
            <?php if($_SESSION['rol'] === 'Administrador'): ?>
            <div class="row mt-4">
                <div class="col-md-2">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <i class="ri-building-line fs-2 text-primary mb-2"></i>
                            <h5>Clientes Registrados</h5>
                            <p class="fs-4 fw-bold"><?php echo $total_clientes; ?></p>
                            <a href="apps-crm-contact.php" class="btn btn-light-secondary btn-sm mt-2">Administrar clientes</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <i class="ri-user-3-line fs-2 text-success mb-2"></i>
                            <h5>Usuarios activos</h5>
                            <p class="fs-4 fw-bold"><?php echo $total_usuarios; ?></p>
                            <a href="listadeusuarios.php" class="btn btn-light-secondary btn-sm mt-2">Administrar usuarios</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <i class="ri-file-user-line fs-2 text-secondary mb-2"></i>
                            <h5>Tickets por asignar</h5>
                            <p class="fs-4 fw-bold"><?php echo $tickets_noasignados;?></p>
                            <a href="apps-project-list.php" class="btn btn-light-danger btn-sm mt-2">Ir a asignar</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <i class="ri-time-line fs-2 text-warning mb-2"></i>
                            <h5>Tickets Activos</h5>
                            <p class="fs-4 fw-bold"><?php echo $tickets_abiertos; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <i class="ri-checkbox-circle-line fs-2 text-danger mb-2"></i>
                            <h5>Tickets Resueltos</h5>
                            <p class="fs-4 fw-bold"><?php echo $tickets_resueltos; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <i class="ri-progress-5-line fs-2 text-info mb-2"></i>
                            <h5>Tickets en progreso</h5>
                            <p class="fs-4 fw-bold"><?php echo $tickets_proceso; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4 shadow-sm">
                <div class="card-body">
                    <div id="adminChart" class="apexcharts-container"></div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Dashboard SOPORTE -->
            <?php if($_SESSION['rol'] === 'Soporte'): ?>
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <i class="ri-inbox-unarchive-line fs-2 text-primary mb-2"></i>
                            <h5>Tickets Asignados</h5>
                            <p class="fs-4 fw-bold"><?php echo $tickets_asignados; ?></p>
                            <a href="tickets_asignados.php" class="btn btn-light-secondary btn-sm mt-2">Ver tickets asignados</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <i class="ri-checkbox-circle-line fs-2 text-success mb-2"></i>
                            <h5>Tickets Resueltos</h5>
                            <p class="fs-4 fw-bold"><?php echo $tickets_resueltos; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <i class="ri-time-line fs-2 text-warning mb-2"></i>
                            <h5>Pendientes</h5>
                            <p class="fs-4 fw-bold"><?php echo $tickets_pendientes; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <i class="ri-progress-5-line fs-2 text-info mb-2"></i>
                            <h5>En progreso</h5>
                            <p class="fs-4 fw-bold"><?php echo $tickets_iniciados; ?></p>
                        </div>
                    </div>
                </div>
                <div class="card mt-4 shadow-sm">
                    <div class="card-body">
                        <div id="soporteChart" class="apexcharts-container"></div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Dashboard CLIENTE -->
            <?php if($_SESSION['rol'] === 'Cliente'): ?>
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <i class="ri-coupon-line fs-2 text-primary mb-2"></i>
                            <h5>Mis Tickets Activos</h5>
                            <p class="fs-4 fw-bold"><?php echo $tickets_activos; ?></p>
                            <a href="mistickets.php" class="btn btn-light-secondary btn-sm mt-2">Ver mis tickets</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <i class="ri-add-circle-line fs-2 text-success mb-2"></i>
                            <h5>Tickets Cerrados</h5>
                            <p class="fs-4 fw-bold"><?php echo $tickets_cerrados; ?></p>
                            <a href="apps-project-create.php" class="btn btn-light-success btn-sm mt-2">Crear nuevo ticket</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Scroll top -->
    <div class="progress-wrap d-flex align-items-center justify-content-center cursor-pointer h-40px w-40px position-fixed" id="progress-scroll">
        <svg class="progress-circle w-100 h-100 position-absolute" viewBox="0 0 100 100">
            <circle cx="50" cy="50" r="45" class="progress" />
        </svg>
        <i class="ri-arrow-up-line fs-16 z-1 position-relative text-primary"></i>
    </div>

    <!-- Footer -->
    <?php include_once("app/layout/footer.php")?>

    <!-- Scripts -->
    <script src="assets/libs/swiper/swiper-bundle.min.js"></script>
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/js/scroll-top.init.js"></script>
    <script src="assets/libs/apexcharts/apexcharts.min.js"></script>
    <script src="assets/js/dashboard/analytics.init.js"></script>
    <script type="module" src="assets/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    <?php if($_SESSION['rol'] === 'Administrador'): ?>
    var dashedChart = {
        series: [{
            name: "Tickets Creados",
            data: <?php echo $datos_tickets; ?>
        },
        {
            name: "Tickets Resueltos",
            data: <?php echo $datos_resueltos; ?>
        },
        {
            name: 'En Progreso',
            data: <?php echo $datos_progreso; ?>
        },
        {
            name: 'Clientes registrados',
            data: <?php echo $datos_clientes; ?>
        }
        ],
        chart: {
            height: 350,
            type: 'line',
            zoom: {
                enabled: false
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: [5, 7, 5],
            curve: 'straight',
            dashArray: [0, 8, 5]
        },
        title: {
            text: 'Estadísticas Generales',
            align: 'left'
        },
        legend: {
            tooltipHoverFormatter: function (val, opts) {
                return val + ' - <strong>' + opts.w.globals.series[opts.seriesIndex][opts.dataPointIndex] + '</strong>'
            }
        },
        markers: {
            size: 0,
            hover: {
                sizeOffset: 6
            }
        },
        xaxis: {
            categories: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
            ],
        },
        tooltip: {
            y: [
                {
                    title: {
                        formatter: function (val) {
                            return val + " tickets"
                        }
                    }
                },
                {
                    title: {
                        formatter: function (val) {
                            return val + " resueltos"
                        }
                    }
                },
                {
                    title: {
                        formatter: function (val) {
                            return val + " en progreso";
                        }
                    }
                },
                {
                    title: {
                        formatter: function (val) {
                            return val + " clientes"
                        }
                    }
                }
            ]
        },
        grid: {
            borderColor: '#f1f1f1',
        }
    };

    var chart = new ApexCharts(document.querySelector("#adminChart"), dashedChart);
    chart.render();
    <?php endif; ?>
    </script>

    <script>
    <?php if($_SESSION['rol'] === 'Soporte'): ?>
    var soporteChartConfig = {
        series: [
            {
                name: "Tickets Asignados",
                data: <?php echo $datos_soporte_asignados; ?>
            },
            {
                name: "Tickets Resueltos",
                data: <?php echo $datos_soporte_resueltos; ?>
            },
            {
                name: "En Progreso",
                data: <?php echo $datos_soporte_progreso; ?>
            }
        ],
        chart: {
            height: 350,
            type: 'line',
            zoom: { enabled: false },
        },
        dataLabels: { enabled: false },
        stroke: {
            width: [5, 7, 5],
            curve: 'straight',
            dashArray: [0, 8, 5]
        },
        title: {
            text: 'Estadísticas de los tickets asignados',
            align: 'left'
        },
        legend: {
            tooltipHoverFormatter: function(val, opts) {
                return val + ' - <strong>' + 
                    opts.w.globals.series[opts.seriesIndex][opts.dataPointIndex] + 
                '</strong>';
            }
        },
        markers: {
            size: 0,
            hover: { sizeOffset: 6 }
        },
        xaxis: {
            categories: [
                'Enero','Febrero','Marzo','Abril','Mayo','Junio',
                'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'
            ],
        },
        tooltip: {
            y: [
                { title: { formatter: val => val + " asignados" }},
                { title: { formatter: val => val + " resueltos" }},
                { title: { formatter: val => val + " en progreso" }},
            ]
        },
        grid: {
            borderColor: '#f1f1f1',
        }
    };

    var soporteChart = new ApexCharts(document.querySelector("#soporteChart"), soporteChartConfig);
    soporteChart.render();
    <?php endif; ?>
    </script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const params = new URLSearchParams(window.location.search);
        const msg = params.get('msg');
    
        if (msg === 'login') {
            Swal.fire({
                icon: 'success',
                title: 'Inicio de sesión exitoso',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });
        } else if (msg === 'contraseña_error') {
            Swal.fire({ icon: 'error', title: 'Contraseña incorrecta', text: 'Intenta nuevamente o restablece tu contraseña' });
        } else if (msg === 'email_error') {
            Swal.fire({ icon:'error', title: 'Email no registrado', text: 'Email no registrado, verifica que sea correcto' });
        }
    });
    </script>

</body>

</html>