<?php
// view/v_daftar_favorit.php
// FILE INI HANYA MENAMPILKAN DATA. SEMUA VARIABEL HARUS DISEDIAKAN OLEH CONTROLLER (c_favorit.php)

// Pastikan sesi sudah berjalan
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// KEAMANAN: Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    header('Location: Login.php');
    exit();
}

// Catatan: Variabel $data_resep_favorit sudah tersedia dari Controller c_favorit.php
// Jika array belum terdefinisi (misal: Controller gagal memuat), inisialisasi sebagai array kosong.
if (!isset($data_resep_favorit)) {
    $data_resep_favorit = [];
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Resep Favorit Saya</title>
    <link rel="stylesheet" href="../asset/style_tampil_resep.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        /* Gaya dasar agar kartu resep terlihat rapi */
        .resep-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
            justify-content: center;
        }
        .card {
            border: 1px solid #ccc; /* Diubah dari red ke abu-abu untuk tampilan yang lebih standar */
            padding: 15px;
            width: 300px;
            border-radius: 8px;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <nav style="padding: 15px; background-color: #f8f8f8; display: flex; justify-content: space-between; align-items: center;">
        <h2> Resep Favorit </h2>
       <a href="../controller/c_resep.php?aksi=tampil" style="color: black;">Kembali ke Daftar Resep</a>
    </nav>
    
    <h3 style="text-align: center; margin: 20px 0;">
        Total Resep Tersimpan: <?php echo count($data_resep_favorit); ?>
    </h3>

    <?php if (empty($data_resep_favorit)):?>
        <div style="text-align: center; margin-top: 50px; padding: 20px; border: 1px dashed #ccc; width: 50%; margin-left: auto; margin-right: auto;">
            <p>Anda belum memiliki resep favorit. Coba temukan resep menarik di halaman utama!</p>
        </div>
    <?php else: ?>
        <div class="resep-grid">
            <?php foreach ($data_resep_favorit as $resep): ?>
                <div class="card">
                    
                    <img src="../controller/c_display_image.php?id_resep=<?php echo htmlspecialchars($resep->id_resep); ?>" 
                          alt="<?php echo htmlspecialchars($resep->nama_menu); ?>" 
                          style="width: 100%; height: 180px; object-fit: cover; border-radius: 5px; margin-bottom: 10px;">

                    <h3><?php echo htmlspecialchars($resep->nama_menu); ?></h3>
                    
                    <p style="font-size: 0.9em; color: #555;">Diunggah oleh: 
                        <strong><?php echo htmlspecialchars($resep->nama_pengunggah); ?></strong>
                    </p>
                    
                    <form action="../controller/c_favorit.php" method="POST" style="margin-bottom: 10px;">
                        <input type="hidden" name="id_resep" value="<?php echo htmlspecialchars($resep->id_resep); ?>">
                        <input type="hidden" name="action" value="remove">
                        <button type="submit" style="background-color: #ffcccc; border: 1px solid red; color: red; padding: 5px 10px; border-radius: 5px; cursor: pointer;">
                            <i class="fa fa-heart-crack"></i> Hapus dari Favorit
                        </button>
                    </form>
                    
                    <p>Deskripsi: <?php echo substr(htmlspecialchars($resep->deskripsi), 0, 100) . '...'; ?></p>
                    
                    <a href="../controller/c_resep.php?aksi=detail&id=<?php echo htmlspecialchars($resep->id_resep); ?>">Lihat Detail</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</body>
</html>