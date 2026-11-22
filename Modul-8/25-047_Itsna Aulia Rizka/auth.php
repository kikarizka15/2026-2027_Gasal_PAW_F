<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function redirectIfLoggedIn(): void {
    if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
        header("Location: index.php");
        exit;
    }
}

function requireLogin(): void {
    if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        header("Location: login.php?error=belum_login");
        exit;
    }
}

function requireOwner(): void {
    requireLogin();
    if (!isset($_SESSION['level']) || (int)$_SESSION['level'] !== 1) {
        header("Location: index.php?error=akses_ditolak");
        exit;
    }
}
