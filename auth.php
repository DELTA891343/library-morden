<?php
session_start();

// Authentication middleware
function requireLogin() {
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit();
    }
}

function requireAdmin() {
    requireLogin();
    if ($_SESSION['user']['role'] !== 'admin') {
        header("Location: dashboard.php");
        exit();
    }
}

function isLoggedIn() {
    return isset($_SESSION['user']);
}

function logout() {
    session_start();
    session_destroy();
    header("Location: login.php");
    exit();
}
?>