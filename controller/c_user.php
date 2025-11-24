<?php

session_start();

require_once '../model/m_koneksi.php'; 
require_once '../model/m_user.php'; 


$koneksi_obj = new m_koneksi();
$dbConnection = $koneksi_obj->koneksi; 

// Cek koneksi
if (!$dbConnection) {
    die("Koneksi database gagal. Cek pengaturan di m_koneksi.php.");
}

$user_model = new m_user($dbConnection);


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
   
    header('Location: ../view/Login.php?error=akses_admin'); 
    exit();
}




$aksi = $_GET['aksi'] ?? 'tampil'; 
$id = $_GET['id'] ?? null;

if ($aksi === 'tampil') {
    
    $users = $user_model->tampil_data();
    include '../view/v_tampil_user.php'; 
    
} elseif ($aksi === 'edit' && $id) {
    $users = $user_model->tampil_data_by_id($id);
    include_once '../view/v_update_user.php';
    
} elseif ($aksi === 'hapus' && $id) {
  
    $result = $user_model->hapus_data($id);
    $pesan = $result ? 'dihapus' : 'gagal dihapus';
    
    echo "<script>alert('Data berhasil $pesan');window.location='c_user.php'</script>";

} elseif ($aksi === 'logout') {
   
    session_unset();
    session_destroy();
    header('Location: ../view/Login.php');
    exit;

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && ($aksi === 'tambah' || $aksi === 'update')) {
    
    
    
    
    $id_user = $_POST['id_user'] ?? null;
    $username = $_POST['username'] ?? '';
    $input_password = $_POST['password'] ?? null; 
    $role = $_POST['role'] ?? 'user';
    
  
    $pass_hash = $input_password ? password_hash($input_password, PASSWORD_DEFAULT) : null;
    $query_result = false;
    
    if ($aksi === 'tambah') {
      
        
        if ($pass_hash === null) {
            echo "<script>alert('Password wajib diisi saat menambah pengguna!');window.location='../view/v_tambah_user.php'</script>";
            exit();
        }
        $query_result = $user_model->tambah_data($username, $pass_hash, $role);
        $pesan_aksi = $query_result ? 'ditambahkan' : 'gagal ditambahkan';

    } elseif ($aksi === 'update') {
        
        $query_result = $user_model->ubah_data($id_user, $username, $pass_hash); 
        $pesan_aksi = $query_result ? 'diperbarui' : 'gagal diperbarui';
    }

    // Feedback dan Redirect
    if ($query_result) {
        echo "<script>alert('Data berhasil $pesan_aksi');window.location='c_user.php'</script>";
    } else {
        echo "<script>alert('Data gagal $pesan_aksi');window.location='c_user.php'</script>";
    }
    
    exit();

} else {
   
    $users = $user_model->tampil_data();
    include '../view/v_tampil_user.php'; 
}
?>
