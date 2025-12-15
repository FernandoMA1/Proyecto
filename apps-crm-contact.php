<?php
session_start();
include("Mostrartablaclientes.php");
include("conexion.php");
include("menu.php");
?>

<html lang="en" data-bs-theme="light" data-layout="vertical">

<head>

    <meta charset="utf-8" />
    <title>Plataforma Tickets</title>
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
                <h6 class="mb-0 flex-grow-1">Clientes</h6>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center">
                                <h6 class="card-title mb-0 fw-semibold">
                                    Clientes <span class="badge bg-secondary-subtle text-secondary ms-1"></span>
                                </h6>
                                <div class="d-flex flex-wrap gap-3 align-items-center">
                                    <button class="btn btn-light-info" onclick="exportarTabla('pdf')">Exportar a pdf</button>
                                    <button class="btn btn-light-info" onclick="exportarTabla('excel')">Exportar a excel</button>
                                    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador'): ?>
                                        <button type="button" class="btn btn-light-primary" data-bs-toggle="modal" data-bs-target="#crearCliente">
                                            <i class="bi bi-plus-lg me-1"></i>Añadir cliente
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-box table-responsive">
                                <table class="table text-nowrap align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col"></th>
                                            <th scope="col">Contacto</th>
                                            <th scope="col">Correo</th>
                                            <th scope="col">Telefono</th>
                                            <th scope="col">Razon Social</th>
                                            <th scope="col">Estatus</th>
                                            <th scope="col">Fecha de Registro</th>
                                            <?php if (!empty($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador'): ?>
                                                <th scope="col">Acciones</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $clientes = obtenerClientes($inicio, $registros_por_pagina);
                                        if (count($clientes) > 0) {
                                            foreach ($clientes as $cliente) {
                                                echo '<tr>';
                                                echo '<td>' . $cliente['id_cliente'] . '</td>';
                                                echo '<td> <img src="' . (isset($cliente['avatar']) && !empty($cliente['avatar']) ? htmlspecialchars($cliente['avatar']) : 'assets/images/avatar/avatar-default.png') . '" alt="Avatar" class="img-fluid rounded-circle" style="width: 40px; height: 40px; object-fit: cover;"></td>';
                                                echo '<td>' . htmlspecialchars($cliente['contacto']) . '</td>';
                                                echo '<td>' . htmlspecialchars($cliente['email']) . '</td>';
                                                echo '<td>' . htmlspecialchars($cliente['telefono']) . '</td>';
                                                echo '<td>' . htmlspecialchars($cliente['Razon_social']) . '</td>';
                                                $estatus_class = '';
                                                if ($cliente['estatus_cliente'] == 'Disponible') {
                                                    $estatus_class = 'bg-success text-white';
                                                } else if ($cliente['estatus_cliente'] == 'No Disponible') {
                                                    $estatus_class = 'bg-danger text-white';
                                                } else {
                                                    $estatus_class = 'bg-warning text-white';
                                                }
                                                echo '<td><span class="badge ' . $estatus_class . '">' . htmlspecialchars($cliente['estatus_cliente']) . '</span></td>';
                                                echo '<td>' . htmlspecialchars($cliente['fecha_registro']) . '</td>';
                                                if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador') {
                                                    echo '<td>
                                                        <div class="hstack gap-2">
                                                            <a href="view-client.php?id=' . $cliente['id_cliente'] .'" class="btn btn-light-secondary">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </a>
                                                            <a href="Eliminarclientes.php?id=' . $cliente['id_cliente'] . '" class="btn btn-light-danger eliminar-cliente">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </a>
                                                        </div>
                                                    </td>';
                                                echo '</tr>';
                                                }
                                            }
                                        } else {
                                        ?>
                                            <tr>
                                                <td colspan="8" class="text-center">No se encontraron clientes</td>
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
                <div class="modal fade" id="crearCliente" data-bs-keyboard="false" tabindex="-1" aria-labelledby="crearClienteLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="crearClienteLabel">Añadir nuevo cliente</h5>
                                <button type="button" class="btn-close icon-btn-sm" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="ri-close-large-line fw-semibold"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="AñadirCliente.php">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="contacto" class="form-label">Contacto</label>
                                            <input type="text" class="form-control" id="contacto" name="contact" placeholder="Insertar nombre de contacto" required>
                                        </div>
                                        <div class="col-12">
                                            <label for="razon_social" class="form-label">Razón Social</label>
                                            <input type="text" class="form-control" id="razon_social" name="razon_social" placeholder="Insertar Razón social" required>
                                        </div>
                                        <div class="col-12">
                                            <label for="email" class="form-label">Correo</label>
                                            <input type="text" class="form-control" id="email" name="email" placeholder="Insertar correo" required>
                                        </div>
                                        <div class="col-12">
                                            <label for="telefono" class="form-label">Telefono</label>
                                            <input type="number" class="form-control" id="telefono" name="telefono" placeholder="Insertar numero de telefono" required>
                                        </div>
                                        <div class="col-12">
                                            <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
                                        </div>
                                        <div class="col-12">
                                            <label for="confirmpassword" class="form-label">Confirma Tu Contraseña <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" name="confirmpassword" placeholder="Confirma tu contraseña" required>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end gap-3 mt-3">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary" id="btnEnviarCliente">
                                            <span class="spinner-border spinner-border-sm me-2 d-none" id="spinnerCliente"></span>
                                            Añadir cliente
                                        </button>
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
    <?php include_once("app/layout/footer.php");?>
    <!-- END Footer -->

    </div>
    <!-- END page -->

    <!-- JAVASCRIPT -->
    <script src="assets/libs/swiper/swiper-bundle.min.js"></script>
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/js/scroll-top.init.js"></script>
    <!-- Datepicker Js -->
    <script src="assets/libs/air-datepicker/air-datepicker.js"></script>
    <!-- Selector Js -->
    <script src="assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>
    <!-- File Js -->
    <script src="assets/js/app/crm-contact.init.js"></script>
    <!-- App js -->
    <script type="module" src="assets/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector('#crearCliente form');
        const btnEnviar = document.getElementById('btnEnviarCliente');
        const spinner = document.getElementById('spinnerCliente');

        form.addEventListener('submit', function () {
            spinner.classList.remove('d-none');
            btnEnviar.setAttribute('disabled', 'disabled');
            btnEnviar.classList.add('disabled');
            btnEnviar.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2"></span>
                Añadiendo cliente...
            `;
        });
    });
    </script>

    <script>
    function exportarTabla(tipo) {
    var tabla = 'clientes';
    var url = tipo === 'excel'
        ? 'Exportarexcel.php?tabla=' + tabla
        : 'Exportarpdf.php?tabla=' + tabla;
    window.location.href = url;
    }
    document.querySelectorAll(".eliminar-cliente").forEach(function(link) {
        link.addEventListener("click", function(e) {
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
    </script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const params = new URLSearchParams(window.location.search);
        const msg = params.get("msg");

        if (msg === "error") {
            Swal.fire({
                icon: 'error',
                title: 'Error al eliminar cliente',
                text: 'El cliente tiene tickets creados',
                confirmButtonText: "Aceptar"
            }).then(() => {
                window.location.href = "apps-crm-contact.php";
            });
        }

        if (msg === "client_deleted") {
            Swal.fire({
                icon: 'success',
                title: 'Cliente eliminado',
                text: 'El cliente ha sido eliminado correctamente.',
                confirmButtonText: "Aceptar"
            }).then(() => {
                window.location.href = "apps-crm-contact.php";
            });
        }

        if(msg === 'registro_exitoso'){
            Swal.fire({
                icon: 'success',
                title: 'Registro exitoso',
                text: 'El cliente ha sido registrado exitosamente, ahora puede iniciar sesión',
                timer: 2500,
                showConfirmButton: false
            });
        }else if(msg === 'registro_fallido'){
            Swal.fire({ icon: 'error', title: 'Error al registrar', text: 'El usuario no ha podido ser registrado. Intenta nuevamente y verifica los campos'})
        }else if(msg === 'email_en_uso'){
            Swal.fire({ icon: 'error', title: 'Error al registrar', text: 'El correo que has ingresado ya esta en uso. Intenta con otro correo'})
        }else if(msg === 'telefono_en_uso'){
            Swal.fire({ icon: 'error', title: 'Error al registrar', text: 'El telefono que has ingresado ya esta en uso. Intenta con otro numero'})
        }else if(msg === 'password_coincidencia'){
            Swal.fire({ icon: 'error', title: 'Error al registrar', text: 'Las contraseñas no coinciden. Verifica que coincidan'})
        }
    });
</script>
</body>

</html>