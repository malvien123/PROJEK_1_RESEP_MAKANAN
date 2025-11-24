<?php
include_once '../model/m_koneksi.php'; 
$koneksi = new m_koneksi();
$conn = $koneksi->koneksi;


function handle_image_error() {
    exit();
}

if (isset($_GET['id_resep'])) {
    $id_resep = $_GET['id_resep']; 
    
  
    $sql = "SELECT gambar, tipe_gambar FROM resep WHERE id_resep = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        handle_image_error();
    }
    
    $stmt->bind_param("i", $id_resep);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
   

    if ($data && !empty($data['gambar'])) {
        $gambar_blob = $data['gambar'];
        $mime_type = $data['tipe_gambar']; 
        

        
        if ($mime_type == 'jpeg' || $mime_type == 'jpg') {
             $mime_type = 'image/jpeg';
        } elseif ($mime_type == 'png') {
             $mime_type = 'image/png';
        } elseif (empty($mime_type)) {
             $mime_type = 'image/jpeg'; 
        }
        header("Content-Type: " . $mime_type);
        echo $gambar_blob;
        exit();
    }
    }


// Keluar jika ID tidak ada, query gagal, atau gambar kosong
handle_image_error();
