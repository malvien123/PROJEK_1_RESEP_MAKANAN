<?php
// controller/c_favorit.php

session_start(); 

// --- 1. INCLUDE MODEL & KONEKSI ---
require_once '../model/m_koneksi.php'; 
require_once '../model/m_favorit.php'; 

// 2. INISIALISASI KONEKSI
$koneksi_obj = new m_koneksi(); 
$dbConnection = $koneksi_obj->koneksi; 

class c_favorit {
    private $m_favorit;

    public function __construct($dbConnection) {
        if (!$dbConnection) {
            die("Error: Objek koneksi database tidak valid.");
        }
        $this->m_favorit = new m_favorit($dbConnection);
    }

    // A. METHOD: MENGELOLA TAMBAH/HAPUS FAVORIT
    public function toggle_favorite() {
        $resep_id = $_POST['id_resep'] ?? null;
        $action = $_POST['action'] ?? null; 
        $user_id = $_SESSION['id_user'] ?? null; 

        // 1. PENGAMANAN Sesi
        if (!$user_id) {
            header('Location: ../view/Login.php?error=session_expired');
            exit;
        }

        // 2. Validasi Data
        if (!$resep_id || !in_array($action, ['add', 'remove'])) {
            // Menggunakan path relatif c_resep.php karena berada di folder yang sama
            header('Location: c_resep.php?aksi=tampil&error=data_missing'); 
            exit;
        }

        // 3. Eksekusi Aksi Model
        if ($action === 'add') {
            $this->m_favorit->add_favorite($user_id, $resep_id);
        } elseif ($action === 'remove') {
            $this->m_favorit->remove_favorite($user_id, $resep_id);
        }

        // ---------------------------------------------------------------------
        // 🔥 PERBAIKAN KRUSIAL: LOGIKA REDIRECT
        // ---------------------------------------------------------------------
        
        $referer = $_SERVER['HTTP_REFERER'] ?? 'c_resep.php?aksi=tampil';
        
        // Cek apakah $referer sudah memiliki query parameter ('?')
        // Jika ya, gunakan '&' sebagai pemisah. Jika tidak, gunakan '?'
        $separator = strpos($referer, '?') !== false ? '&' : '?';

        // Lakukan redirect dengan separator yang benar
        header("Location: " . $referer . $separator . "status=favorite_{$action}_success");
        exit;
        // ---------------------------------------------------------------------
    }
    
    // B. METHOD: MENAMPILKAN DAFTAR FAVORIT
    public function show_favorites() {
        $user_id = $_SESSION['id_user'] ?? null;

        if (!$user_id) {
            header('Location: ../view/Login.php?error=relogin_favorit');
            exit;
        }

        // Variabel yang akan digunakan di View
        $data_resep_favorit = $this->m_favorit->get_user_favorites($user_id);
        
        // Muat View
        include '../view/v_daftar_favorit.php';
    }
}


// ============== LOGIKA ROUTING DASAR ==============
if ($dbConnection) {
    $controller = new c_favorit($dbConnection);
    
    // Aksi POST: Tambah/Hapus Favorit
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_resep'])) {
        $controller->toggle_favorite();
    } 
    // Aksi GET: Tampilkan Daftar Favorit
    elseif (isset($_GET['action']) && $_GET['action'] == 'show') {
        $controller->show_favorites();
    }
    else {
        // Jika diakses tanpa aksi yang valid, arahkan ke daftar resep
        header('Location: c_resep.php?aksi=tampil');
        exit;
    }
} else {
    // Koneksi gagal
    die("Koneksi database gagal. Cek pengaturan di m_koneksi.php.");
}
?>