<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$nama_user = $_SESSION['username'] ?? 'Pengguna';

$current_user_id = $user_id ?? $_SESSION['id_user'] ?? null; 

// Jika daftar resep belum terdefinisi
if (!isset($daftar_resep)) {
    $daftar_resep = [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Daftar Resep</title>
    <link rel="stylesheet" href="../asset/style_tampil_resep.css">

</head>
<body>
    <div>
        <nav>
            <h2><center>Koleksi Resep</center></h2>
            <div style="display: flex; gap: 15px; justify-content: flex-end; align-items: center; padding: 10px;">
                <div class="welcome">Selamat datang, <?= htmlspecialchars($nama_user); ?></div>
                
                <a href="../controller/c_favorit.php?action=show" style="text-decoration: none; color: white;">
                    <i class="fa fa-heart"></i> Favorit Saya 
                </a>
                
                <a href="../controller/c_resep.php?aksi=logout" 
                   onclick="return confirm('Apakah Anda yakin ingin keluar dari sesi?');" style="text-decoration: none; color: white;">
                    <i class="fa fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </nav>
    </div>
    
    <?php if (empty($daftar_resep)):?>
        <p style="text-align: center; margin-top: 20px;">Belum ada resep yang diunggah.</p>
    <?php else: ?>
        <div class="resep-grid" style="display: flex; flex-wrap: wrap; gap: 20px; padding: 20px; justify-content: center;">
            <?php foreach ($daftar_resep as $resep): 
                // Cek status favorit menggunakan $current_user_id yang lebih terjamin isinya
                $is_favorited = ($current_user_id && isset($favorite_model)) ? 
                                $favorite_model->is_favorited($current_user_id, $resep->id_resep) : false;
            ?>
                <div class="card" style="border: 1px solid #ccc; padding: 15px; width: 300px; border-radius: 8px;">
                    
                    <?php 
                    // Tampilkan gambar BLOB
                    if (!empty($resep->gambar)): 
                    ?>
                        <img src="../controller/c_display_image.php?id_resep=<?php echo $resep->id_resep; ?>" 
                            alt="<?php echo htmlspecialchars($resep->nama_menu); ?>" 
                            style="width: 100%; height: 180px; object-fit: cover; border-radius: 5px; margin-bottom: 10px;">
                    <?php endif; ?>

                    <h3><?php echo htmlspecialchars($resep->nama_menu); ?></h3>
                    
                    <p style="font-size: 0.9em; color: #555;">Diunggah oleh: 
                        <strong><?php echo htmlspecialchars($resep->nama_pengunggah); ?></strong>
                    </p>

                    <p>Deskripsi: <?php echo substr(htmlspecialchars($resep->deskripsi), 0, 80) . '...'; ?></p>

                    <div style="margin-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                        
                        <?php if ($current_user_id): // Tampilkan hanya jika user login ?>
                            
                            <?php if ($is_favorited): ?>
                                <form action="../controller/c_favorit.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="id_resep" value="<?php echo $resep->id_resep; ?>">
                                    <input type="hidden" name="action" value="remove">
                                    <button type="submit" class="btn-favorit active">
                                        <i class="fa fa-heart"></i> Hapus Favorit
                                    </button>
                                </form>
                            <?php else: ?>
                                <form action="../controller/c_favorit.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="id_resep" value="<?php echo $resep->id_resep; ?>">
                                    <input type="hidden" name="action" value="add">
                                    <button type="submit" class="btn-favorit">
                                        <i class="fa fa-heart-o"></i> Tambah Favorit
                                    </button>
                                </form>
                            <?php endif; ?>
                        
                        <?php else: ?>
                            <span style="color: #999; font-size: 0.8em;">Login untuk Favorit</span>
                        <?php endif; ?>

                        <a href="../controller/c_resep.php?aksi=detail&id=<?php echo $resep->id_resep; ?>" class="btn-detail">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</body>
</html>