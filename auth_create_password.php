<?php 
include("Reestablecer.php");
$email = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';
$codigo = isset($_GET['codigo']) ? htmlspecialchars($_GET['codigo']) : '';
?>
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
    <img src="assets/images/auth/login_bg.jpg" alt="" class="auth-bg light w-full h-full opacity-60 position-absolute top-0">
    <img src="assets/images/auth/auth_bg_dark.jpg" alt="" class="auth-bg d-none dark">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100 py-10">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card mx-xxl-8">
                    <div class="card-body py-12 px-8">
                        <img src="assets/images/logo-dark.png" alt="" height="30" class="mb-4 mx-auto d-block">
                        <h6 class="mb-3 mb-8 fw-medium text-center">Recuperar contraseña</h6>
                        <form id="passwordForm" action="Reestablecer.php" method="POST">
                            <input type="hidden" name="email" value="<?php echo $email; ?>">
                            <input type="hidden" name="codigo" value="<?php echo $codigo; ?>">
                            <div class="row g-4">
                                <div class="col-12 password-field-wrapper">
                                    <label for="password" class="form-label">Nueva contraseña :</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <div class="password-strength fs-12 mt-2" id="passwordStrength">La contraseña debe contener mas de 8 caracteres</div>
                                </div>
                                <div class="password-field-wrapper">
                                    <label for="confirmPassword" class="form-label">Confirmar nueva contraseña :</label>
                                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                                </div>
                                <div class="error" id="confirmPasswordError"></div>
                                <button type="submit" id="btnCambiarP" class="btn btn-primary w-full">
                                    <span class="spinner-border spinner-border-sm me-2 d-none" id="spinnerCambiarP"></span>Cambiar contraseña
                                </button>
                                <p class="mb-0 fw-semibold position-relative text-center fs-12">Regresar a <a href="auth_signin.php" class="text-decoration-underline text-primary">Iniciar sesión</a></p>
                            </div>
                        </form>
                    </div>
                </div>
                <p class="position-relative text-center fs-12 mb-0"></p>
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
    // Elementos de contraseña y validación
    const passwordInput = document.getElementById("password");
    const confirmPasswordInput = document.getElementById("confirmPassword");
    const passwordStrength = document.getElementById("passwordStrength");
    const submitButton = document.getElementById("btnCambiarP");
    const confirmPasswordError = document.getElementById("confirmPasswordError");

    function checkPasswordStrength(value) {
        if ((value || '').length < 8) {
            passwordStrength.textContent = "La contraseña debe contener más de 8 caracteres";
            if (submitButton) submitButton.disabled = true;
            return false;
        } else {
            passwordStrength.textContent = "";
            return true;
        }
    }

    function checkPasswordsMatch() {
        if (!passwordInput || !confirmPasswordInput) return;
        if (passwordInput.value !== confirmPasswordInput.value) {
            confirmPasswordError.textContent = "Las contraseñas no coinciden";
            if (submitButton) submitButton.disabled = true;
        } else {
            confirmPasswordError.textContent = "";
            if (checkPasswordStrength(passwordInput.value)) {
                if (submitButton) submitButton.disabled = false;
            } else {
                if (submitButton) submitButton.disabled = true;
            }
        }
    }

    if (passwordInput) {
        passwordInput.addEventListener("input", function () {
            checkPasswordStrength(passwordInput.value);
            checkPasswordsMatch();
        });
    }
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener("input", checkPasswordsMatch);
    }

    const params = new URLSearchParams(window.location.search);
    if (params.get('msg') === 'cambiada') {
        if (window.Swal) {
            Swal.fire({
                icon: 'success',
                title: 'Contraseña cambiada',
                text: 'Tu contraseña se actualizó correctamente.',
                confirmButtonText: 'Ir a iniciar sesión'
            }).then(() => {
                window.location.href = 'auth_signin.php';
            });
        }
    } else if (params.get('msg') === 'codigo_ok') {
        if (window.Swal) Swal.fire({ icon: 'info', title: 'Código validado', text: 'Ahora puedes crear tu nueva contraseña.' });
    } else if (params.get('msg') === 'usuario_no_encontrado') {
        if (window.Swal) Swal.fire({ icon: 'error', title: 'Correo no registrado', text: 'Verifica el correo ingresado.' });
    } else if (params.get('msg') === 'error_guardar') {
        if (window.Swal) Swal.fire({ icon: 'error', title: 'Error al guardar', text: 'No se pudo actualizar la contraseña.' });
    }

    document.addEventListener("DOMContentLoaded", function () {
        const passwordForm = document.getElementById('passwordForm');
        const btnCambiarP = document.getElementById('btnCambiarP');

        if (passwordForm && btnCambiarP) {
            passwordForm.addEventListener('submit', function (e) {
                if (!checkPasswordStrength(passwordInput.value) || passwordInput.value !== confirmPasswordInput.value) {
                    e.preventDefault();
                    if (window.Swal) {
                        Swal.fire({ icon: 'warning', title: 'Error', text: 'Verifica la contraseña y su confirmación.' });
                    } else {
                        alert('Verifica la contraseña y su confirmación.');
                    }
                    return;
                }
                btnCambiarP.setAttribute('disabled', 'disabled');
                btnCambiarP.classList.add('disabled');
                btnCambiarP.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Actualizando contraseña...';
            });
        }
    });
</script>


</body>
</html>