<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulir Pendaftaran Pengguna</title>
  <link rel="stylesheet" href="../asset/style_tambah.css">
</head>
<body>

  <div class="container">
    <h2>FORM REGISTRASI</h2>
    <form action="../controller/c_proses_register.php" method="post"> 
      <!-- <label for="id_user">id_user:</label> -->
      
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" required>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required>

      <label for="role">Role:</label>
      <input type="text" id="role" name="role" value="user" readonly>

      <button type="submit" value="Daftar" name="tambah">DAFTAR </button>
      
    </form>
  </div>

</body>
</html>
