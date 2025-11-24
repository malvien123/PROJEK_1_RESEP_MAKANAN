<?php
// index.php

// 1. Mulai Sesi
session_start();

// 2. Logika Pengecekan Sesi dan Pengarahan
// Cek apakah user sudah login dan status sesi adalah 'login'
if (isset($_SESSION['id_user']) && ($_SESSION['status'] ?? null) === 'login') {
    
    $role = $_SESSION['role'] ?? 'user'; // Ambil role dari sesi

    if ($role === 'admin') {
        // Jika Admin, arahkan ke Controller CRUD Admin
        // Path ke Controller harus relatif dari index.php (Controller ada di folder 'controller')
        header('Location: controller/c_user.php'); 
    } else {
        // Jika User, arahkan ke Controller Resep utama
        header('Location: controller/c_resep.php?aksi=tampil'); 
    }
    exit();
} else {
    // Jika user BELUM login atau sesi sudah habis/invalid
    
    // Hancurkan sesi yang mungkin rusak (opsional, untuk kebersihan)
    session_unset();
    session_destroy();

    // Arahkan ke Form Login di folder view
    header('Location: view/Login.php');
    exit();
}
?>