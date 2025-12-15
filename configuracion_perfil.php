<?php 
session_start();
include("conexion.php");
include("menu.php");
$es_cliente = isset($_SESSION['rol']) && strcasecmp($_SESSION['rol'], 'cliente') === 0;
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-layout="vertical">

<head>

    <meta charset="utf-8" />
    <title>Pages | FabKin Admin & Dashboards Template </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta content="Admin & Dashboards Template" name="description" />
    <meta content="Pixeleyez" name="author" />
    
    <!-- layout setup -->
    <script type="module" src="assets/js/layout-setup.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<!-- RemixIcon CDN -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/k_favicon_32x.png">    <link rel="stylesheet" href="assets/libs/choices.js/public/assets/styles/choices.min.css">
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
                <h6 class="mb-0 flex-grow-1">Perfil</h6>
                <div class="flex-shrink-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-end mb-0">
                            <li class="breadcrumb-item active" aria-current="page">Editar perfil</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="card overflow-hidden">
                <div class="card-body h-176px" style="background-image: url('assets/images/background.png');background-repeat: no-repeat;background-position: right;"></div>
                <div class="mt-2">
                    <div class="card-body p-5">
                        <div class="d-flex flex-wrap align-items-start gap-5">
                            <div class="mt-n12 flex-shrink-0">
                                <div class="position-relative d-inline-block">
                                    <img src="<?= isset($_SESSION['avatar']) ? htmlspecialchars(string: $_SESSION['avatar']) : 'assets\images\avatar\avatar-default.png' ?>" alt="" class="h-128px w-128px border border-4 border-white shadow-lg">
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="mb-5">
                                    <h5 class="mb-1"><?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Cliente') { echo htmlspecialchars($_SESSION['nombre']); }else{echo htmlspecialchars($_SESSION['nombre_completo']);} ?>
                                        <button type="button" class="btn btn-light-dark btn-sm" data-bs-toggle="modal" data-bs-target="#editarperfil">
                                            <i class="ri-edit-2-line me-1"></i>Editar</button>
                                    </h5>
                                    <p class="text-muted fs-6 mb-0"><?php if(isset($_SESSION['rol'])&& $_SESSION['rol'] === 'Cliente') { echo htmlspecialchars($_SESSION['razon']); }else{ echo htmlspecialchars(string: $_SESSION['rol']);}?></p>
                                </div>
                                <div class="row g-5 mt-2">
                                    <div class="col-md-4 col-xl-3">
                                        <div class="d-flex gap-2">
                                            <i class="bi bi-mailbox fs-16"></i>
                                            <p class="text-muted mb-2">Email</p>
                                        </div>
                                        <h6 class="mb-0"><?= isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'Invitado'?></h6>
                                    </div>
                                    <div class="col-md-4 col-xl-3">
                                        <div class="d-flex gap-2">
                                            <i class="bi bi-telephone fs-16"></i>
                                            <p class="text-muted mb-2">No. Teléfono</p>
                                        </div>
                                        <h6 class="mb-0">+52 <?= isset($_SESSION['telefono']) ? htmlspecialchars($_SESSION['telefono']) : 'Invitado'?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                            <div class="row">
                                <div class="col-12 col-xl-4">
                                    <div class="card">
                                        <div class="card-header align-items-center">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-action-title mb-0">Acerca de</h5>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h6 class="fs-16 mb-5">Información de contacto</h6>
                                            <div class="mb-8">
                                                <div class="row my-3">
                                                    <div class="col-3">
                                                        <label for="firstNameLayout1" class="mb-2 mb-lg-0 text-muted">Nombre completo:</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <p class="mb-0 fw-medium"><?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Cliente') { echo htmlspecialchars($_SESSION['nombre']); }else{echo htmlspecialchars($_SESSION['nombre_completo']);} ?></p>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-3">
                                                        <label for="firstNameLayout2" class="mb-2 mb-lg-0 text-muted">No.Teléfono</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <p class="mb-0 fw-medium">+52 <?= isset($_SESSION['telefono']) ? htmlspecialchars($_SESSION['telefono']) : 'Invitado'?></p>
                                                    </div>
                                                </div>
                                                <?php if (isset($_SESSION['rol']) && strcasecmp($_SESSION['rol'], 'cliente') === 0): ?>
                                                    <div class="row mb-3">
                                                        <div class="col-3">
                                                            <label for="firstNameLayout4" class="mb-2 mb-lg-0 text-muted">Razón social: </label>
                                                        </div>
                                                        <div class="col-9">
                                                            <p class="mb-0 fw-medium" class="link link-primary fw-medium text-body"><?= isset($_SESSION['razon']) ? htmlspecialchars($_SESSION['razon']) : 'Invitado' ?></p>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="row mb-3">
                                                    <div class="col-3">
                                                        <label for="firstNameLayout4" class="mb-2 mb-lg-0 text-muted">Email: </label>
                                                    </div>
                                                    <div class="col-9">
                                                        <p class="mb-0 fw-medium" class="link link-primary fw-medium text-body"><?= isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'Invitado'?></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="row my-3">
                                                    <div class="col-3">
                                                        <label for="firstNameLayout6" class="mb-2 mb-lg-0 text-muted">Fecha de registro</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <p class="mb-0 fw-medium"><?= isset($_SESSION['fecha_alta']) ? date('d/m/Y', strtotime($_SESSION['fecha_alta'])) : 'Sin fecha registrada'; ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="editarperfil" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editarperfil" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editarperfil">Editar Perfil</h5>
                                        <button type="button" class="btn-close icon-btn-sm" data-bs-dismiss="modal" aria-label="Close">
                                            <i class="ri-close-large-line fw-semibold"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="Actualizarperfil.php" enctype="multipart/form-data">
                                            <div class="row g-3">
                                                <div class="col-12 mt-3">
                                                    <label for="foto_perfil" class="form-label">Foto de perfil</label>
                                                    <div class="mb-2">
                                                        <img id="preview" 
                                                            src="<?= isset($_SESSION['avatar']) ? htmlspecialchars($_SESSION['avatar']) : 'assets/images/avatar/avatar-default.png' ?>" 
                                                            class="rounded border" 
                                                            style="width: 120px; height: 120px; object-fit: cover;">
                                                    </div>
                                                    <button type="button" class="btn btn-danger btn-sm mt-2" id="btnEliminarFoto" style="<?= (isset($_SESSION['avatar']) && $_SESSION['avatar'] !== 'assets/images/avatar/avatar-default.png') ? '' : 'display:none;' ?>">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                    <input type="file" name="foto_perfil" id="foto_perfil" accept="image/*">
                                                </div>
                                                <?php if ($es_cliente): ?>
                                                <div class="col-12">
                                                    <label for="contacto" class="form-label">Contacto</label>
                                                    <input type="text" class="form-control" id="contacto" name="contacto" placeholder="Inserta tu nombre de contacto" value="<?= isset($_SESSION['nombre']) ? htmlspecialchars($_SESSION['nombre']) : '' ?>" required>
                                                </div>
                                                <div class="col-12">
                                                    <label for="razon_social" class="form-label">Razón social</label>
                                                    <input type="text" class="form-control" id="razon_social" name="razon_social" placeholder="Inserta tu razón social" value="<?= isset($_SESSION['razon']) ? htmlspecialchars($_SESSION['razon']): '' ; ?>" required>
                                                </div>
                                                <?php else: ?>
                                                <div class="col-12">
                                                    <label for="nombre" class="form-label">Nombre</label>
                                                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Tu nombre" value="<?= isset($_SESSION['nombre']) ? htmlspecialchars($_SESSION['nombre']): '' ; ?>" required>
                                                </div>
                                                <div class="col-12">
                                                    <label for="apellidos" class="form-label">Apellidos</label>
                                                    <input type="text" class="form-control" id="apellidos" name="apellidos" placeholder="Tus apellidos" value="<?= isset($_SESSION['apellidos']) ? htmlspecialchars($_SESSION['apellidos']): '' ; ?>" required>
                                                </div>
                                                <?php endif; ?>
                                                <div class="col-12">
                                                    <label for="email" class="form-label">Correo</label>
                                                    <input type="email" class="form-control" id="email" name="email" placeholder="Insertar correo" value="<?= isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '' ?>" required>
                                                </div>
                                                <div class="col-12">
                                                    <label for="telefono" class="form-label">Teléfono</label>
                                                    <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Insertar número de teléfono" value="<?= isset($_SESSION['telefono']) ? htmlspecialchars($_SESSION['telefono']) : '' ?>" required>
                                                </div>
                                            </div>
                                            <div class="mt-3 d-flex justify-content-end gap-3">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" name="actualizar" id="btnActualizarPerfil" class="btn btn-primary">
                                                    <span class="spinner-border spinner-border-sm me-2 d-none" id="spinnerActualizarPerfil" role="status" aria-hidden="true"></span>
                                                    Actualizar perfil
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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
<!-- End Begin page -->

<!-- JAVASCRIPT -->
<script src="assets/libs/swiper/swiper-bundle.min.js"></script>
<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/libs/simplebar/simplebar.min.js"></script>
<script src="assets/js/scroll-top.init.js"></script>
<script src="assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="assets/js/pages/profile-setting.init.js"></script>
<!-- App js -->
<script type="module" src="assets/js/app.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const params = new URLSearchParams(window.location.search);
    const msg = params.get('msg');
    if (msg === 'perfil_actualizado') {
        Swal.fire({
            icon: 'success',
            title: 'Perfil actualizado',
            text: 'Tus cambios se han guardado correctamente.',
            timer: 2500,
            showConfirmButton: false
        });
        
    } else if (msg === 'error_email') {
        Swal.fire({ icon: 'error', title: 'Email registrado', text: 'Ya existe una cuenta con ese email' });
    } else if (msg === 'error_sql' || msg === 'error_guardar') {
        Swal.fire({ icon: 'error', title: 'Error al guardar', text: 'Intenta nuevamente o contacta soporte.' });
    }
});
</script>
<script>
document.getElementById("btnEliminarFoto").addEventListener("click", function () {
    const btnEliminar = this;

    Swal.fire({
        title: "¿Eliminar foto de perfil?",
        text: "Se restablecerá la foto predeterminada.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {

            fetch("eliminar_foto_perfil.php", { method: "POST" })
            .then(r => r.json())
            .then(data => {

                if (data.ok) {

                    document.getElementById("preview").src = "assets/images/avatar/avatar-default.png";

                    // Ocultar botón
                    btnEliminar.style.display = "none";

                    Swal.fire({
                        icon: "success",
                        title: "Foto eliminada",
                        timer: 2000,
                        showConfirmButton: false
                    });

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "No se pudo eliminar la foto."
                    });
                }
            });
        }
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector('#editarperfil form') || document.querySelector('form[action="Actualizarperfil.php"]');
    const btn = document.getElementById('btnActualizarPerfil');
    const spinner = document.getElementById('spinnerActualizarPerfil');

    if (form && btn) {
        form.addEventListener('submit', function (e) {
            try {
                if (spinner) spinner.classList.remove('d-none');
                btn.setAttribute('disabled', 'disabled');
                btn.classList.add('disabled');
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Actualizando perfil...';
            } catch (err) {
                console.error('Error al mostrar spinner:', err);
            }
        });
    } else {
    }
});
</script>

<script>
document.getElementById('foto_perfil').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('preview');
    const btnEliminar = document.getElementById('btnEliminarFoto');

    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(event) {
        preview.src = event.target.result;
        btnEliminar.style.display = "inline-block";
    };
    reader.readAsDataURL(file);
});
</script>
</body>
</html>