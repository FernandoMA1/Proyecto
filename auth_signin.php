<?php
include("Login.php");
?>

<html lang="en" data-bs-theme="light" data-layout="vertical">

<head>
    <meta charset="utf-8" />
    <title>Iniciar sesión </title>
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
    <img src="assets/images/auth/login_bg.jpg" alt="" class="auth-bg light w-full h-full opacity-60 position-absolute top-0">
    <img src="assets/images/auth/auth_bg_dark.jpg" alt="" class="auth-bg d-none dark">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100 py-10">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card mx-xxl-8">
                    <div class="card-body py-12 px-8">
                        <img src="assets/images/logo-dark.png" alt="" height="30" class="mb-4 mx-auto d-block">
                        <h6 class="mb-3 mb-8 fw-medium text-center">Iniciar sesión</h6>
                        <form method="post">
                            <div class="row g-4">
                                <div class="col-12">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="Ingresa tu correo" required>
                                </div>
                                <div class="col-12">
                                    <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Ingresa tu contraseña" required>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <div class="form-text">
                                            <a href="auth_reset_password.php" class="link link-primary text-muted text-decoration-underline">Olvidaste tu Contraseña?</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mt-8">
                                    <button type="submit" class="btn btn-primary w-full mb-4" id="btnLogin">
                                        <span class="spinner-border spinner-border-sm me-2 d-none" id="spinnerLogin"></span>Iniciar Sesión<i class="bi bi-box-arrow-in-right ms-1 fs-16"></i>
                                </div>
                            </div>
                            <p class="mb-0 fw-semibold position-relative text-center fs-12">No tienes una cuenta? <a href="auth_signup.php" class="text-decoration-underline text-primary">Registrate aquí</a></p>
                        </form>
                        <div class="text-center">
                        </div>
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
document.addEventListener("DOMContentLoaded", function() {
    const params = new URLSearchParams(window.location.search);
    const msg = params.get('msg');
    if (msg === 'contraseña_error') {
            Swal.fire({ icon: 'error', title: 'Contraseña incorrecta', text: 'Intenta nuevamente o restablece tu contraseña' });
        } else if (msg === 'email_error') {
            Swal.fire({ icon:'error', title: 'Email no registrado', text: 'Parece que este correo no está registrado, verifica que sea correcto' });
        }else if(msg === 'estatus_error'){
            Swal.fire({icon: 'error', title:'Error al iniciar sesión', text: 'Tu cuenta ha sido marcada como No Disponible. Ponte en contacto con nosotros para activarla de nuevo.'});
        }
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector('form');
        const btnEnviar = document.getElementById('btnLogin');
        const spinner = document.getElementById('spinnerLogin');

        form.addEventListener('submit', function () {
            spinner.classList.remove('d-none');
            btnEnviar.setAttribute('disabled', 'disabled');
            btnEnviar.classList.add('disabled');
            btnEnviar.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2"></span>
                Iniciando sesión...
            `;
        });
    });
</script>
</body>
</html>