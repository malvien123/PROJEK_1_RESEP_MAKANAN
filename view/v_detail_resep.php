<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($detail_resep) || !$detail_resep) {
    
    header('Location: ../controller/c_resep.php'); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Detail Resep: <?php echo htmlspecialchars($detail_resep->nama_menu); ?></title>
    <link rel="stylesheet" href="../asset/style_detail_resep.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="header">
        <a href="../controller/c_resep.php" class="back-link">
            <i class="fa fa-arrow-left"></i> Kembali ke Daftar Resep
        </a>
    </div>
    
    <div class="resep-container">
        <h1><?php echo htmlspecialchars($detail_resep->nama_menu); ?></h1>
        
        <?php if (!empty($detail_resep->gambar)): ?>
            <div class="gambar-container">
                <img src="../controller/c_display_image.php?id_resep=<?php echo $detail_resep->id_resep; ?>" 
                     alt="<?php echo htmlspecialchars($detail_resep->nama_menu); ?>" 
                     class="resep-image">
            </div>
        <?php endif; ?>

        <p class="meta-info">
            Diunggah oleh: 
            <strong>
                <?php 
                // Jika LEFT JOIN berhasil, tampilkan nama. Jika gagal, tampilkan ID User atau "Tidak Dikenal".
                echo htmlspecialchars($detail_resep->nama_pengunggah ?? 'Pengguna Tidak Dikenal'); 
                ?>
            </strong>
        </p>

        <h3>Deskripsi Lengkap</h3>
        <div class="deskripsi-text">
            <p><?php echo nl2br(htmlspecialchars($detail_resep->deskripsi)); ?></p>
        </div>
        
        
    
</body>
</html>