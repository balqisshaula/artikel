<?php
session_start();

// Fungsi untuk mengecek apakah user sudah login
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Fungsi untuk mengecek role user
function hasRole($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

// Fungsi untuk redirect jika tidak memiliki akses
function requireRole($role, $redirectTo = '../index.php') {
    if (!isLoggedIn() || !hasRole($role)) {
        header('Location: ' . $redirectTo);
        exit();
    }
}

// Fungsi untuk logout
function logout() {
    session_unset();
    session_destroy();
    header('Location: ../index.php');
    exit();
}

// Fungsi untuk mendapatkan informasi user saat ini
function getCurrentUser() {
    if (isLoggedIn()) {
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'role' => $_SESSION['role']
        ];
    }
    return null;
}
?>