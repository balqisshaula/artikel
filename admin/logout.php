<?php
require_once 'auth.php';

// Pastikan user sudah login
if (!isLoggedIn()) {
    header('Location: ../index.php');
    exit();
}

// Lakukan logout
logout();
