<?php

session_start(); 

require_once '../model/m_koneksi.php'; 
require_once '../model/m_favorit.php'; 


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

   
    public function toggle_favorite() {
        $resep_id = $_POST['id_resep'] ?? null;
        $action = $_POST['action'] ?? null; 
        $user_id = $_SESSION['id_user'] ?? null; 

        
        if (!$user_id) {
            header('Location: ../view/Login.php?error=session_expired');
            exit;
        }

        if (!$resep_id || !in_array($action, ['add', 'remove'])) {
            header('Location: c_resep.php?aksi=tampil&error=data_missing'); 
            exit;
        }

        if ($action === 'add') {
            $this->m_favorit->add_favorite($user_id, $resep_id);
        } elseif ($action === 'remove') {
            $this->m_favorit->remove_favorite($user_id, $resep_id);
        }

        
        $referer = $_SERVER['HTTP_REFERER'] ?? 'c_resep.php?aksi=tampil';
        $separator = strpos($referer, '?') !== false ? '&' : '?';

  
        header("Location: " . $referer . $separator . "status=favorite_{$action}_success");
        exit;
        // ---------------------------------------------------------------------
    }
    
   
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


if ($dbConnection) {
    $controller = new c_favorit($dbConnection);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_resep'])) {
        $controller->toggle_favorite();
    } 
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