<?php
include("conexion.php");
include("registrarcliente.php");
?>


<html lang="en" data-bs-theme="light" data-layout="vertical">

<head>
    <meta charset="utf-8" />
    <title>Registrarse </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta content="Admin & Dashboards Template" name="description" />
    
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
    <img src="assets/images/auth/login_bg.jpg" alt="" class="auth-bg light w-full h-full auth-bg-cover opacity-60 position-absolute top-0">
    <img src="assets/images/auth/auth_bg_dark.jpg" alt="" class="auth-bg d-none dark">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100 py-10">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card mx-xxl-8">
                    <div class="card-body py-12 px-8">
                        <img src="assets/images/logo-dark.png" alt="" height="30" class="mb-4 mx-auto d-block">
                        <h6 class="mb-3 mb-8 fw-medium text-center">Bienvenido usuario!</h6>
                        <h6 class="mb-3 mb-8 fw-medium text-center">Empecemos a crear tu cuenta.</h6>
                        <form method="post">
                            <div class="row g-4">
                                <div class="col-6">
                                    <label for="contact" class="form-label">Contacto <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="contact" placeholder="Ingresa el nombre de contacto" required>
                                </div>
                                <div class="col-6">
                                    <label for="razon_social" class="form-label">Razon Social <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="razon_social" placeholder="Razon social" required>
                                </div>
                                <div class="col-6">
                                    <label for="telefono" class="form-label">Telefono <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="telefono" placeholder="Telefono" required>
                                </div>
                                <div class="col-6">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="email" placeholder="Email" required>
                                </div>
                                <div class="col-12">
                                    <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
                                </div>
                                <div class="col-12">
                                    <label for="confirmpassword" class="form-label">Confirma Tu Contraseña <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="confirmpassword" placeholder="Confirma tu contraseña" required>
                                </div>
                                <div class="col-12 mt-8">
                                    <button type="submit" class="btn btn-primary w-full mb-4" id="btnRegistrar">
                                        <span class="spinner-border spinner-border-sm me-2 d-none" id="spinnerSignup"></span>Registrarse<i class="bi bi-box-arrow-in-right ms-1 fs-16"></i>
                                    </button>
                                </div>
                            </div>
                            <p class="mb-0 fw-semibold position-relative text-center fs-12">Ya tienes una Cuenta? <a href="auth_signin.php" class="text-decoration-underline text-primary">Inicia Sesión Aquí</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="assets/libs/swiper/swiper-bundle.min.js"></script>
<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/libs/simplebar/simplebar.min.js"></script>
<script src="assets/js/scroll-top.init.js"></script>
<script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
<script src="assets/js/ui/sweetalert.init.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const params = new URLSearchParams(window.location.search);
        const msg = params.get('msg');

        if(msg === 'registro_exitoso'){
            Swal.fire({
                icon: 'success',
                title: 'Registro exitoso',
                text: 'Tu cuenta ha sido registrada exitosamente, ahora puede iniciar sesión',
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
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector('form');
        const btnEnviar = document.getElementById('btnRegistrar');
        const spinner = document.getElementById('spinnerSignup');

        form.addEventListener('submit', function () {
            spinner.classList.remove('d-none');
            btnEnviar.setAttribute('disabled', 'disabled');
            btnEnviar.classList.add('disabled');
            btnEnviar.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2"></span>
                Registrando cuenta...
            `;
        });
    });
</script>
</body>
</html>