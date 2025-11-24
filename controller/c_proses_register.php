<?php


session_start();


require_once '../model/m_koneksi.php'; 
require_once '../model/m_user.php'; 


$koneksi_obj = new m_koneksi();
$dbConnection = $koneksi_obj->koneksi; 

if (!$dbConnection) {
    die("Koneksi database gagal. Cek pengaturan di m_koneksi.php.");
}

$user_model = new m_user($dbConnection);



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? null;
    $password_input = $_POST['password'] ?? null;
    $role = 'user'; 

  
    if (empty($username) || empty($password_input)) {
        header('Location: ../view/v_register.php?error=incomplete');
        exit;
    }

    $password_hash = password_hash($password_input, PASSWORD_DEFAULT);
    $success = $user_model->tambah_data($username, $password_hash, $role);

    if ($success) {
       
        echo "<script>alert('Pendaftaran berhasil! Silakan login.');window.location='../view/Login.php?status=register_success'</script>";
    } else {
       
        echo "<script>alert('Pendaftaran gagal! Username mungkin sudah terdaftar atau terjadi kesalahan database.');window.location='../view/v_register.php?error=duplicate'</script>";
    }
    exit;

} else {
    
    header('Location: ../view/v_register.php');
    exit;
}
?>