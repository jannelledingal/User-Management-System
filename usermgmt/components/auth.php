<?php
session_start();

function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}

function adminOnly() {
    checkLogin();
    if ($_SESSION['role'] !== 'admin') {
        header("Location: profile.php?error=access_denied");
        exit;
    }
}
?>