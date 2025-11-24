<?php
// controller/c_resep.php

// PENTING: session_start() HARUS menjadi baris kode pertama.
session_start();

// --- 1. INCLUDE MODEL & KONEKSI ---
require_once '../model/m_koneksi.php'; 
require_once '../model/m_data.php';   
require_once '../model/m_resep.php'; 
require_once '../model/m_favorit.php'; 


// --- 2. INISIALISASI MODEL & KONEKSI UNTUK DI ---

$resep_model = new m_resep(); 
$dbConnection = $resep_model->koneksi->koneksi; 

if (!$dbConnection) {
    die("Koneksi database gagal. Cek pengaturan di m_koneksi.php.");
}

// Inisialisasi Model Favorit (Dependency Injection)
$favorit_model = new m_favorit($dbConnection); 


// --- 3. KEAMANAN & SESI ---
if (!isset($_SESSION['id_user'])) {
    header('Location: ../view/Login.php'); 
    exit();
}
$id_user_session = $_SESSION['id_user']; // ID User saat ini


// --- 4. TANGKAP AKSI ---
$aksi = $_GET['aksi'] ?? 'tampil'; 


// --- 5. LOGIKA CONTROLLER BERDASARKAN AKSI ---
switch ($aksi) {
    
    // ==========================================================
    // Aksi 1: LOGOUT
    // ==========================================================
    case 'logout':
        session_unset();
        session_destroy();
        header('Location: ../view/Login.php?status=logout');
        exit();
    break;
    
    // ==========================================================
    // Aksi 2: DETAIL RESEP
    // ==========================================================
    case 'detail':
        $id_resep = $_GET['id'] ?? null;
        
        if ($id_resep) {
            $detail_resep = $resep_model->ambilResepById($id_resep);
            
            if ($detail_resep) {
                // Siapkan data favorit untuk View Detail (jika dibutuhkan)
                $is_favorited = $favorit_model->is_favorited($id_user_session, $id_resep);
                
                include_once '../view/v_detail_resep.php';
            } else {
                header('Location: c_resep.php?error=notfound');
            }
        } else {
            header('Location: c_resep.php?error=noid');
        }
    break;
    
    // ==========================================================
    // Aksi 3: TAMPIL DAFTAR RESEP (Default)
    // ==========================================================
    case 'tampil':
    default:
        // Siapkan data resep
        $daftar_resep = $resep_model->ambilSemuaResepDenganPengunggah();
        
        
        // 1. Menyediakan ID User ke View dengan nama $user_id
        $user_id = $id_user_session; 
        
        // 2. Menyediakan Objek Model Favorit ke View dengan nama $favorite_model
        $favorite_model = $favorit_model; 

        // Muat View Daftar
        // View sekarang dapat mengakses: $daftar_resep, $user_id, dan $favorite_model
        include_once '../view/v_tampil_resep.php';
    break;
}

















