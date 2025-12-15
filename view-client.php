<?php
session_start();
include('view.php');
include('conexion.php');
include('Editclient.php');
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
    <main class="app-wrapper d-flex align-items-center justify-content-center" style="min-height: 90vh;">
        <div class="container" style="max-width: 1200px;">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header bg-light d-flex justify-content-between align-items-center" style="border-radius: 12px 12px 0 0;">
                    <h5 class="mb-0">Administrar cliente</h5>
                    <a href="apps-crm-contact.php" class="btn btn-dark btn-sm">
                        <i class="ri-arrow-left-s-line"></i> Regresar
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row g-5">
                            <div class="col-12">
                                <label for="contacto" class="form-label">Contacto</label>
                                <input type="text" class="form-control" id="contacto" name="contacto" placeholder="Ingresa el contacto" value="<?= $cliente['contacto']; ?>" required>
                            </div>
                            <div class="col-xl-12">
                                <label for="razon" class="form-label">Razón social</label>
                                <input type="text" class="form-control" id="razon" name="razon" placeholder="Ingresa la razon social" value="<?= $cliente['Razon_social']; ?>" required>
                            </div>
                            <div class="col-12">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Ingresa el email" value="<?= $cliente['email']; ?>" required>
                            </div>
                            <div class="col-12">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Ingresa el telefono" value="<?= $cliente['telefono']; ?>" required>
                            </div>
                            <div class="col-xl-6">
                                <label for="estatus" class="form-label">Estatus</label>
                                <select class="form-select" id="estatus" name="estatus" required>
                                    <option value="Disponible" <?= $cliente['estatus_cliente'] === 'Disponible' ? 'selected' : '' ?>>Disponible</option>
                                    <option value="No Disponible" <?= $cliente['estatus_cliente'] === 'No Disponible' ? 'selected' : '' ?>>No Disponible</option>
                                </select>
                            </div>
                            <div class="mb-8 d-flex justify-content-end gap-3">
                                <button type="submit" class="btn btn-light-info" id="btnGuardarClient">
                                    <span class="spinner-border spinner-border-sm me-2 d-none" id="spinnerClient"></span>Guardar cambios
                                </button>
                            </div>
                        </div>
                    </form>
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
    </div>
    <script src="assets/libs/swiper/swiper-bundle.min.js"></script>
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/js/scroll-top.init.js"></script>
    <script src="assets/libs/air-datepicker/air-datepicker.js"></script>
    <script src="assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>
    <script src="assets/js/app/crm-contact.init.js"></script>
    <script type="module" src="assets/js/app.js"></script>
    <script src="../../1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="../../1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="../../responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="../../buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="../../buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="../../buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="../../ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const params = new URLSearchParams(window.location.search);
        const msg = params.get('msg');
        if (msg === 'estatus_editado') {
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
    });
    </script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector('form');
        const btnEnviar = document.getElementById('btnGuardarClient');
        const spinner = document.getElementById('spinnerClient');

        form.addEventListener('submit', function () {
            spinner.classList.remove('d-none');
            btnEnviar.setAttribute('disabled', 'disabled');
            btnEnviar.classList.add('disabled');
            btnEnviar.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2"></span>
                Guardando cambios...
            `;
        });
    });
    </script>
</body>

</html>