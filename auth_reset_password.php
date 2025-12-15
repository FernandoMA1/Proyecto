<?php /* Mantener sin side-effects. Redirección la maneja Recuperar.php como endpoint. */ ?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-layout="vertical">

<head>
    <meta charset="utf-8" />
    <title> </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta content="Admin & Dashboards Template" name="description" />
    <meta content="Pixeleyez" name="author" />
    
    <!-- layout setup -->
    <script type="module" src="assets/js/layout-setup.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<!-- RemixIcon CDN -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/k_favicon_32x.png">    <!-- Simplebar Css -->
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
<!-- START -->
<div>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100 py-10">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card mx-xxl-8">
                    <div class="card-body py-12 px-8">
                        <img src="assets/images/logo-dark.png" alt="" height="30" class="mb-4 mx-auto d-block">
                        <h6 class="mb-3 mb-8 fw-medium text-center">Recuperar Contraseña</h6>
                        <form method="POST" action="Recuperar.php">
                            <div class="row g-4">
                                <div class="col-12">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="Enter your Email" required>
                                </div>
                                <div class="col-12 mt-8">
                                    <button type="submit" class="btn btn-primary w-full mb-5" id="btnEnviar">
                                        <span class="spinner-border spinner-border-sm me-2 d-none" id="spinnercorreo"></span>Enviar código de recuperación
                                    </button>
                                </div>
                            </div>
                            <p class="mb-0 fw-semibold position-relative text-center fs-12"><a href="auth_signin.php" class="text-decoration-underline text-primary">Regresar a inicio</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- JAVASCRIPT -->
<script src="assets/libs/swiper/swiper-bundle.min.js"></script>
<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/libs/simplebar/simplebar.min.js"></script>
<script src="assets/js/scroll-top.init.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  // Mostrar SweetAlerts según resultado
    const params = new URLSearchParams(window.location.search);
    const msg = params.get('msg');
    if (msg === 'email_no_registrado') {
    Swal.fire({ icon: 'error', title: 'Correo no registrado', text: 'Verifica el correo ingresado.' });
    } else if (msg === 'error_envio') {
    Swal.fire({ icon: 'error', title: 'Error al enviar código', text: 'Inténtalo de nuevo más tarde.' });
    } else if (msg === 'enviado') {
    Swal.fire({ icon: 'info', title: 'Código enviado', text: 'Revisa tu correo para el código.' });
    }
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector('form');
    const btnEnviar = document.getElementById('btnEnviar');
    const spinner = document.getElementById('spinnercorreo');
    form.addEventListener('submit', function () {
        spinner.classList.remove('d-none');
        btnEnviar.setAttribute('disabled', 'disabled');
        btnEnviar.classList.add('disabled');
        btnEnviar.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2"></span>
            Enviando código...
        `;
    });
});
</script>
</body>
</html>