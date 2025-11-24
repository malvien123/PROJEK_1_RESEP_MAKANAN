<?php
// controller/c_proses_register.php

session_start();

// Import Model dan Koneksi
require_once '../model/m_koneksi.php'; 
require_once '../model/m_user.php'; 

// --- 1. Inisialisasi Koneksi dan Model ---
$koneksi_obj = new m_koneksi();
$dbConnection = $koneksi_obj->koneksi; 

if (!$dbConnection) {
    die("Koneksi database gagal. Cek pengaturan di m_koneksi.php.");
}

$user_model = new m_user($dbConnection);

// ============== LOGIKA PROSES REGISTRASI USER BARU ==============

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? null;
    $password_input = $_POST['password'] ?? null;
    $role = 'user'; // Role default untuk pendaftar baru

    // Validasi dasar
    if (empty($username) || empty($password_input)) {
        header('Location: ../view/v_register.php?error=incomplete');
        exit;
    }

    // 🔥 BARIS KRUSIAL: HASHING PASSWORD SEBELUM DISIMPAN
    $password_hash = password_hash($password_input, PASSWORD_DEFAULT);
    
    // Panggil Model untuk menyimpan data
    // Model yang dipanggil adalah tambah_data($username, $password_hash, $role)
    $success = $user_model->tambah_data($username, $password_hash, $role);

    if ($success) {
        // Redirect ke halaman Login setelah berhasil daftar
        echo "<script>alert('Pendaftaran berhasil! Silakan login.');window.location='../view/Login.php?status=register_success'</script>";
    } else {
        // Handle error (biasanya username duplikat atau error DB)
        echo "<script>alert('Pendaftaran gagal! Username mungkin sudah terdaftar atau terjadi kesalahan database.');window.location='../view/v_register.php?error=duplicate'</script>";
    }
    exit;

} else {
    // Jika diakses tanpa POST, kembalikan ke form register
    header('Location: ../view/v_register.php');
    exit;
}
?>