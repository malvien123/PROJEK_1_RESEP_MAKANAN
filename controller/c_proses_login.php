<?php

// controller/c_proses_login.php

session_start();

require_once '../model/m_koneksi.php'; 
require_once '../model/m_user.php'; 

// --- Inisialisasi Koneksi ---
$koneksi_obj = new m_koneksi();
$dbConnection = $koneksi_obj->koneksi;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Inisialisasi Model dengan Koneksi DB
    $user_model = new m_user($dbConnection);
    $user_data = $user_model->verify_login($username, $password);

    if ($user_data) {
        // Login BERHASIL: Simpan data ke SESSION (menggunakan array access)
        $_SESSION['id_user'] = $user_data['id_user'];
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['role'] = $user_data['role']; 
        $_SESSION['status'] = 'login'; 
        
        if ($user_data['role'] === 'admin') {
            // Arahkan ke Controller CRUD Admin
            header('Location: c_user.php'); 
        } else {
            // Arahkan ke halaman User/Resep
            header('Location: c_resep.php'); 
        }
        exit();
    } else {
        // Login GAGAL
        header('Location: ../view/Login.php?error=invalid_credentials');
        exit();
    }
} else {
    header('Location: ../view/Login.php');
    exit();
}
// JANGAN ADA TAG PENUTUP 