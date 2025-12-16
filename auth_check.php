<?php
session_start();

if (!isset($_SESSION['rol'])) {
    header("Location: auth_signin.php");
    exit;
}

/**
 *
 * @param array $rolesPermitidos
 */
function protegerRuta(array $rolesPermitidos) {
    if (!in_array($_SESSION['rol'], $rolesPermitidos)) {
        http_response_code(403);
        require "403.php";
        exit;
    }
}
