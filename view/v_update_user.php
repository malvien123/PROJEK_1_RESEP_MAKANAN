<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title> Form Update</title>
    <link rel="stylesheet" href="../asset/style_update.css">
</head>
<body>
  <!-- Navbar -->
    <!-- <a class="edit" aria-current="edit" href="edit_user.php">edit</a> -->
  </li>
  <!-- <li class="nav-daftar"> -->
    <!-- <a class="daftar" href="form_daftar_user.php">daftar</a> -->
  </li>
</ul>

  <div class="form-container">
    <h2>Edit Form User</h2>
    <form method="POST" action="../controller/c_user.php?aksi=update">


    
      <div class="form-grid">
        <div class="left-column">
          
          <input type="hidden" id="id_user" name="id_user"
          value="<?= $users->id_user ?>" >

          <label for="username">Username:</label>
          <input type="text" id="username" name="username"
           value="<?= $users->username ?>"  required>

          <label for="password">Password:</label>
          <input type="password" id="password" name="password" placeholder="Biarkan kosong jika tidak ingin mengubah password"
           value="<?= $users->password ?>" required>
          

          <label for="role">Role:</label>
          <input type="text" id="role" name="role" value="user" 
           value="<?= $users->role ?>" readonly>
        </div>
      </div>


      <div class="submit-button">
        <button type="submit">Update</button>
      </div>
    </form>
  </div>

</body>
</html>

