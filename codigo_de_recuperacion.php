<?php 
include("conexion.php");
include("Validar_codigo.php");
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
                        <img src="assets/images/auth/email.png" alt="" class="h-56px w-56px mx-auto d-block mb-3">
                        <h3 class="mb-2 text-center text-capitalize">Recuperar Contraseña</h3>
                        <p class="text-muted text-center">Ingresa el código de 6 dígitos enviado a tu correo <span class="fw-bold"><?php echo $email ?: 'tu correo'; ?></span></p>
                        <form method="POST" action="" id="otpForm">
                          <input type="hidden" name="email" value="<?php echo $email; ?>">
                            <input type="hidden" name="codigo" id="codigo">
                            <div id="otp-container" class="d-flex align-items-center justify-content-center gap-2">
                              <input type="text" class="form-control text-center min-h-50px border-0 border-bottom border-2 rounded-0" placeholder="0" data-otp-input maxlength="1" inputmode="numeric" pattern="[0-9]" aria-label="OTP digit 1">
                              <input type="text" class="form-control text-center min-h-50px border-0 border-bottom border-2 rounded-0"
                                placeholder="0" data-otp-input maxlength="1" inputmode="numeric" pattern="[0-9]" aria-label="OTP digit 2">
                              <input type="text" class="form-control text-center min-h-50px border-0 border-bottom border-2 rounded-0"
                                placeholder="0" data-otp-input maxlength="1" inputmode="numeric" pattern="[0-9]" aria-label="OTP digit 3">
                              <input type="text" class="form-control text-center min-h-50px border-0 border-bottom border-2 rounded-0"
                                placeholder="0" data-otp-input maxlength="1" inputmode="numeric" pattern="[0-9]" aria-label="OTP digit 4">
                            <input type="text" class="form-control text-center min-h-50px border-0 border-bottom border-2 rounded-0"
                              placeholder="0" data-otp-input maxlength="1" inputmode="numeric" pattern="[0-9]" aria-label="OTP digit 5">
                            <input type="text" class="form-control text-center min-h-50px border-0 border-bottom border-2 rounded-0"
                                placeholder="0" data-otp-input maxlength="1" inputmode="numeric" pattern="[0-9]" aria-label="OTP digit 6">
                            </div>
                            <button type="submit" class="btn btn-primary rounded-2 w-100 btn-loader text-center mt-10" id="btnValidar">
                              <span class="spinner-border spinner-border-sm me-2 d-none" id="spinnervalidar"></span>Recuperar Contraseña
                            </button>
                          </form>
                          <?php if (!empty($email)) : ?>
                          <form method="POST" action="Recuperar.php" class="mt-3" id="resendForm">
                            <input type="hidden" name="email" value="<?php echo $email; ?>">
                            <button type="submit" class="btn btn-outline-primary rounded-2 w-100" id="btnReenviar">
                              <span class="spinner-border spinner-border-sm me-2 d-none" id="spinnerreenviar"></span>Reenviar código
                            </button>
                            <p class="text-center mb-0 mt-3">
                              <a href="auth_signin.php" class=" text-body fw-medium fs-12">Regresar al inicio</a>
                            </p>
                          </form>
                          <?php endif; ?>
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
<script src="assets/js/auth/auth.init.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Combinar dígitos y enviar con seguridad
const otpForm = document.getElementById('otpForm');
if (otpForm) {
  // Auto-avance y restricción a un dígito numérico
  const inputs = document.querySelectorAll('[data-otp-input]');
  inputs.forEach((input, idx) => {
    input.addEventListener('input', (e) => {
      input.value = input.value.replace(/\D/g, '').slice(0, 1);
      if (input.value && inputs[idx + 1]) inputs[idx + 1].focus();
    });
    input.addEventListener('keydown', (e) => {
      if (e.key === 'Backspace' && !input.value && inputs[idx - 1]) {
        inputs[idx - 1].focus();
      }
    });
  });

  otpForm.addEventListener('submit', function(e) {
    const inputs = document.querySelectorAll('[data-otp-input]');
    let code = '';
    inputs.forEach(input => { code += (input.value || '').trim(); });
    document.getElementById('codigo').value = code;
  });
}

// Mensajes
const params = new URLSearchParams(window.location.search);
if (params.get('msg') === 'enviado') {
  Swal.fire({ icon: 'info', title: 'Código enviado', text: 'Revisa tu correo para el código.' });
} else if (params.get('msg') === 'error_codigo') {
  Swal.fire({ icon: 'error', title: 'Código inválido o expirado', text: 'Intenta nuevamente.' });
} else if (params.get('msg') === 'usuario_no_encontrado') {
  Swal.fire({ icon: 'error', title: 'Correo no registrado', text: 'Verifica el correo ingresado.' });
}
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const otpForm = document.getElementById('otpForm');
    const btnValidar = document.getElementById('btnValidar');

    if (otpForm && btnValidar) {
        otpForm.addEventListener('submit', function (e) {
            const inputs = document.querySelectorAll('[data-otp-input]');
            let code = '';
            inputs.forEach(input => { code += (input.value || '').trim(); });
            if (code.length < 6) {
                e.preventDefault();
                if (window.Swal) {
                    Swal.fire({ icon: 'warning', title: 'Código incompleto', text: 'Ingresa los 6 dígitos.' });
                } else {
                    alert('Ingresa los 6 dígitos.');
                }
                return;
            }
            btnValidar.setAttribute('disabled', 'disabled');
            btnValidar.classList.add('disabled');
            btnValidar.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Validando código...';
        });
    }
    const resendForm = document.getElementById('resendForm');
    const btnReenviar = document.getElementById('btnReenviar');

    if (resendForm && btnReenviar) {
        resendForm.addEventListener('submit', function () {
            btnReenviar.setAttribute('disabled', 'disabled');
            btnReenviar.classList.add('disabled');
            btnReenviar.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Reenviando código...';
        });
    }
});
</script>

</body>
</html>