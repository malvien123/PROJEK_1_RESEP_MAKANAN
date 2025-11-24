<?php
session_start();


if (isset($_GET['aksi']) && $_GET['aksi'] == 'logout') {

    session_unset();
    session_destroy();

    header('Location: ../view/Login.php?status=logout_sukses');
    exit();
}

header('Location: ../view/Login.php'); 
exit();
?>
