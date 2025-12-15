<?php
session_start();
include("Mostrartablausuarios.php");
include("conexion.php");
include("AñadirUsuario.php");
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
    <link rel="stylesheet" href="../../1.11.5/css/dataTables.bootstrap5.min.css">
    <!--datatable responsive css-->
    <link rel="stylesheet" href="../../responsive/2.2.9/css/responsive.bootstrap.min.css">


</head>

<body>
        <div class="sidebar-backdrop" id="sidebar-backdrop"></div>
        <main class="app-wrapper">
            <div class="container-fluid">
                <div class="d-flex align-items-center mt-2 mb-2">
                    <h6 class="mb-0 flex-grow-1">Usuarios</h6>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center">
                                    <h6 class="card-title mb-0 fw-semibold">
                                        Usuarios <span class="badge bg-secondary-subtle text-secondary ms-1"></span>
                                    </h6>
                                    <div class="d-flex flex-wrap gap-3 align-items-center">
                                        <a href="Exportarpdf.php?tabla=usuarios" class="btn btn-light-info">Exportar PDF</a>
                                        <a href="Exportarexcel.php?tabla=usuarios" class="btn btn-light-info">Exportar a excel</a>
                                        <button type="button" class="btn btn-light-primary" data-bs-toggle="modal" data-bs-target="#createContactModal">
                                            <i class="bi bi-plus-lg me-1"></i>Añadir usuario
                                        </button>
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
                                                <th scope="col">Nombre(s)</th>
                                                <th scope="col">Apellidos</th>
                                                <th scope="col">Correo</th>
                                                <th scope="col">Telefono</th>
                                                <th scope="col">Rol asignado</th>
                                                <th scope="col">Estatus</th>
                                                <th scope="col">Fecha de Registro</th>
                                                <th scope="col">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            if ($resultado->num_rows > 0) {
                                                while ($usuario = $resultado->fetch_assoc()) {
                                                    $rol_class = '';
                                                    switch ($usuario['rol']) {
                                                        case 'Administrador':
                                                            $rol_class = 'bg-info text-white';
                                                            break;
                                                        case 'Soporte':
                                                            $rol_class = 'bg-secondary text-white';
                                                            break;
                                                    }
                                                    $estatus_class = '';
                                                    switch ($usuario['estatus_Usuario']) {
                                                        case 'Disponible':
                                                            $estatus_class = 'bg-success text-white';
                                                            break;
                                                        case 'No Disponible':
                                                            $estatus_class = 'bg-danger text-white';
                                                            break;
                                                    }
                                            ?>
                                                <tr>
                                                    <td><?php echo $usuario['id_usuario']; ?></td>
                                                    <td><img src="<?= isset($usuario['avatar']) && !empty($usuario['avatar']) ? htmlspecialchars($usuario['avatar']) : 'assets/images/avatar/avatar-default.png' ?>" alt="Avatar" class="img-fluid rounded-circle" style="width: 40px; height: 40px; object-fit: cover;"></td>
                                                    <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                                    <td><?php echo htmlspecialchars($usuario['apellidos']); ?></td>
                                                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                                    <td><?php echo htmlspecialchars($usuario['telefono']); ?></td>
                                                    <td><span class="badge <?php echo $rol_class; ?>"><?php echo $usuario['rol']; ?></span></td>
                                                    <td><span class="badge <?php echo $estatus_class; ?>"><?php echo $usuario['estatus_Usuario']; ?></span></td>
                                                    <td><?php echo ($usuario['fecha_registro_usuario']); ?></td>
                                                    <td>
                                                        <a href="view-user.php?id=<?php echo $usuario['id_usuario']; ?>" class="btn btn-light-secondary">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                        <a href="Eliminarusuarios.php?id=<?php echo $usuario['id_usuario']; ?>" class="btn btn-light-danger eliminar-usuario">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </a>
                                                    </td>
                                                    </tr>
                                                <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="9" class="text-center">No se encontraron Usuarios</td>
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

                                                // Mostrar páginas en el rango
                                                for ($i = $inicio_rango; $i <= $fin_rango; $i++) {
                                                    echo '<li class="page-item ' . ($i == $pagina_actual ? 'active' : '') . '">
                                                    <a class="page-link" href="?pagina=' . $i . '">' . $i . '</a>
                                                </li>';
                                                }

                                                // Mostrar última página si estamos lejos
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
                    <div class="modal fade" id="createContactModal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="createContactModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="createContactModalLabel">Añadir nuevo usuario</h5>
                                    <button type="button" class="btn-close icon-btn-sm" data-bs-dismiss="modal" aria-label="Close">
                                        <i class="ri-close-large-line fw-semibold"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label for="nombre" class="form-label">Nombre</label>
                                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Insertar nombre de usuario" required>
                                            </div>
                                            <div class="col-12">
                                                <label for="apellidos" class="form-label">Apellidos</label>
                                                <input type="text" class="form-control" id="apellidos" name="apellidos" placeholder="Insertar apellidos" required>
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
                                                <label for="rol" class="form-label">Rol</label>
                                                <select class="form-select" id="rol" name="rol" required>
                                                    <option value="Administrador">Administrador</option>
                                                    <option value="Soporte">Soporte</option>
                                                </select>
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
                                            <div class="mb-8 d-flex justify-content-end gap-3"></div>
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary" id="btnEnviarUsuario">
                                                    <span class="spinner-border spinner-border-sm me-2 d-none" id="spinnerUsuario"></span>
                                                    Añadir usuario
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
    <!-- Datepicker Js -->
    <script src="assets/libs/air-datepicker/air-datepicker.js"></script>
    <!-- Selector Js -->
    <script src="assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>
    <!-- File Js -->
    <script src="assets/js/app/crm-contact.init.js"></script>
    <!-- App js -->
    <script type="module" src="assets/js/app.js"></script>
    <script src="../../1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="../../1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="../../responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="../../buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="../../buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="../../buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="../../ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="../../ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="../../ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector('#createContactModalLabel form');
        const btnEnviar = document.getElementById('btnEnviarUsuario');
        const spinner = document.getElementById('spinnerUsuario');

        form.addEventListener('submit', function () {
            spinner.classList.remove('d-none');
            btnEnviar.setAttribute('disabled', 'disabled');
            btnEnviar.classList.add('disabled');
            btnEnviar.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2"></span>
                Añadiendo usuario...
            `;
        });
    });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const params = new URLSearchParams(window.location.search);
        const msg = params.get('msg');

        if (msg === 'usuario_editado') {
            Swal.fire({
                icon: 'success',
                title: 'Se ha actualizado el usuario',
                text: 'Tus cambios se han guardado correctamente.',
                timer: 2500,
                showConfirmButton: false
            });
        
        } else if (msg === 'error_sql' || msg === 'error_guardar') {
        Swal.fire({ icon: 'error', title: 'Error al guardar', text: 'Intenta nuevamente' });
        }

        if(msg === 'registro_exitoso'){
            Swal.fire({
                icon: 'success',
                title: 'Registro exitoso',
                text: 'El usuario ha sido registrado exitosamente, ahora puede iniciar sesión',
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
    <script>
        document.querySelectorAll(".eliminar-usuario").forEach(function(link) {
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

        // ERROR: tiene tickets
        if (msg === "error") {
            Swal.fire({
                icon: 'error',
                title: 'Error al eliminar usuario',
                text: 'El usuario tiene tickets asignados',
                confirmButtonText: "Aceptar"
            }).then(() => {
                window.location.href = "listadeusuarios.php";
            });
        }

        if (msg === "user_deleted") {
            Swal.fire({
                icon: 'success',
                title: 'Usuario eliminado',
                text: 'El usuario ha sido eliminado correctamente.',
                confirmButtonText: "Aceptar"
            }).then(() => {
                window.location.href = "listadeusuarios.php";
            });
        }
    });
    </script>

</body>

</html>