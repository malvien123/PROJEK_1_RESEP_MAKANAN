<?php
session_start();

// Cek apakah aksi=logout sudah diterima (dari klik OK di JavaScript)
if (isset($_GET['aksi']) && $_GET['aksi'] == 'logout') {
    
    // Hancurkan Sesi
    session_unset();
    session_destroy();

    // Redirect ke halaman Login dengan notif sukses
    // Pastikan path ke Login.php sudah benar.
    header('Location: ../view/Login.php?status=logout_sukses');
    exit();
}

// Jika tidak ada parameter yang sesuai atau diakses tanpa klik tombol, 
// kembalikan ke halaman login atau dashboard awal.
header('Location: ../view/Login.php'); 
exit();
?>
