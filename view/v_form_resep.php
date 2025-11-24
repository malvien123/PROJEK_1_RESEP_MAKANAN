<?php
session_start();
include_once '../model/m_resep.php'; 

// KEAMANAN: Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header('Location: Login.php');
    exit();
}

$resep_model = new m_resep();
$aksi = $_GET['aksi'] ?? 'tambah';
$detail_resep = null;
$page_title = "Tambah Resep Baru";
$form_action = "tambah_proses";

// Logika untuk Mode EDIT
if ($aksi == 'edit') {
    $id_resep = $_GET['id'] ?? null;
    
    if ($id_resep) {
        $detail_resep = $resep_model->ambilResepById($id_resep);
        
        // Verifikasi Kepemilikan (Penting!)
        $is_owner = $detail_resep->id_user == $_SESSION['id_user'];
        $is_admin = $_SESSION['role'] == 'Admin';
        
        if (!$detail_resep || (!$is_owner && !$is_admin)) {
            // Jika resep tidak ditemukan atau user tidak berhak mengedit
            header('Location: v_tampil_resep.php?error=unauthorized');
            exit();
        }
        
        $page_title = "Edit Resep: " . htmlspecialchars($detail_resep->nama_menu);
        $form_action = "edit_proses";
    } else {
        // ID tidak ada, arahkan ke Tambah
        $aksi = 'tambah';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="../asset/css/style.css"> 
</head>
<body>

    <header class="navbar">
        <div class="container">
            <h1 class="logo">🍴 Culinary Hub</h1>
            <nav>
                <a href="v_tampil_resep.php">Resep</a>
                <?php if ($_SESSION['role'] == 'Admin'): ?>
                    <a href="v_manajemen_resep_admin.php">Kelola Resep</a>
                    <a href="v_tampil_user.php">Kelola User</a>
                <?php endif; ?>
                <a href="../controller/c_proses_logout.php?aksi=logout" class="btn-logout">Logout</a>
            </nav>
        </div>
    </header>

    <div class="container main-content">
        <p><a href="v_tampil_resep.php" class="back-link">← Kembali</a></p>
        <h2 class="section-title"><?php echo $page_title; ?></h2>

        <form action="../controller/c_resep.php" method="POST" enctype="multipart/form-data" class="recipe-form">
            
            <input type="hidden" name="aksi" value="<?php echo $form_action; ?>">
            <?php if ($detail_resep): ?>
                 <input type="hidden" name="id_resep" value="<?php echo htmlspecialchars($detail_resep->id_resep); ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="nama_menu">Nama Menu:</label>
                <input type="text" id="nama_menu" name="nama_menu" 
                       value="<?php echo htmlspecialchars($detail_resep->nama_menu ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi Singkat:</label>
                <textarea id="deskripsi" name="deskripsi" rows="3" required><?php echo htmlspecialchars($detail_resep->deskripsi ?? ''); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="bahan">Bahan-Bahan:</label>
                <textarea id="bahan" name="bahan" rows="6" required><?php echo htmlspecialchars($detail_resep->bahan ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <label for="langkah">Langkah-Langkah Memasak:</label>
                <textarea id="langkah" name="langkah" rows="8" required><?php echo htmlspecialchars($detail_resep->langkah ?? ''); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="gambar">Gambar Resep:</label>
                <?php if ($detail_resep): ?>
                    <p class="current-image-info">
                        *Biarkan kosong jika tidak ingin mengganti gambar.
                        <br>Gambar saat ini: <img src="../controller/c_display_image.php?id_resep=<?php echo $detail_resep->id_resep; ?>" style="max-height: 50px;">
                    </p>
                <?php endif; ?>
                <input type="file" id="gambar" name="gambar" <?php echo ($aksi == 'tambah' ? 'required' : ''); ?>>
            </div>

            <button type="submit" class="btn-primary">
                <?php echo ($aksi == 'tambah') ? 'Unggah Resep' : 'Simpan Perubahan'; ?>
            </button>
            
        </form>
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Culinary Hub.</p>
        </div>
    </footer>
</body>
</html>