<?php
include("conexion.php");
?> 
<!-- begin::App -->
<div id="layout-wrapper">
    <!-- Begin Header -->
    <header class="app-header" id="appHeader">
        <div class="container-fluid w-100">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <div class="d-inline-flex align-items-center gap-5">
                        <a href="index.html" class="fs-18 fw-semibold">
                            <img height="30" class="header-sidebar-logo-default d-none" alt="Logo" src="assets/images/logo-dark.png">
                            <img height="30" class="header-sidebar-logo-light d-none" alt="Logo" src="assets/images/logo-light.png">
                            <img height="30" class="header-sidebar-logo-small d-none" alt="Logo" src="assets/images/logo-md.png">
                            <img height="30" class="header-sidebar-logo-small-light d-none" alt="Logo" src="../assets/images/logo-md-light.png">
                        </a>
                        <button type="button" class="vertical-toggle btn btn-light-light text-muted icon-btn fs-5 rounded-pill" id="toggleSidebar">
                            <i class="bi bi-arrow-bar-left header-icon"></i>
                        </button>
                        <button type="button" class="horizontal-toggle btn btn-light-light text-muted icon-btn fs-5 rounded-pill d-none" id="toggleHorizontal">
                            <i class="ri-menu-2-line header-icon"></i>
                        </button>
                    </div>
                </div>
                <div class="flex-shrink-0 d-flex align-items-center gap-1">
                    <div class="dropdown pe-dropdown-mega d-none d-md-block">
                        <button class="header-profile-btn btn gap-1 text-start" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="header-btn btn position-relative">
                                <img src="<?= isset($_SESSION['avatar']) && !empty($_SESSION['avatar']) ? htmlspecialchars($_SESSION['avatar']) : 'assets/images/avatar/avatar-default.png' ?>" alt="Avatar" class="img-fluid rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                <span class="position-absolute translate-middle badge border border-light rounded-circle bg-success"><span class="visually-hidden">unread messages</span></span>
                            </span>
                            <div class="d-none d-lg-block pe-2">
                                <span class="d-block mb-0 fs-13 fw-semibold"><?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Cliente') { echo htmlspecialchars($_SESSION['nombre']); }else{echo htmlspecialchars($_SESSION['nombre_completo']);} ?></span>
                                <span class="d-block mb-0 fs-12 text-muted"><?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'Cliente') { echo htmlspecialchars($_SESSION['razon']); }else{ echo htmlspecialchars(string: $_SESSION['rol']);}?></span>
                            </div>
                        </button>
                        <div class="dropdown-menu dropdown-mega-sm header-dropdown-menu p-3">
                            <div class="border-bottom pb-2 mb-2 d-flex align-items-center gap-2">
                                <img src="<?= isset($_SESSION['avatar']) ? htmlspecialchars($_SESSION['avatar']) : 'assets\images\avatar\avatar-default.png' ?>" alt="" class="avatar-md">
                                <div>
                                    <a href="configuracion_perfil.php">
                                        <h6 class="mb-0 lh-base"><?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Cliente') { echo htmlspecialchars($_SESSION['nombre']); }else{echo htmlspecialchars($_SESSION['nombre_completo']);} ?></h6>
                                    </a>
                                    <p class="mb-0 fs-13 text-muted"><?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'Invitado'; ?></p>
                                </div>
                            </div>
                            <ul class="list-unstyled mb-1 border-bottom pb-1">
                                <li><a class="dropdown-item" href="configuracion_perfil.php"><i class="ri-pencil-line me-1"></i> Editar perfil</a></li>
                                <?php if($_SESSION['rol'] === 'Cliente'): ?>
                                    <li><a class="dropdown-item" href="mistickets.php"><i class="ri-ticket-2-line me-1"></i> Mis Tickets</a></li>
                                <?php endif;?>
                                <?php if($_SESSION['rol'] === 'Soporte'):?>
                                    <li><a class="dropdown-item" href="tickets_asignados.php"><i class="ri-ticket-2-line me-1"></i>Tickets asignados</a></li>
                                <?php endif;?>
                                <?php if($_SESSION['rol'] === 'Administrador'):?>
                                    <li><a class="dropdown-item" href="apps-project-list.php"><i class="ri-archive-2-line me-1"></i>Historial</a></li>
                                <?php endif;?>
                            </ul>
                            <ul class="list-unstyled mb-0">
                                <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-right me-1"></i> Cerrar Sesión</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- END Header -->
    <aside class="pe-app-sidebar" id="sidebar">
        <div class="pe-app-sidebar-logo px-6 d-flex align-items-center position-relative">
            <!--begin::Brand Image-->
            <a href="dashboard_prueba.php" class="fs-18 fw-semibold">
                <img height="30" class="pe-app-sidebar-logo-default d-none" alt="Logo" src="assets/images/logo-dark.png">
                <img height="30" class="pe-app-sidebar-logo-light d-none" alt="Logo" src="assets/images/logo-light.png">
                <img height="30" class="pe-app-sidebar-logo-minimize d-none" alt="Logo" src="assets/images/logo-md.png">
                <img height="30" class="pe-app-sidebar-logo-minimize-light d-none" alt="Logo" src="assets/images/logo-md-light.png">
                <!-- FabKin -->
            </a>
            <!--end::Brand Image-->
        </div>
        <nav class="pe-app-sidebar-menu nav nav-pills">
            <ul class="pe-horizontal-menu list-unstyled" id="horizontal-menu">
                <li class="pe-menu-title">
                    Menu
                </li>
                <ul class="pe-main-menu list-unstyled">
                    <li class="pe-slide pe-has-sub">
                        <a href="dashboard_prueba.php" class="pe-nav-link">
                            <i class="ri-home-4-line pe-nav-icon"></i>
                            <span class="pe-nav-content">Pagina principal</span>
                        </a>
                    </li>
                    <?php if($_SESSION['rol'] === 'Cliente'): ?>
                    <li class="pe-slide pe-has-sub">
                        <a href="mistickets.php" class="pe-nav-link">
                            <i class="ri-coupon-fill pe-nav-icon" ></i>
                            <span class="pe-nav-content">Mis tickets</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <li class="pe-slide pe-has-sub">
                        <a href="apps-project-create.php" class="pe-nav-link">
                            <i class="ri-ticket-line pe-nav-icon"></i>
                            <span class="pe-nav-content">Crear ticket</span>
                        </a>
                    </li>
            <?php if(isset($_SESSION['rol']) && ($_SESSION['rol'] === 'Administrador' || $_SESSION['rol'] === 'Soporte')): ?>
                <ul class="pe-horizontal-menu list-unstyled" id="horizontal-menu">
                    <li class="pe-menu-title">
                        Panel de control
                    </li>
                    <?php if($_SESSION['rol'] === 'Soporte'): ?>
                    <li class="pe-slide pe-has-sub">
                        <a href="tickets_asignados.php" class="pe-nav-link">
                            <i class="ri-ticket-2-line pe-nav-icon"></i>
                            <span class="pe-nav-content">Tickets asignados</span>
                        </a>
                    </li>
                    <?php endif;?>
                    <li class="pe-slide pe-has-sub">
                        <a href="apps-crm-contact.php" class="pe-nav-link">
                            <i class="ri-user-line pe-nav-icon"></i>
                            <span class="pe-nav-content">Clientes</span>
                        </a>
                    </li>
                    <?php if($_SESSION['rol'] === 'Administrador'): ?>
                    <li class="pe-slide pe-has-sub">
                        <a href="apps-project-list.php" class="pe-nav-link">
                            <i class="ri-archive-2-line pe-nav-icon"></i>
                            <span class="pe-nav-content">Historial</span>
                        </a>
                    </li>
                    <li class="pe-slide pe-has-sub">
                        <a href="listadeusuarios.php" class="pe-nav-link">
                            <i class="ri-user-2-line pe-nav-icon"></i>
                            <span class="pe-nav-content">Usuarios</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
            </ul>
        </nav>
    </aside>
    <aside class="pe-app-sidebar horizontal-sidebar" id="horizontal-aside">
        <div class="pe-app-sidebar-logo px-6 d-flex align-items-center position-relative">
            <!--begin::Brand Image-->
            <a href="dashboard_prueba.php" class="fs-18 fw-semibold">
                <img height="30" class="pe-app-sidebar-logo-default d-none" alt="Logo" src="assets/images/logo-dark.png">
                <img height="30" class="pe-app-sidebar-logo-light d-none" alt="Logo" src="assets/images/logo-light.png">
                <img height="30" class="pe-app-sidebar-logo-minimize d-none" alt="Logo" src="assets/images/logo-md.png">
                <img height="30" class="pe-app-sidebar-logo-minimize-light d-none" alt="Logo" src="assets/images/logo-md-light.png">
                <!-- FabKin -->
            </a>
            <!--end::Brand Image-->
        </div>
        <!-- data-simplebar id="sidebar-simplebar" -->
        <nav class="pe-app-sidebar-menu nav nav-pills">
            <ul class="pe-horizontal-menu list-unstyled" id="horizontal-menu">
                <li class="pe-menu-title">
                    Menu
                </li>
                <li class="pe-slide pe-has-sub">
                    <a href="dashboard_prueba.php" class="pe-nav-link">
                        <i class="ri-home-4-line"></i>
                        <span class="pe-nav-content">Pagina principal</span>
                    </a>
                </li>
                <?php if($_SESSION['rol'] === 'Clientes'): ?>
                <li class="pe-slide pe-has-sub">
                    <a href="mistickets.php" class="pe-nav-link">
                        <i class="ri-coupon-line"></i>
                        <span class="pe-nav-content">Mis tickets</span>
                    </a>
                </li>
                <?php endif;?>
                <?php if(isset($_SESSION['rol']) && ($_SESSION['rol'] === 'Administrador' || $_SESSION['rol'] === 'Cliente')): ?>
                <li class="pe-slide pe-has-sub">
                    <a href="apps-project-create.html" class="pe-nav-link">
                        Crear un ticket
                    </a>
                </li>
                <?php endif; ?>
            <?php if(isset($_SESSION['rol']) && ($_SESSION['rol'] === 'Administrador' || $_SESSION['rol'] === 'Soporte')): ?>
                <ul class="pe-horizontal-menu list-unstyled" id="horizontal-menu">
                    <li class="pe-menu-title">
                        Panel de control
                    </li>
                    <?php if($_SESSION['rol'] === 'Soporte'): ?>
                    <li class="pe-slide pe-has-sub">
                        <a href="tickets_asignados.php" class="pe-nav-link">
                            <i class="ri-ticket-2-line"></i>
                            <span class="pe-nav-content">Tickets asignados</span>
                        </a>
                    </li>
                    <?php endif;?>
                    <li class="pe-slide pe-has-sub">
                        <a href="apps-crm-contact.php" class="pe-nav-link">
                            <i class="ri-user-line pe-nav-icon"></i>
                            <span class="pe-nav-content">Clientes</span>
                        </a>
                    </li>
                    <?php if($_SESSION['rol'] === 'Administrador'): ?>
                    <li class="pe-slide pe-has-sub">
                        <a href="apps-project-list.php" class="pe-nav-link">
                            <i class="ri-archive-2-line pe-nav-icon"></i>
                            <span class="pe-nav-content">Historial</span>
                        </a>
                    </li>
                    <li class="pe-slide pe-has-sub">
                        <a href="listadeusuarios.php" class="pe-nav-link">
                            <i class="ri-user-2-line"></i>
                            <span class="pe-nav-content">Usuarios</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
            </ul>
        </nav>
    </aside>
</div>