<?php
// view/v_tampil_user.php

// session_start(); 
// Variabel $users tersedia di sini karena sudah disiapkan oleh c_user.php

// Pastikan $users tersedia, jika tidak (misal error di Model), sediakan array kosong
if (!isset($users)) {
    $users = [];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../asset/style_tampil.css"> 
    <title>Admin - Data Pengguna</title>
    </head>
<body>
    <div>
        <h3>ADMIN DASHBOARD</h3>
        <nav>
            <a href="../view/v_tambah_user.php">Tambah Pengguna</a> |
            <a href="../controller/c_user.php?aksi=logout" 
               onclick="return confirm('Apakah Anda yakin ingin keluar dari sesi?');">
               Logout
            </a>
        </nav>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID User</th>
                <th>Username</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        if (is_array($users) && count($users) > 0):
            foreach ($users as $data ):
        ?>
            <tr>
                <td><?=$no++?></td>
                <td><?=$data->id_user?></td>
                <td><?=$data->username?></td>
                <td class="<?=$data->role === 'admin' ? 'role-admin' : 'role-user'?>"><?=$data->role?></td>
                <td>
                    <a href="../controller/c_user.php?aksi=edit&id=<?= $data->id_user;?>" class="button">Edit</a>
                    <a href="../controller/c_user.php?id=<?= $data->id_user; ?>&aksi=hapus" 
                      onclick="return confirm ('Anda yakin mau menghapus data ini ?')" 
                      class="buton">
                      Hapus
                    </a>
                </td>
            </tr>
        <?php 
            endforeach;
        else:
        ?>
            <tr>
                <td colspan="5" style="text-align: center;">Tidak ada data pengguna yang ditemukan.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</body>
</html>