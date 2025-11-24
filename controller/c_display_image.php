<?php
include_once '../model/m_koneksi.php'; 
$koneksi = new m_koneksi();
$conn = $koneksi->koneksi;

// Fungsi untuk menangani kegagalan: Keluar tanpa output apa pun
function handle_image_error() {
    // Anda bisa mengarahkan ke gambar placeholder jika ada, atau sekadar keluar
    // header("Location: ../asset/placeholder.png"); 
    exit();
}

if (isset($_GET['id_resep'])) {
    $id_resep = $_GET['id_resep']; // Ambil ID tanpa sanitasi awal
    
    // --- PENGGUNAAN PREPARED STATEMENT UNTUK KEAMANAN DAN STABILITAS ---
    $sql = "SELECT gambar, tipe_gambar FROM resep WHERE id_resep = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        handle_image_error();
    }
    
    // Bind parameter: 'i' untuk integer (ID)
    $stmt->bind_param("i", $id_resep);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
    // --- AKHIR PREPARED STATEMENT ---

    if ($data && !empty($data['gambar'])) {
        $gambar_blob = $data['gambar'];
        $mime_type = $data['tipe_gambar']; 
        

        
        // --- PERBAIKAN PENTING DI SINI ---
        
        // 1. Standarisasi Tipe MIME yang tidak lengkap
        // Jika data dari DB hanya 'jpeg' atau 'png', tambahkan 'image/'
        if ($mime_type == 'jpeg' || $mime_type == 'jpg') {
             $mime_type = 'image/jpeg';
        } elseif ($mime_type == 'png') {
             $mime_type = 'image/png';
        } elseif (empty($mime_type)) {
             // Jika kolom kosong (data lama), asumsikan JPEG
             $mime_type = 'image/jpeg'; 
        }

        // 2. Mengatur Header HTTP agar browser tahu ini adalah gambar
        header("Content-Type: " . $mime_type);
        // header("Content-Length: " . strlen($gambar_blob)); 
        
        // Mencetak data biner gambar
        echo $gambar_blob;
        exit();
    }
// ...
    }


// Keluar jika ID tidak ada, query gagal, atau gambar kosong
handle_image_error();
