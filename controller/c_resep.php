<?php

session_start();


require_once '../model/m_koneksi.php'; 
require_once '../model/m_data.php';   
require_once '../model/m_resep.php'; 
require_once '../model/m_favorit.php'; 




$resep_model = new m_resep(); 
$dbConnection = $resep_model->koneksi->koneksi; 

if (!$dbConnection) {
    die("Koneksi database gagal. Cek pengaturan di m_koneksi.php.");
}


$favorit_model = new m_favorit($dbConnection); 



if (!isset($_SESSION['id_user'])) {
    header('Location: ../view/Login.php'); 
    exit();
}
$id_user_session = $_SESSION['id_user']; 


$aksi = $_GET['aksi'] ?? 'tampil'; 



switch ($aksi) {
    
    
    case 'logout':
        session_unset();
        session_destroy();
        header('Location: ../view/Login.php?status=logout');
        exit();
    break;
    
    case 'detail':
        $id_resep = $_GET['id'] ?? null;
        
        if ($id_resep) {
            $detail_resep = $resep_model->ambilResepById($id_resep);
            
            if ($detail_resep) {
                
                $is_favorited = $favorit_model->is_favorited($id_user_session, $id_resep);
                
                include_once '../view/v_detail_resep.php';
            } else {
                header('Location: c_resep.php?error=notfound');
            }
        } else {
            header('Location: c_resep.php?error=noid');
        }
    break;
    
    case 'tampil':
    default:
        // Siapkan data resep
        $daftar_resep = $resep_model->ambilSemuaResepDenganPengunggah();
        
        
        
        $user_id = $id_user_session; 
        
        
        $favorite_model = $favorit_model; 

        include_once '../view/v_tampil_resep.php';
    break;
}

















